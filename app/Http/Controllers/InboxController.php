<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InboxController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get all conversations (unique senders/receivers)
        $conversations = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) use ($user) {
                return $message->sender_id == $user->id ? $message->receiver_id : $message->sender_id;
            });

        // Filter out conversations with no messages (empty groups)
        $conversations = $conversations->filter(function ($messages) {
            return $messages->count() > 0;
        });

        // Sort conversations by last message created_at descending
        $conversations = $conversations->sortByDesc(function ($messages) {
            return $messages->last()->created_at;
        });

        return view('inbox.index', compact('conversations'));
    }

    public function show($userId)
    {
        $user = Auth::user();
        $otherUser = User::findOrFail($userId);

        // Check if user can message this person
        if (!$this->canUserMessage($user, $otherUser)) {
            abort(403, 'Anda tidak diizinkan untuk mengirim pesan ke pengguna ini.');
        }

        // Get messages between current user and selected user
        $messages = Message::where(function ($query) use ($user, $userId) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($user, $userId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $user->id);
        })->with(['sender', 'receiver'])
          ->orderBy('created_at', 'asc')
          ->get();

        // Mark messages as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', $user->id)
            ->where('status', 'unread')
            ->update(['status' => 'read']);

        return view('inbox.show', compact('messages', 'otherUser'));
    }

    public function store(Request $request, $userId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_message_id' => 'nullable|exists:messages,id',
        ]);

        $user = Auth::user();

        // Restrict users with role 'user' from sending messages
        if ($user->role === 'user') {
            abort(403, 'Kamu tidak bisa mengirim pesan');
        }

        // Fetch the receiver user
        $receiver = User::findOrFail($userId);

        // Check authorization based on role
        if ($user->role === 'seller') {
            // Check if receiver has bought seller's products
            $hasBought = \App\Models\Order::whereHas('product', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('user_id', $receiver->id)->exists();

            if (!$hasBought) {
                abort(403, 'Anda hanya dapat mengirim pesan ke pembeli produk Anda.');
            }
        } elseif ($user->role === 'developer') {
            if (!in_array($receiver->role, ['seller', 'user', 'buyer'])) {
                abort(403, 'Developer can only send messages to sellers or users.');
            }
        }

        // Check if this is a reply and validate reply permissions
        if ($request->parent_message_id) {
            $parentMessage = Message::findOrFail($request->parent_message_id);

            // Check if user can reply to this message
            if (!$this->canReplyToMessage($user, $parentMessage)) {
                abort(403, 'Anda tidak diizinkan untuk membalas pesan ini.');
            }
        }

        Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $userId,
            'content' => $request->content,
            'status' => 'unread',
            'parent_message_id' => $request->parent_message_id,
        ]);

        return redirect()->back()->with('success', 'Pesan berhasil dikirim!');
    }

    public function getUnreadCount()
    {
        $user = Auth::user();
        $unreadCount = Message::where('receiver_id', $user->id)
            ->where('status', 'unread')
            ->count();

        return response()->json(['unread_count' => $unreadCount]);
    }

    public function compose()
    {
        $user = Auth::user();

        // Restrict users with role 'user' from composing messages
        if ($user->role === 'user') {
            abort(403, 'Users with role "user" are not allowed to compose messages.');
        }

        // Get available users based on role
        $availableUsers = collect();

        if ($user->role === 'seller') {
            // Seller can message users who bought their products
            $buyerIds = \App\Models\Order::whereHas('product', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->distinct('user_id')->pluck('user_id');

            $availableUsers = User::whereIn('id', $buyerIds)
                ->where('id', '!=', $user->id)
                ->get();
        } elseif ($user->role === 'developer') {
            // Developer can message sellers/users
            $availableUsers = User::whereIn('role', ['seller', 'user', 'buyer'])
                ->where('id', '!=', $user->id)
                ->get();
        } else {
            // Other roles can message anyone except themselves
            $availableUsers = User::where('id', '!=', $user->id)->get();
        }

        return view('inbox.compose', compact('availableUsers'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'subject' => 'required|string|max:255',
            'content' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        // Restrict users with role 'user' from sending messages
        if ($user->role === 'user') {
            return back()->withErrors(['username' => 'Users with role "user" are not allowed to send messages.']);
        }

        $receiver = User::where('username', $request->username)->first();

        if (!$receiver) {
            return back()->withErrors(['username' => 'Username tidak ditemukan.']);
        }

        if ($receiver->id == $user->id) {
            return back()->withErrors(['username' => 'Anda tidak dapat mengirim pesan ke diri sendiri.']);
        }

        // Check authorization based on role
        if ($user->role === 'seller') {
            // Check if receiver has bought seller's products
            $hasBought = \App\Models\Order::whereHas('product', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('user_id', $receiver->id)->exists();

            if (!$hasBought) {
                return back()->withErrors(['username' => 'Anda hanya dapat mengirim pesan ke pembeli produk Anda.']);
            }
        } elseif ($user->role === 'developer') {
            if (!in_array($receiver->role, ['seller', 'user', 'buyer'])) {
                return back()->withErrors(['username' => 'Developer hanya dapat mengirim pesan ke seller atau user.']);
            }
        }

        $message = Message::sendMessage(
            $user->id,
            $receiver->id,
            $request->subject,
            $request->content,
            $user->role,
            $receiver->role
        );

        return view('inbox.send', compact('message', 'receiver'));
    }

    /**
     * Check if a user can VIEW messages from another user based on roles
     * This is used for viewing conversations, not for sending new messages
     */
    private function canUserMessage($sender, $receiver)
    {
        // Users can view messages from anyone who sent them messages
        // The restriction is only for COMPOSING new messages
        return true;
    }

    /**
     * Check if a user can reply to a specific message
     */
    private function canReplyToMessage($user, $message)
    {
        // Users with role 'user' cannot send any messages, including replies
        if ($user->role === 'user') {
            return false;
        }

        // If the message sender is a developer, check if allow_reply is true
        if ($message->sender->role === 'developer') {
            return $message->allow_reply;
        }

        // For non-developer messages, allow replies based on normal rules
        // Sellers can reply to users and other sellers
        if ($user->role === 'seller' && in_array($message->sender->role, ['user', 'seller'])) {
            return true;
        }

        // Developers can reply to anyone
        if ($user->role === 'developer') {
            return true;
        }

        return false;
    }

    /**
     * Toggle allow_reply for a developer's message
     */
    public function toggleAllowReply($messageId)
    {
        $user = Auth::user();

        // Only developers can toggle allow_reply
        if ($user->role !== 'developer') {
            abort(403, 'Only developers can toggle reply permissions.');
        }

        $message = Message::findOrFail($messageId);

        // Only the sender can toggle allow_reply
        if ($message->sender_id !== $user->id) {
            abort(403, 'You can only toggle reply permissions for your own messages.');
        }

        $message->update([
            'allow_reply' => !$message->allow_reply
        ]);

        return redirect()->back()->with('success', 'Reply permission updated successfully.');
    }
}

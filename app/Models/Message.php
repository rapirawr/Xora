<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'role_sender',
        'role_receiver',
        'subject',
        'content',
        'status',
        'parent_message_id',
        'allow_reply',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function parentMessage()
    {
        return $this->belongsTo(Message::class, 'parent_message_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_message_id');
    }

    /**
     * Send a message from sender to receiver
     */
    public static function sendMessage($senderId, $receiverId, $subject = null, $content, $roleSender = null, $roleReceiver = null)
    {
        return self::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'subject' => $subject,
            'content' => $content,
            'role_sender' => $roleSender,
            'role_receiver' => $roleReceiver,
            'status' => 'unread',
        ]);
    }
}

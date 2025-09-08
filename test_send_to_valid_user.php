<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Message;

$user1 = User::where('username', 'joshua36')->first();
$user2 = User::where('username', 'melvina69')->first();

if ($user1 && $user2) {
    echo "Sending message from {$user1->username} (role: {$user1->role}) to {$user2->username} (role: {$user2->role})\n\n";

    try {
        $message = Message::sendMessage(
            $user1->id,
            $user2->id,
            'Test Subject: Hello from joshua36',
            'This is a test message sent to demonstrate the messaging functionality.',
            $user1->role,
            $user2->role
        );

        echo "✅ Message sent successfully!\n";
        echo "Message ID: {$message->id}\n";
        echo "Subject: {$message->subject}\n";
        echo "Content: {$message->content}\n";
        echo "Status: {$message->status}\n";
        echo "From: {$user1->username} ({$user1->role})\n";
        echo "To: {$user2->username} ({$user2->role})\n";

    } catch (Exception $e) {
        echo "❌ Exception: " . $e->getMessage() . "\n";
    }
} else {
    echo 'One or both users not found' . PHP_EOL;
    if (!$user1) echo 'User joshua36 not found' . PHP_EOL;
    if (!$user2) echo 'User melvina69 not found' . PHP_EOL;
}

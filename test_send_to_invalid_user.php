<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Message;

$user1 = User::where('username', 'joshua36')->first();

if ($user1) {
    echo "Attempting to send message from {$user1->username} to non-existent user 'moziemuach'\n";
    echo "Sender role: {$user1->role}\n\n";

    try {
        // Try to find the receiver
        $receiver = User::where('username', 'moziemuach')->first();

        if (!$receiver) {
            echo "❌ Error: Username 'moziemuach' tidak ditemukan.\n";
            echo "Available usernames: joshua36, melvina69, corbin00, ymorar, leffler.tyrese\n";
            return;
        }

        // This won't execute since receiver is null
        $message = Message::sendMessage($user1->id, $receiver->id, 'Test Subject', 'Test message to invalid user', $user1->role, $receiver->role);
        echo 'Message sent successfully: ' . $message->id . PHP_EOL;

    } catch (Exception $e) {
        echo "❌ Exception: " . $e->getMessage() . "\n";
    }
} else {
    echo 'Sender user not found' . PHP_EOL;
}

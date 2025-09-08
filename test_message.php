<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Message;

$user1 = User::where('username', 'joshua36')->first();
$user2 = User::where('username', 'melvina69')->first();

if ($user1 && $user2) {
    $message = Message::sendMessage($user1->id, $user2->id, null, 'Test message from joshua36 to melvina69', $user1->role, $user2->role);
    echo 'Message sent successfully: ' . $message->id . PHP_EOL;
    echo 'Message content: ' . $message->content . PHP_EOL;
    echo 'From: ' . $user1->username . ' To: ' . $user2->username . PHP_EOL;
} else {
    echo 'Users not found' . PHP_EOL;
    if (!$user1) echo 'User joshua36 not found' . PHP_EOL;
    if (!$user2) echo 'User melvina69 not found' . PHP_EOL;
}

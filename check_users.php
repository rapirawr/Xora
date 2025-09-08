<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$users = User::all();
echo "Total users: " . $users->count() . PHP_EOL;
foreach($users as $user) {
    echo $user->id . ': ' . $user->username . PHP_EOL;
}

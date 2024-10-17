<?php

use App\Services\UserUpdater;
use Illuminate\Support\Facades\Route;


//Auth::route();
Route::get('/', function () {

    $usersToUpdate = [
        ['email' => 'user1@example.com'],
        ['email' => 'user2@example.com'],
        // ... up to 20 users
    ];

    $userUpdater = new UserUpdater();
    $response = $userUpdater->updateUsers($usersToUpdate);
    return view('welcome');
});

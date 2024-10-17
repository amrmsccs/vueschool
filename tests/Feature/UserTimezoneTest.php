<?php
// tests/Feature/UserTimezoneTest.php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);


it('checks if the authenticated user timezone is CET using Macro inUserTimezone', function () {
    // Create a user with the desired timezone
    $user = User::factory()->create([
        'timezone' => 'CET', // Assuming you have a 'timezone' column in your users table
    ]);

    // Simulate authentication
    Auth::login($user);
    //Expect Time As Loggedin user To be Equal to String Time Zone CET
    expect(now()->inUserTimezone()->toString())->toEqual(now()->timezone('CET')->toString());
});

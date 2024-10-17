<?php

use App\Console\Commands\UpdateUserInfo;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithConsoleEvents;

uses(WithConsoleEvents::class);
uses(RefreshDatabase::class);

it('updates user info and using command update:user-info', function () {
    // Create a test user
    $user = User::factory()->create([
        'email' => 'samantha@example.com',
        'firstname' => 'Samantha',
        'lastname' => 'OldLastName',
        'timezone' => 'America/Los_Angeles',
    ]);



    $this->artisan('update:user-info samantha@example.com')->assertSuccessful();

    // Or use this syntax
    //Artisan::call('update:user-info samantha@example.com');

    //Artisan::call('update:user-info', ['email' => 'samantha@example.com']);

    // Refresh the user from the database
    $user->refresh();

    // Assert that the user has been updated
    expect($user->firstname)->not->toBe('Samantha');
    expect($user->lastname)->not->toBe('OldLastName');
    expect($user->timezone)->not->toBeIn(['CET', 'CST', 'GMT+1']);
});

<?php

use Tests\TestCase;
use App\Models\User;
use App\Jobs\SendBatchJob;
use Laravel\Sanctum\Sanctum;
use App\Services\UserUpdater;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;


beforeEach(function () {

    // Create 20 users for testing
    User::factory()->count(20)->create();
});
/*
it('updates user attributes in batches and respects rate limits', function () {
    // Simulate a successful response from the API
    Http::fake([
        'api.example.com/batch-testing' => Http::sequence()
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->push('success', 200)
                                    ->whenEmpty(Http::response())
    ]);

    // Fetch users from the database
    $users = User::select('email')->get()->toArray();

    // Create an instance of UserUpdater
    $userUpdater = new UserUpdater();

    // Call the updateUsers method
    $response = $userUpdater->updateUsers($users);

    // Assert that we sent the correct number of batches
    expect($response)->toHaveCount(20);

    // Check that the jobs are dispatched
    Queue::assertPushed(SendBatchJob::class, 20);

    // Optionally, check that each batch contains the expected number of users
    foreach ($response as $batchResponse) {
        expect($batchResponse['success'])->toBe(true);
    }
});
 */
it('updates user attributes in batches and queues jobs with correct timing', function () {
    // Simulate a successful response from the API for multiple requests
    Http::fake([
        'api.example.com/batch-endpoint' => Http::sequence()
            ->push(['success' => true])
            ->push(['success' => true])
            ->push(['success' => true]) // Add more as needed
    ]);

    // Fetch users from the database
    $users = User::select('email')->get()->toArray();

    // Create an instance of UserUpdater
    $userUpdater = new UserUpdater();
    dd($userUpdater);
    // Call the updateUsers method
    $batches = $userUpdater->updateUsers($users);

    // Queue the batch jobs with timing adjustments
    foreach ($batches as $batch) {
        dd($batch);
        Queue::later(now()->addSeconds(72), new SendBatchJob($batch)); // Adjust timing based on your limits
    }

    // Assert that the correct number of jobs were dispatched
    Queue::assertPushed(SendBatchJob::class, count($batches));
   // Queue::assertPushed()

    // Optionally, check that each batch response indicates success
    foreach ($batches as $batchResponse) {
        expect($batchResponse['success'])->toBe(true);
    }
});


test('Test Token', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['*']
    );
    $user = [
        'email' => User::first()->email,
        'password' => 'password',
        'device_name' => 'Android',
    ];

    $response = $this->post('/api/sanctum/token', $user);

    $response->assertOk();
});

<?php

use Mockery;
use Tests\TestCase;
use App\Models\User;
use App\Jobs\ProcessAPI;
use App\Jobs\SendBatchJob;
use Laravel\Sanctum\Sanctum;
use App\Services\UserUpdater;
use Illuminate\Support\Sleep;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create 20 users for testing
    User::factory()->count(20)->create();
});

it('updates user attributes in batches and queues jobs with correct timing', function () {

    Bus::fake();

    User::whereNotNull('email')
            ->chunkById(2, function (Collection $users) {
                $jobs = collect();
                $users->flatMap(function ($user) use ($jobs) {
                    return $jobs->push(new ProcessAPI($user));
                });


                 Bus::batch($jobs->toArray())->dispatch();
                 //seleep for 72 second until the next batch
                 //Sleep::for(72)->seconds();
                 // Travel to speed the output only otherwise comment this line.
                 $this->travel(72)->seconds();
            }, $column = 'id');

    Bus::assertBatchCount(10);

    $user = User::find(20);

    Log::shouldReceive('info')
        ->with("[{$user->id}] firstname: {$user->firstname} timezone: {$user->timezone}");

});

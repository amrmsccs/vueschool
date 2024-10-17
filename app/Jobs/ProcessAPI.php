<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessAPI implements ShouldQueue
{
    use Batchable, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //Calling Custom Command update:user-info on the user by email.
        Artisan::call('update:user-info '.$this->user->email);

       //Login the change
        Log::info('['.$this->user->id.'] firstname: '.$this->user->firstname+' timezone: '.$this->user->timezone);
    }
}

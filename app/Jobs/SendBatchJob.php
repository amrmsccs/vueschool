<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $batch;

    public function __construct($batch)
    {
        $this->batch = $batch;
    }

    public function handle()
    {
        Http::post('https://vueschool.test/api/batch-testing', [
            'batches' => [[
                'subscribers' => $this->batch,
            ]]
        ]);
    }
}

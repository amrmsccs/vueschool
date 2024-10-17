<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UserUpdater
{
    protected $timeZones = ['CET', 'CST', 'GMT+1'];

    public function updateUsers(array $users)
    {
        $batches = [];
        $currentBatch = [];
        $batchSize = 1000; // Maximum size of each batch
        $maxBatches = 50; // Maximum number of batches per hour

        foreach ($users as $user) {
            $timeZone = $this->getRandomTimeZone();
            $currentBatch[] = [
                'email' => $user['email'],
                'timezone' => $timeZone,
            ];

            // Check if we need to send the batch
            if (count($currentBatch) >= $batchSize) {
                $batches[] = $this->sendBatch($currentBatch);
                $currentBatch = []; // Reset current batch
            }
        }

        // Send any remaining users in the current batch
        if (!empty($currentBatch)) {
            $batches[] = $this->sendBatch($currentBatch);
        }

        return $batches;
    }

    protected function sendBatch(array $subscribers)
    {
        $response = Http::post('https://vueschool.test/api/batch-testing', [
            'batches' => [[
                'subscribers' => $subscribers,
            ]]
        ]);

        return $response->json();
    }

    protected function getRandomTimeZone()
    {
        return $this->timeZones[array_rand($this->timeZones)];
    }
}

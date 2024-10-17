<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;


class UpdateUserInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:user-info {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user firstname, lastname, and random timezone using users email';

    /**
     * @var array
     */
    protected $timeZones = ['CET', 'CST', 'GMT+1'];
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userEmail = $this->argument('email');
        $user = User::whereEmail($userEmail)->first();



        if (!$user) {
            $this->error('User not found.');
            return;
        }

        $faker = \Faker\Factory::create();

        $user->firstname = $faker->firstName;
        $user->lastname = $faker->lastName;
        $user->timezone = $this->getRandomTimeZone();

        $user->save();

        $this->info('User information updated successfully.');
    }

    //get random time zone
    protected function getRandomTimeZone()
    {
        return $this->timeZones[array_rand($this->timeZones)];
    }
}


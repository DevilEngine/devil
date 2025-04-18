<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateUserReputation extends Command
{
    protected $signature = 'users:update-reputation';
    protected $description = 'Update DevilCoin reputation for all users';

    public function handle()
    {
        $this->info('Updating user reputation...');

        User::chunk(100, function ($users) {
            foreach ($users as $user) {
                $user->updateReputation();
            }
        });

        $this->info('Reputation updated successfully!');
    }
}

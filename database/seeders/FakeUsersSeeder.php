<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\User;

class FakeUsersSeeder extends Seeder
{
    public function run()
    {
        User::factory()->count(10)->create([
            'password' => bcrypt('password'), // mot de passe commun
            'coins_spent' => 0,
        ]);
    }
}

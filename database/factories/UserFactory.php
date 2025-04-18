<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $mnemonic = User::generateMnemonic();

        return [
            'username' => strtolower(fake()->unique()->word() . fake()->randomNumber(3)),
            'bio' => fake()->sentence(8),
            'mnemonic_key' => $mnemonic,
            'reputation' => rand(0, 3000),
            'created_at' => now()->subDays(rand(0, 60)),
            'updated_at' => now(),
        ];
    }
}

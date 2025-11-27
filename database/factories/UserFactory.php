<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        return [
            'cpf' => $this->generateCpf(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'phone' => $this->faker->phoneNumber(),
            'cep' => $this->faker->numerify('#####-###'),
            'city' => $this->faker->city(),
            'state' => $this->faker->stateAbbr(),
            'country' => $this->faker->country(),
            'remember_token' => Str::random(10),
            'role_id' => Role::where('id', '<>', Role::ADMIN)->inRandomOrder()->first()->id,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    private function generateCpf(): string
    {
        $n = [];
        for ($i = 0; $i < 9; $i++) {
            $n[$i] = random_int(0, 9);
        }

        // Dígito 1
        $d1 = 0;
        for ($i = 0, $j = 10; $i < 9; $i++, $j--) {
            $d1 += $n[$i] * $j;
        }
        $d1 = ($d1 % 11);
        $d1 = ($d1 < 2) ? 0 : 11 - $d1;

        // Dígito 2
        $d2 = 0;
        for ($i = 0, $j = 11; $i < 9; $i++, $j--) {
            $d2 += $n[$i] * $j;
        }
        $d2 += $d1 * 2;
        $d2 = ($d2 % 11);
        $d2 = ($d2 < 2) ? 0 : 11 - $d2;

        return implode('', $n) . $d1 . $d2;
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'username'       => $this->faker->unique()->firstName,
            'firstname'      => $this->faker->name,
            'lastname'       => $this->faker->lastName,
            'phone'          => $this->faker->phoneNumber,
            'gender'         => $this->faker->boolean,
            'address'        => $this->faker->address,
            'identification' => $this->faker->sentence,
            'email'          => $this->faker->unique()->safeEmail,
            'password'       => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => Str::random(10),
            'created_by'     =>  $this->faker->randomElement(User::query()->pluck('id')->toArray()),
        ];
    }
}

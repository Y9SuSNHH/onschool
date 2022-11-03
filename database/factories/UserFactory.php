<?php

namespace Database\Factories;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'username'          => $this->faker->unique()->firstName,
            'firstname'         => $this->faker->name,
            'lastname'          => $this->faker->lastName,
            'phone'             => $this->faker->phoneNumber,
            'gender'            => $this->faker->boolean,
            'email'             => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'active'            => $this->faker->boolean,
            'remember_token'    => Str::random(10),
            'created_by'        => $this->faker->boolean,
            'role'              => $this->faker->randomElement(UserRoleEnum::getValues()),
        ];
    }
}

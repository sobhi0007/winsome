<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->optional()->numerify('01###########'), 
            'position' => $this->faker->jobTitle(),
            'salary' => $this->faker->numberBetween(3000, 15000),
            'hired_at' => $this->faker->date(),
            'status' => Arr::random(['active', 'inactive']),
        ];
    }
}

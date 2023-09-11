<?php

namespace Database\Factories;

use App\Enums\StudentStatusEnum;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'gender' => fake()->boolean(),
            'birthdate'=> fake()->dateTimeBetween('-30 years','-18 years'),
            'status' => fake()->randomElement(StudentStatusEnum::asArray()),
            'course_id' => Course::query()->inRandomOrder()->value('id'),
        ];
    }
}

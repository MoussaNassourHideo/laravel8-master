<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\comment>
 */
class CommentFactory extends Factory
{
 
    public function definition()
    {
        return [
            'content' => fake()->text,
            'created_at' => fake()->dateTimeBetween('-3 months'),
        ];
    }
}

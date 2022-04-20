<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Lections;

class LectionsFactory extends Factory
{

    protected $model = Lections::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'subject' => $this->faker->unique()->sentence(),
            'description' => $this->faker->paragraph(3),
        ];
    }
}

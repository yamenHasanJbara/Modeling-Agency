<?php

namespace Modules\Model\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Model\Models\Model::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return
        [
            'name' => $this->faker->userName(),
            'date_of_birth' => $this->faker->date('Y-m-d'),
            'height' => $this->faker->numberBetween(150, 190),
            'shoe_size' => $this->faker->numberBetween(33, 45),
            'category_id' => $this->faker->numberBetween(1, 100),
        ];
    }
}


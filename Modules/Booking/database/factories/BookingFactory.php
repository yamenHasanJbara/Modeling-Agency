<?php

namespace Modules\Booking\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Booking\Models\Booking::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return
        [
            'customer_name' => $this->faker->userName(),
            'booking_date' => $this->faker->date,
            'model_id' => $this->faker->numberBetween(1, 100),
        ];
    }
}


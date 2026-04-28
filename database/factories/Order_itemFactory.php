<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class Order_itemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quantity' => $this->faker->numberBetween(1, 5),
            'item_price' => $this->faker->randomFloat(2, 10, 200),
        ];
    }
}
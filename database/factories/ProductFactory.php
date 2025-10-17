<?php

namespace Database\Factories;

use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'sub_category_id' => SubCategory::inRandomOrder()->first()?->id ?? SubCategory::factory(),
            'name' => ucfirst($this->faker->unique()->words(2, true)),
            'price' => $this->faker->randomFloat(2, 100, 10000),
            'description' => $this->faker->sentence(),
        ];
    }
}

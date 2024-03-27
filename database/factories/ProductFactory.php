<?php

namespace Database\Factories;

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();

        return [
            'name' => $faker->word,
            'old_price' => $faker->randomFloat(2, 1, 100),
            'new_price' => $faker->randomFloat(2, 1, 100),
            'description' => $faker->sentence,
            'detail_description' => $faker->paragraph,
            'origin' => $faker->country,
            'standard' => $faker->word,
            'image' => $faker->imageUrl(),
            'category_id' => function () {
                return \App\Models\Category::factory()->create()->id;
            },
        ];
    }
}
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Category;
use Faker\Generator as Faker;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'name' =>$this-> $faker->word,
            'old_price' => $faker->randomFloat(2, 1, 100),
            'new_price' => $faker->randomFloat(2, 1, 100),
            'description' => $faker->sentence,
            'detail_description' => $faker->paragraph,
            'origin' => $faker->country,
            'standard' => $faker->word,
            'image' => $faker->imageUrl(),
            'category_id' => function () {
                return Category::factory()->create()->id;
            },
        ];
    }
}
<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_at' => now(),
            'updated_at' => now(),
            'slug' => $this->faker->unique()->slug,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Category $category) {
            $category->slug = 'category-' . $category->id;
            $category->save();
        });
    }
}

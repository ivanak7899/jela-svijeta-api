<?php

namespace Database\Factories;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredient>
 */
class IngredientFactory extends Factory
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
        return $this->afterCreating(function (Ingredient $ingredient) {
            $ingredient->slug = 'ingredient-' . $ingredient->id;
            $ingredient->save();
        });
    }
}

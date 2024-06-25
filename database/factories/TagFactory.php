<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
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
        return $this->afterCreating(function (Tag $tag) {
            $tag->slug = 'tag-' . $tag->id;
            $tag->save();
        });
    }
}

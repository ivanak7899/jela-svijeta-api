<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Meal;
use App\Models\Category;
use App\Models\Language;
use App\Models\Ingredient;
use App\Models\TagTranslation;
use App\Models\MealTranslation;
use Illuminate\Database\Seeder;
use Database\Seeders\LanguageSeeder;
use App\Models\IngredientTranslation;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $this->call([
            LanguageSeeder::class,
        ]);


        $languages = Language::all()->pluck('code')->toArray();

        Category::factory(6)->create()->each(function ($category) use ($languages, $faker) {
            foreach ($languages as $language) {
                $category->translateOrNew($language)->title = $faker->word;
                $category->save();
            }
        });
        

        // Create tags and seed them with translations
        Tag::factory(10)->create()->each(function ($tag) use ($languages, $faker) {
            foreach ($languages as $language) {
                $tag->translateOrNew($language)->title = $faker->word;
                $tag->save();
            }
        }); 

        // Seed ingredients with translations
        Ingredient::factory(20)->create()->each(function ($ingredient) use ($languages, $faker) {
            foreach ($languages as $language) {
                $ingredient->translateOrNew($language)->title = $faker->word;
                $ingredient->save();
            }
        });

        // Seed meals with translations
        Meal::factory(100)->create()->each(function ($meal) use ($languages, $faker) {
            foreach ($languages as $language) {
                $meal->translateOrNew($language)->title = $faker->word;
                $meal->translateOrNew($language)->description = $faker->paragraph;
                $meal->save();
            }
        });

        //Seed pivot tables:
        $meals = Meal::all();
        $tags = Tag::all();
        $categories = Category::all();
        $ingredients = Ingredient::all();

        $meals->each(function ($meal) use ($tags) {
            $meal->tags()->attach($tags->random(rand(1, 5))->pluck('id'));
        });

        $meals->each(function ($meal) use ($ingredients) {
            $meal->ingredients()->attach($ingredients->random(rand(1, 10))->pluck('id'));
        });

        $meals->each(function ($meal) use ($categories) {
            if (rand(0, 1) == 1) {
                $category = $categories->random();
                $meal->category()->associate($category)->save();
            }
        });
    }
}

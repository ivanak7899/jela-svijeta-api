<?php

namespace App\Services;

use App\Models\Meal;
use App\Models\Language;
use Illuminate\Http\Request;

class MealService
{
    public function getMeals(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $page = $request->input('page', 1);
        $category = $request->input('category');
        $tags = $request->input('tags');
        $with = $request->input('with', []);
        $lang = $request->input('lang');
        $diffTime = $request->input('diff_time');

        // Check if the language exists
        if (!Language::where('code', $lang)->exists()) {
            return collect([]);
        }

        $query = Meal::query();

        if ($request->has('category')) {
            if ($category === 'NULL') {
                $query->whereNull('category_id');
            } elseif ($category === '!NULL') {
                $query->whereNotNull('category_id');
            } else {
                $query->where('category_id', $category);
            }
        }

        if ($tags !== null) {
            $tagsArray = explode(',', $tags);
            $query->whereHas('tags', function ($q) use ($tagsArray) {
                $q->whereIn('tags.id', $tagsArray);
            }, '=', count($tagsArray));
        }

        if ($diffTime !== null) {
            $query->withTrashed()->where(function ($q) use ($diffTime) {
                $q->where('created_at', '>', date('Y-m-d H:i:s', $diffTime))
                    ->orWhere('updated_at', '>', date('Y-m-d H:i:s', $diffTime))
                    ->orWhere('deleted_at', '>', date('Y-m-d H:i:s', $diffTime));
            });
        }

        $withArray = is_array($with) ? $with : explode(',', $with);
        $query->with($withArray);

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function transformMeals($meals, $lang, $diffTime, $withArray)
    {
        return collect($meals->items())->transform(function ($meal) use ($lang, $diffTime, $withArray) {
            $status = 'created';
            if ($diffTime !== null) {
                if ($meal->deleted_at !== null && strtotime($meal->deleted_at) > $diffTime) {
                    $status = 'deleted';
                } elseif (strtotime($meal->updated_at) > $diffTime) {
                    $status = 'modified';
                }
            }

            $mealData = [
                'id' => $meal->id,
                'title' => $meal->translate($lang)->title,
                'description' => $meal->translate($lang)->description,
                'status' => $status,
            ];

            if (in_array('category', $withArray)) {
                $mealData['category'] = $meal->category ? [
                    'id' => $meal->category->id,
                    'title' => $meal->category->translate($lang)->title,
                    'slug' => $meal->category->slug,
                ] : null;
            }

            if (in_array('tags', $withArray)) {
                $mealData['tags'] = $meal->tags->map(function ($tag) use ($lang) {
                    return [
                        'id' => $tag->id,
                        'title' => $tag->translate($lang)->title,
                        'slug' => $tag->slug,
                    ];
                });
            }

            if (in_array('ingredients', $withArray)) {
                $mealData['ingredients'] = $meal->ingredients->map(function ($ingredient) use ($lang) {
                    return [
                        'id' => $ingredient->id,
                        'title' => $ingredient->translate($lang)->title,
                        'slug' => $ingredient->slug,
                    ];
                });
            }

            return $mealData;
        });
    }

    public function buildPaginationLinks(Request $request, $meals)
    {
        $fullUrl = $request->fullUrl();
        $baseUrl = strtok($fullUrl, '?');
        $queryParams = $request->query();

        return [
            'prev' => $meals->previousPageUrl() ? $this->buildPageUrl($queryParams, $meals->currentPage() - 1, $baseUrl) : null,
            'next' => $meals->nextPageUrl() ? $this->buildPageUrl($queryParams, $meals->currentPage() + 1, $baseUrl) : null,
            'self' => $fullUrl,
        ];
    }

    private function buildPageUrl($queryParams, $page, $baseUrl)
    {
        $queryParams['page'] = $page;
        return $baseUrl . '?' . http_build_query($queryParams);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MealService;
use Illuminate\Validation\ValidationException;

class MealController extends Controller
{
    protected $mealService;

    public function __construct(MealService $mealService)
    {
        $this->mealService = $mealService;
    }

    public function index(Request $request)
    {
        // Validating request parameters
        try {
            $request->validate([
                'lang' => 'required|string|size:2', 
                'per_page' => 'integer|min:1',
                'page' => 'integer|min:1',
                'category' => 'nullable|integer|min:1',
                'tags' => ['nullable', 'string', 'regex:/^\d+(,\d+)*$/'],
                'with' => ['nullable', 'string', 'regex:/^(ingredients|category|tags)(,(ingredients|category|tags))*$/'],
                'diff_time' => 'nullable|integer|min:0',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'details' => $e->errors()
            ], 422);
        }

        $lang = $request->input('lang');
        $diffTime = $request->input('diff_time');
        $withArray = is_array($request->input('with', [])) ? $request->input('with', []) : explode(',', $request->input('with', []));

        $meals = $this->mealService->getMeals($request);

        // Check if no meals were found
        if ($meals->isEmpty()) {
            return response()->json([
                'error' => 'No meals found for the given request.'
            ], 404);
        }
        
        $results = $this->mealService->transformMeals($meals, $lang, $diffTime, $withArray);

        $meta = [
            'currentPage' => $meals->currentPage(),
            'totalItems' => $meals->total(),
            'itemsPerPage' => $meals->perPage(),
            'totalPages' => $meals->lastPage(),
        ];

        $links = $this->mealService->buildPaginationLinks($request, $meals);

        return response()->json([
            'meta' => $meta,
            'data' => $results,
            'links' => $links,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function getTrendingCategories()
    {
        $categories = Category::where('id', '!=', 7)
            ->withCount('products')
            ->get()
            ->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'image' => $category->image,
                    'items' => $category->products_count
                ];
            });
        return response()->json($categories);
    }

}

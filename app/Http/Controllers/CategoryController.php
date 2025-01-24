<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function getTrendingCategories()
    {
        $categories = Category::where('id', '!=', 7)->get();
        return response()->json($categories);
    }

}

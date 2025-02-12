<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    //Get the trending categories
    public function getTrendingCategories()
    {
        $categories = Category::where('id', '!=', 7) //Get the categories except one with id = 7
            ->withCount('products') //Get the count of products in the category
            ->get() //Get the categories
            ->map(function($category) { //Map the categories
                return [
                    'id' => $category->id, //Get the id of the category
                    'name' => $category->name, //Get the name of the category
                    'image' => $category->image, //Get the image of the category
                    'items' => $category->products_count //Get the count of products in the category
                ];
            });
        return response()->json($categories); //Return the categories
    }

}

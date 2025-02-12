<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;



class ControllerOllama extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    //Generate a description with Ollama AI
    public function generateDescription(Request $request)
    {
        // Validate the user input (for example, Clothes, Shoes, Mobiles...)
        $request->validate([
            'description' => 'required|string',
        ]);

        //Get the description
        $description = $request->input('description');

        // Send a request to Ollama
        $response = Http::post('http://localhost:11434/api/generate', [
            'model' => 'deepseek-r1',
            'prompt' => "Genera una descripción para un producto: $description", //Generate a description for a product with the following name
        ]);
        dd($response); //Debug the response
        
        // Return the response to the client
        return response()->json([
            'description' => $response->json()['response'], 
        ]);
    }
}
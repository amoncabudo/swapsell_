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
    public function generateDescription(Request $request)
    {
        // Validar la entrada del usuario (por ejemplo, ingredientes o tipo de receta)
        $request->validate([
            'description' => 'required|string',
        ]);

        // Obtener los ingredientes del request
        $description = $request->input('description');

        // Enviar una solicitud a Ollama
        $response = Http::post('http://localhost:11434/api/generate', [
            'model' => 'deepseek-r1',
            'prompt' => "Genera una descripción para un producto con el siguiente nombre: $description",
        ]);
        dd($response);
        // Devolver la respuesta al cliente
        return response()->json([
            'description' => $response->json()['response'],
        ]);
    }
}
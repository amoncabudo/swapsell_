<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EventController extends Controller
{   
    public function index()
    {   
        $isAuthenticated = Auth::check();
        return Inertia::render('Events', [
            'isAuthenticated' => $isAuthenticated
        ]);
    }

    public function addEvent(Request $request)
    {

        $title = $request->get("title");
        $description = $request->get("description");
        $date = $request->get("date");
        $time = $request->get("time");
        $latitude = $request->get("latitude");
        $longitude = $request->get("longitude");
        $image = $request->get("image");

        $userId = auth()->id();

        $event = new Event();
        $event->title = $title;
        $event->description = $description;
        $event->date = $date;
        $event->time = $time;
        $event->longitude = $longitude;
        $event->latitude = $latitude;
        $event->image = $image;
        $event->user_id = $userId;

        $event->save();

        // Validar los datos
        // $request->validate([
        //     'title' => 'required',
        //     'description' => 'required',
        //     'date' => 'required',
        //     'hour' => 'required',
        //     'longitude' => 'required',
        //     'latitude' => 'required',
        // ]);

        // // Crear el usuario
        // $event = Event::create([
        //     'title' => $request->title,
        //     'description' => $request->description,
        //     'date' => $request->date,
        //     'hour' => $request->hour,
        //     'longitude' => $request->longitude,
        //     'latitude' => $request->latitude,
        // ]);

        return redirect(route('adminpanel', absolute: false)); // Retorna el usuario creado
    }
}

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
        // $image = $request->get("image");

        $userId = auth()->id();

        $event = new Event();
        $event->title = $title;
        $event->description = $description;
        $event->date = $date;
        $event->time = $time;
        $event->longitude = $longitude;
        $event->latitude = $latitude;
        $event->image = 'imatge.jpg';
        $event->user_id = $userId;

        $event->save();


        return redirect()->route('Events');
    }

    public function deleteEvent($id){
        $event = Event::find($id);
        $event->delete();
        return redirect()->route('Events');
    }
    
    public function updateEvent(Request $request){
        $id = $request->get("id");
        $title = $request->get("title");
        $description = $request->get("description");
        $date = $request->get("date");
        $time = $request->get("time");
        $longitude = $request->get("longitude");
        $latitude = $request->get("latitude");

        $event = Event::find($id);
        $event->title = $request->get("title", $event->title);
        $event->description = $request->get("description", $event->description);
        $event->date = $request->get("date", $event->date);
        $event->time = $request->get("time", $event->time);
        $event->longitude = $request->get("longitude", $event->longitude);
        $event->latitude = $request->get("latitude", $event->latitude);
        $event->user_id = Auth::id();
        $event->image = 'image.jpg';

        $event->save();
        
        return redirect()->route('Events');
    }

    public function getAllEvents(){
        $events = Event::all();
        $isAuthenticated = Auth::check();
        return Inertia::render("Events", 
        ["events" => $events,
        "isAuthenticated" => $isAuthenticated
    ]);
    }

    public function goEvent($id){
        $event = Event::find($id);
        $isAuthenticated = Auth::check();
        return Inertia::render("UpdateEvent", 
        ["event" => $event,
        "isAuthenticated" => $isAuthenticated,
    ]);
    }

    public function mapae(){

        $events = Event::all();
        return Inertia::render('MapaE',[
            'events' => $events
        ]);
    }
}

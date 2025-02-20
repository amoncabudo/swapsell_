<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use Inertia\Inertia;

class ContactController extends Controller
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

    public function contact()
    {
        return Inertia::render('Contact');
    }

    public function sendContact(Request $request) //Function to send a contact message
    {
        $request->validate([ //Validate the request
            'name' => 'required|string|max:255', //Validate the name
            'cognom' => 'required|string|max:255', //Validate the surname
            'email' => 'required|email', //Validate the email
            'subject' => 'required|string|max:255', //Validate the subject
            'message' => 'required|string', //Validate the message
        ]);

        Mail::to('swapsell6@gmail.com')->send(new ContactMail($request->all())); //Send the email to the admin

        return redirect()->route('contact')->with('message', 'Missatge enviat amb èxit!'); //Redirect to the contact page with the next message
    }
}

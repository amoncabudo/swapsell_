<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Product;
use App\Models\Auction;
use App\Models\User;
use App\Models\Review;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [ //Render the profile edit page
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail, //Check if the user is a must verify email
            'status' => session('status'), //Get the status
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse //Update the user's profile information
    {
        $request->user()->fill($request->validated()); //Fill the user's profile information
        $file=$request->file('image'); //Get the file
        if($file){ //If the file exists
            $name = time() . $file->getClientOriginalName(); //Get the name of the file
    
            $path = $file->storeAs('users', $name, 'public'); //Store the file
        
            $request->user()->image = "storage/".$path; //Set the image of the user
        }
        if ($request->user()->isDirty('email')) { //If the email is dirty
            $request->user()->email_verified_at = null; //Set the email verified at
        }

        $request->user()->save(); //Save the user

        return Redirect::route('profile.edit'); //Redirect to the profile edit page
    }   

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse //Delete the user's account
    {
        $request->validate([ //Validate the request
            'password' => ['required', 'current_password'], //Validate the password
        ]);

        $user = $request->user(); //Get the user

        Auth::lout(); //Logout the user

        $user->delete(); //Delete the user

        $request->session()->invalidate(); //Invalidate the session
        $request->session()->regenerateToken(); //Regenerate the token

        return Redirect::to('/');
    }
    
    public function profile()
    {   
        $user = Auth::user(); //Get the user
        $isAuthenticated = Auth::check(); //Check if the user is authenticated
        $image = User::where('id',$user->id)->get(); //Get the image of the user
        $products = Product::where('user_id', $user->id)->where('status', 1)->get(); //Get the products of the user
        $soldProducts = Product::where('user_id', $user->id) //Get the sold products of the user
            ->where('status', 0) //Where the status is 0
            ->count(); //Count the sold products
        $mediaReview = round(Review::avg('rating'), 2); //Get the media review of the user
        $auction = Auction::where('user_id', $user->id)->with('product')->get(); //Get the auction of the user
        return Inertia::render("Profile", [ //Render the profile page
            "user" => $user, //Set the user
            "image" => $image, //Set the image
            "formattedDate" => Carbon::parse($user->created_at)->isoFormat('D [de] MMM [de] YYYY'), //Set the formatted date
            "isAuthenticated" => $isAuthenticated, //Set the is authenticated
            "products" => $products, //Set the products
            "auction" => $auction, //Set the auction
            "soldProducts" => $soldProducts, //Set the sold products
            "mediaReview" => $mediaReview //Set the media review
        ]);
    }

    public function getUserById($id)
    {
        $user = User::find($id); //Get the user
        $isAuthenticated = Auth::check(); //Check if the user is authenticated
        $image = User::where('id',$user->id)->get(); //Get the image of the user
        $products = Product::where('user_id', $user->id)->where('status', 1)->get(); //Get the products of the user
        $soldProducts = Product::where('user_id', $user->id) //Get the sold products of the user
            ->where('status', 0) //Where the status is 0
            ->count(); //Count the sold products
        $mediaReview = round(Review::avg('rating'), 2); //Get the media review of the user
        $auction = Auction::where('user_id', $user->id)->with('product')->get(); //Get the auction of the user
        return Inertia::render("Profile", [ //Render the profile page
            "user" => $user, //Set the user
            "image" => $image, //Set the image
            "formattedDate" => Carbon::parse($user->created_at)->isoFormat('D [de] MMM [de] YYYY'), //Set the formatted date
            "isAuthenticated" => $isAuthenticated, //Set the is authenticated
            "products" => $products, //Set the products
            "auction" => $auction, //Set the auction
            "soldProducts" => $soldProducts, //Set the sold products
            "mediaReview" => $mediaReview //Set the media review
        ]);
    }
}

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
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());
        $file=$request->file('image');
        if($file){
            $name = time() . $file->getClientOriginalName();
    
            $path = $file->storeAs('images', $name, 'public');
        
            $request->user()->image = "storage/".$path;
        }
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    
    public function profile()
    {   
        $user = Auth::user();
        $isAuthenticated = Auth::check();
        $image = User::where('id',$user->id)->get();
        $products = Product::where('user_id', $user->id)->get();
        $soldProducts = Product::where('user_id', $user->id)
            ->where('status', 0)
            ->count();
        $mediaReview = round(Review::avg('rating'), 2);
        $auction = Auction::where('user_id', $user->id)->with('product')->get();
        return Inertia::render("Profile", [
            "user" => $user,
            "image" => $image,
            "formattedDate" => Carbon::parse($user->created_at)->isoFormat('D [de] MMM [de] YYYY'),
            "isAuthenticated" => $isAuthenticated,
            "products" => $products,
            "auction" => $auction,
            "soldProducts" => $soldProducts,
            "mediaReview" => $mediaReview
        ]);
    }

   
}

<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ControllerProducts;
use App\Http\Controllers\ControllerEvents;
use App\Http\Controllers\ControllerProject;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SellController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/Cart', function () {return Inertia::render('Cart');});

Route::get('/productextend', function () {
    return Inertia::render('ProducteAmpliat', [
        'isAuthenticated' => auth()->check(),
    ]);
});
Route::get('/adminpanel', function () {return Inertia::render('AdminPanel');})->name("adminpanel");

Route::post('/users', [UserController::class, "addUser"])->name("users");
Route::middleware(['auth'])->group(function(){
    Route::post('/events', [EventController::class, "addEvent"])->name("events");
});

Route::get('/mapa', function () {return Inertia::render('Mapa');});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile2', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile2', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile2', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/favorites', function () {
    return Inertia::render('Favorites');
});

Route::get('/events', function () {
    return Inertia::render('Events');
});
Route::get('/auctions', function () {
    return Inertia::render('Auctions');
});
Route::get('/footer', function(){
    return Inertia::render('Footer');
});

Route::get('/aboutus', function(){
    return Inertia::render('AboutUs');
});

Route::get('/profile', function(){
    return Inertia::render('Profile');
});

Route::get('/legal', function(){
    return Inertia::render('Legal');
});

Route::get('/contact', function(){
    return Inertia::render('Contact');
});
Route::get('/products', function () {return Inertia::render('Products');});

Route::get('/eventInfo', function(){
    return Inertia::render('EventInfo');
});

Route::get('/sell', function(){
    return Inertia::render('Sell');
});

Route::get('/updateProduct', function(){
    return Inertia::render('updateProduct');
});

Route::post('/sell', [ProductController::class, "addProduct"])->name("sell");

Route::post('/updateProduct', [ProductController::class, "UpdateProduct"])->name("UpdateProduct");

Route::get('/products', [ControllerProducts::class, "index"])->name("Products");


Route::get('/events', [ControllerEvents::class, "index"])->name("Events");

Route::get('/project', function () {
    return Inertia::render('Project', [
        'isAuthenticated' => auth()->check(),
    ]);
});

Route::get('/subasta', function(){
    return Inertia::render('Subasta');
});

Route::get('/deleteProduct/{id}', [ProductController::class, "deleteProduct"])->name("deleteProduct");
Route::post('/deleteProduct/{id}', [ProductController::class, "deleteProduct"])->name("deleteProduct");

require __DIR__.'/auth.php';

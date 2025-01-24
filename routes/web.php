<?php


use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ControllerProducts;
use App\Http\Controllers\ControllerEvents;
use App\Http\Controllers\ControllerProject;
use App\Http\Controllers\ControllerFavorites;
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
        'isAuthenticated' => auth()->check(),
    ]);
});

Route::get('/Cart', function () {
    $isAuthenticated = Auth::check();
    return Inertia::render('Cart', [
        'isAuthenticated' => $isAuthenticated
    ]);
});

Route::get('/productextend', function () {
    return Inertia::render('ProducteAmpliat', [
        'isAuthenticated' => auth()->check(),
    ]);
});
Route::get('/adminpanel', function () {return Inertia::render('AdminPanel', [
    'isAuthenticated' => auth()->check(),
]);})->name("adminpanel");

Route::post('/users', [UserController::class, "addUser"])->name("users");

Route::middleware(['auth'])->group(function(){
    Route::post('/events', [EventController::class, "addEvent"])->name("events");
    Route::post('/products', [ProductController::class, "addProduct"])->name("products");
});

Route::get('/mapa', [ProductController::class, "mapa"])->name("mapa");

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile2', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile2', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile2', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/favorites', function () {
    return Inertia::render('Favorites',[
        'isAuthenticated' => auth()->check(),
    ]);
});

Route::get('/auctions', function () {
    return Inertia::render('Auctions');
});
Route::get('/footer', function(){
    return Inertia::render('Footer');
});

Route::get('/aboutus', function(){
    return Inertia::render('AboutUs',[
        'isAuthenticated' => auth()->check(),
    ]);
});



Route::get('/legal', function(){
    return Inertia::render('Legal', [
        'isAuthenticated' => auth()->check(),
    ]);
});

Route::get('/contact', function(){
    return Inertia::render('Contact');
});
Route::get('/products', function () {
    return Inertia::render('Products');
});
Route::post('/products', [ProductController::class, 'store']);

Route::get('/eventInfo', function(){
    return Inertia::render('EventInfo',[
        'isAuthenticated' => auth()->check(),
    ]);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/sell', [SellController::class, 'index'])->name('sell');
}); 

Route::get('/updateProduct', function(){
    return Inertia::render('UpdateProduct',[
        'isAuthenticated' => auth()->check(),
    ]);
});

Route::post('/sell', [ProductController::class, "addProduct"])->name("sell");

Route::post('/updateProduct', [ProductController::class, "updateProduct", ])->name("updateProduct");

Route::get('/products', [ProductController::class, "index"])->name("products");
Route::get('/products', [ProductController::class, 'getAllProducts'])->name('Products');
<<<<<<< HEAD
Route::post('/products', [ProductController::class, 'toggleFavourite'])->name('productFavorite');
//Route to show a product by id
Route::get('/products/{id}', [ProductController::class, "goProduct"])->name("product.show");
=======

>>>>>>> 243a01a363de6dc139a193131e9c279fc0f2d224
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
});



Route::get('/events', [EventController::class, "index"])->name("Events");

Route::get('/project', function () {
    return Inertia::render('Project', [
        'isAuthenticated' => auth()->check(),
    ]);
});

Route::get('/subasta', function(){
    return Inertia::render('Subasta', [
        'isAuthenticated' => auth()->check(),
    ]);
});

Route::get('/deleteProduct/{id}', [ProductController::class, "deleteProduct"])->name("deleteProduct");
Route::post('/deleteProduct/{id}', [ProductController::class, "deleteProduct"])->name("deleteProduct");
Route::get('/categories/trending', [CategoryController::class, 'getTrendingCategories'])->name('categories.trending');
require __DIR__.'/auth.php';

<?php

use Inertia\Inertia;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ControllerProducts;
use App\Http\Controllers\ControllerEvents;
use App\Http\Controllers\ControllerProject;
use App\Http\Controllers\ControllerFavorites;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AuctionController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ManagerMiddleware;

// Routes welcome
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'isAuthenticated' => auth()->check(),
    ]);
})->name("Welcome");

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Route AdminPanel
Route::get('/adminpanel', function () {return Inertia::render('AdminPanel', [
    'isAuthenticated' => auth()->check(),
]);})->middleware(AdminMiddleware::class);

//Route Events
Route::post('/updateEvent', [EventController::class, "updateEvent"])->middleware(['auth', 'verified'])->name('updateEvent');;

//Route Cart
Route::get('/Cart', function () {
    $isAuthenticated = Auth::check();
    return Inertia::render('Cart', [
        'isAuthenticated' => $isAuthenticated
    ]);
})->middleware(['auth', 'verified'])->name('cart');

// Route Products
Route::get('/productextend', function () {
    return Inertia::render('ProducteAmpliat', [
        'isAuthenticated' => auth()->check(),
    ]);
});

Route::get('/products', function () {
    return Inertia::render('Products');
})->name('productsview');

Route::post('/products', [ProductController::class, 'store']);

Route::get('/updateProduct', function(){
    return Inertia::render('UpdateProduct',[
        'isAuthenticated' => auth()->check(),
    ]);
})->middleware(['auth', 'verified'])->name('updateProduct');;

Route::post('/products', [ProductController::class, "addProduct"])->name("products");

Route::post('/updateProduct', [ProductController::class, "updateProduct", ])->name("updateProduct");

Route::get('/products', [ProductController::class, "index"])->name("products");
Route::get('/products', [ProductController::class, 'getAllProducts'])->name('Products');
Route::post('/products', [ProductController::class, 'toggleFavourite'])->name('productFavorite');
//Route to show a product by id
Route::get('/products/{id}', [ProductController::class, "goProduct"])->name("product.show");
Route::get('/products/{id}', [ProductController::class, "show"])->name("products.show");

//Route Users
Route::post('/users', [UserController::class, "addUser"])->name("users");

//Route Events
Route::post('/events', [EventController::class, "addEvent"])->name("events");
Route::get('/eventInfo', function(){
    return Inertia::render('EventInfo',[
        'isAuthenticated' => auth()->check(),
    ]);
});

Route::get('/addEvent', function(){
    return Inertia::render('AddEvent');
})->name('addEvent');
Route::get('/updateEvent/{id}', [EventController::class, "goEvent"])->middleware(ManagerMiddleware::class)->name("event.show");

Route::get('/deleteEvent/{id}', [EventController::class, "deleteEvent"])->middleware(ManagerMiddleware::class)->name("deleteEvent");
Route::post('/deleteEvent/{id}', [EventController::class, "deleteEvent"])->middleware(ManagerMiddleware::class)->name("deleteEvent");
Route::get('/events', [EventController::class, 'getAllEvents'])->name('Events');

//Route Mapa
Route::get('/mapa', [ProductController::class, "mapa"])->name("mapa");
Route::get('/mapae', [EventController::class, "mapae"])->name("mapae");

//Route Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile2', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile2', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile2', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
});

//Route Favorites
Route::get('/favorites/all', [ControllerFavorites::class, 'index'])->middleware(['auth', 'verified'])->name('favorites');
Route::get('/favorites', [FavoriteController::class, 'gatAllFavorites'])->name('products_favs');

//Route Sell
Route::middleware(['auth'])->group(function () {
    Route::get('/sell', [SellController::class, 'index'])->middleware(['auth', 'verified'])->name('sell');
}); 
Route::post('/sell', [ProductController::class, "addProduct"])->middleware(['auth', 'verified'])->name("sell");

//Route MOncayo
Route::get('/footer', function(){ return Inertia::render('Footer'); });
Route::get('/aboutus', function(){ return Inertia::render('AboutUs',['isAuthenticated' => auth()->check(),]);});

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
})->name('productsview');
Route::post('/products', [ProductController::class, 'store']);

Route::get('/eventInfo', function(){
    return Inertia::render('EventInfo',[
        'isAuthenticated' => auth()->check(),
    ]);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/sell', [SellController::class, 'index'])->name('sell');
}); 

// Route::get('/updateProduct', function(){
//     return Inertia::render('UpdateProduct',[
//         'isAuthenticated' => auth()->check(),
//     ]);
// });

Route::post('/sell', [ProductController::class, "addProduct"])->name("sell");

Route::post('/updateProduct/{id}', [ProductController::class, "updateProduct"])->name("updateProduct");
Route::get('/products', [ProductController::class, "index"])->name("products");
Route::get('/products', [ProductController::class, 'getAllProducts'])->name('Products');
Route::post('/products', [ProductController::class, 'toggleFavourite'])->name('productFavorite');
Route::get('/editProduct/{id}', [ProductController::class, "editProduct"])->name("editProductId");
//Route to show a product by id
Route::get('/products/{id}', [ProductController::class, "goProduct"])->name("product.show");

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
});

Route::get('/project', function () {
    return Inertia::render('Project', [
        'isAuthenticated' => auth()->check(),
    ]);
});

Route::get('/auction', [ProductController::class, 'auction'])->name('auction');

Route::get('/subasta', function(){
    return Inertia::render('Subasta', [
        'isAuthenticated' => auth()->check(),
    ]);
});

Route::get('/updateProduct/{id}', [ProductController::class, "updateProduct"])->middleware(['auth', 'verified'])->name("updateProductId");
Route::get('/deleteProduct/{id}', [ProductController::class, "deleteProduct"])->middleware(['auth', 'verified'])->name("deleteProduct");
Route::post('/deleteProduct/{id}', [ProductController::class, "deleteProduct"])->middleware(['auth', 'verified'])->name("deleteProduct");
Route::get('/product/featured', [ProductController::class, 'getProductsByCategoryId'])->name('products.featured');
Route::get('/categories/trending', [CategoryController::class, 'getTrendingCategories'])->name('categories.trending');

Route::middleware(['auth'])->group(function () {
    Route::get('/create-auction', [AuctionController::class, 'create'])->name('auctions.create');
    Route::post('/create-auction', [AuctionController::class, 'store'])->name('auctions.store');
    Route::post('/auctions/bid', [AuctionController::class, 'bid'])->name('auctions.bid');
});
require __DIR__.'/auth.php';



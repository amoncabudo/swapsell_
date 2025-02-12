<?php
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
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
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BasketController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ManagerMiddleware;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ControllerReview;

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Routes Welcome
//--------------------------------------------------------------------------------------------------------------------------------------------------------
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'isAuthenticated' => auth()->check(),
    ]);
})->name("Welcome");

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Routes Dashboard
//--------------------------------------------------------------------------------------------------------------------------------------------------------

//routes dashboard
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Routes AdminPanel
//--------------------------------------------------------------------------------------------------------------------------------------------------------

//Route AdminPanel
Route::get('/adminpanel', function () {return Inertia::render('AdminPanel', [
    'isAuthenticated' => auth()->check(),
]);})->middleware(AdminMiddleware::class)->name("AdminPanel");

//Route UsersList
Route::get('adminpanel/users/list', [UserController::class, 'getAllUsers'])->name('users.list');

//Route ProductsList
Route::get('adminpanel/products/list', [ProductController::class, 'getAllProductsAdmin'])->name('products.list');

//Route EventsList
Route::get('adminpanel/events/list', [EventController::class, 'getAllEventsAdmin'])->name('events.list');

//Route DeleteUser
Route::delete('adminpanel/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

//Route DeleteProduct
Route::delete('adminpanel/products/{id}', [ProductController::class, 'AdminDeleteProduct'])->name('products.delete');

//Route DeleteEvent
Route::delete('adminpanel/events/{id}', [EventController::class, 'AdminDeleteEvent'])->name('events.delete');

//Route UpdateUser
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');

//Route UpdateProduct
Route::put('adminpanel/products/{id}', [ProductController::class, 'AdminUpdateProduct'])->name('products.update');

//Route UpdateEvent
Route::put('adminpanel/events/{id}', [EventController::class, 'AdminUpdateEvent'])->name('events.update');

//Route CreateProduct
Route::post('adminpanel/products', [ProductController::class, 'addProduct'])->name('products.addProduct');

//Route CreateEvent
Route::post('/adminpanel/events', [EventController::class, 'addEvent'])->name('event.addEvent');

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Routes Events
//--------------------------------------------------------------------------------------------------------------------------------------------------------

Route::post('/events', [EventController::class, "addEvent"])->name("events");
Route::get('/events', [EventController::class, 'getAllEvents'])->name('Events');

//Route AddEvent
Route::get('/addEvent', function(){return Inertia::render('AddEvent', ['isAuthenticated' => auth() -> check()]);})->middleware('auth', 'verified' , ManagerMiddleware::class)->name('addEvent');

//Route UpdateEvent
Route::get('/updateEvent/{id}', [EventController::class, "goEvent"])->middleware(ManagerMiddleware::class)->name("event.show");
Route::post('/updateEvent', [EventController::class, "updateEvent"])->middleware(['auth', 'verified'])->name('updateEvent');

//Route DeleteEvent
Route::get('/deleteEvent/{id}', [EventController::class, "deleteEvent"])->middleware(ManagerMiddleware::class)->name("deleteEvent");
Route::post('/deleteEvent/{id}', [EventController::class, "deleteEvent"])->middleware(ManagerMiddleware::class)->name("deleteEvent");

//Route EventInfo
Route::get('/eventInfo', function(){return Inertia::render('EventInfo',['isAuthenticated' => auth()->check(),]);});

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Routes Products
//--------------------------------------------------------------------------------------------------------------------------------------------------------

//Route Products
Route::get('/products', function () {return Inertia::render('Products');})->name('productsview');

//Route Products Controller
Route::get('/products', [ProductController::class, "index"])->name("products");

//Route AddProduct
Route::post('/products', [ProductController::class, 'store']);
Route::post('/products', [ProductController::class, "addProduct"])->name("products");

//Route UpdateProduct
Route::get('/updateProduct', function(){return Inertia::render('UpdateProduct',['isAuthenticated' => auth()->check(),]);})->middleware(['auth', 'verified'])->name('updateProduct');;
Route::get('/updateProduct/{id}', [ProductController::class, "updateProduct"])->middleware(['auth', 'verified'])->name("updateProductId");
Route::post('/updateProduct/{id}', [ProductController::class, "updateProduct"])->name("updateProduct");
Route::get('/editProduct/{id}', [ProductController::class, "editProduct"])->name("editProductId");

//Route DeleteProduct
Route::get('/deleteProduct/{id}', [ProductController::class, "deleteProduct"])->middleware(['auth', 'verified'])->name("deleteProduct");
Route::post('/deleteProduct/{id}', [ProductController::class, "deleteProduct"])->middleware(['auth', 'verified'])->name("deleteProduct");

//Route GetAllProducts
Route::get('/products', [ProductController::class, 'getAllProducts'])->name('Products');

//Route ProductExtend
Route::get('/productextend', function () {return Inertia::render('ProducteAmpliat', ['isAuthenticated' => auth()->check(),]);});

//Route ToggleFavourite
Route::post('/products', [ProductController::class, 'toggleFavourite'])->name('productFavorite');

// Route to show a product by id
Route::get('/products/{id}', [ProductController::class, "goProduct"])->name("product.show");

//Route ProductFeatured
Route::get('/product/featured', [ProductController::class, 'getProductsByCategoryId'])->name('products.featured');

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Route Comments
//--------------------------------------------------------------------------------------------------------------------------------------------------------

//Route AddComment
Route::middleware(['auth'])->group(function () {
    Route::post('/comentarios', [CommentController::class, 'addcomment'])->name('comments.addcoment');

});

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Route Users
//--------------------------------------------------------------------------------------------------------------------------------------------------------

//Route AddUser

Route::post('/users', [UserController::class, "addUser"])->name("users");
Route::get('/users', [UserController::class, "index"])->name("users");

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Route Mapa
//--------------------------------------------------------------------------------------------------------------------------------------------------------

//Route Mapa
Route::get('/mapa', [ProductController::class, "mapa"])->name("mapa");

//Route MapaEvent
Route::get('/mapae', [EventController::class, "mapae"])->name("mapae");

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Route Profile
//--------------------------------------------------------------------------------------------------------------------------------------------------------

Route::middleware('auth')->group(function () {
    Route::get('/profile2', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile2', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile2', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
});


//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Route Favorites
//--------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/favorites', [FavoriteController::class, 'getAllFavorites'])->name('products_favs');
Route::get('/favorites/all', [ControllerFavorites::class, 'index'])->middleware(['auth', 'verified'])->name('favorites');

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Route Basket
//--------------------------------------------------------------------------------------------------------------------------------------------------------

//Route Basket
Route::get('/Cart', [BasketController::class, 'getAllBaskets'])->name('cart');

//Route ToggleBasket
Route::post('/products/baskets', [ProductController::class, 'toggleBasket'])->middleware(['auth', 'verified'])->name('baskets_products');

//Route GetAllBaskets
Route::get('/baskets', [BasketController::class, 'getAllBaskets'])->name('products_baskets');

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Route Sell
//--------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/sell', [SellController::class, 'index'])->middleware(['auth', 'verified'])->name('sell');
Route::post('/sell', [ProductController::class, "addProduct"])->middleware(['auth', 'verified'])->name("sell");

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Route Footer
//--------------------------------------------------------------------------------------------------------------------------------------------------------

//Route Footer
Route::get('/footer', function(){ return Inertia::render('Footer'); });

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Route AboutUs
//--------------------------------------------------------------------------------------------------------------------------------------------------------

//Route AboutUs
Route::get('/aboutus', function(){ return Inertia::render('AboutUs',['isAuthenticated' => auth()->check(),]);});

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Route Legal
//--------------------------------------------------------------------------------------------------------------------------------------------------------

//Route Legal
Route::get('/legal', function(){
    return Inertia::render('Legal', [
        'isAuthenticated' => auth()->check(),
    ]);
});

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Route Contact
//--------------------------------------------------------------------------------------------------------------------------------------------------------

//Route Contact
Route::get('/contact', function(){
    return Inertia::render('Contact');
});

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Route Project
//--------------------------------------------------------------------------------------------------------------------------------------------------------

//Route Project
Route::get('/project', function () {
    return Inertia::render('Project', [
        'isAuthenticated' => auth()->check(),
    ]);
});

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Route Auction
//--------------------------------------------------------------------------------------------------------------------------------------------------------

//Route Auction
Route::get('/auction', [ProductController::class, 'auction'])->name('auction');


//Route CreateAuction
Route::middleware(['auth'])->group(function () {
    Route::get('/create-auction', [AuctionController::class, 'create'])->name('auctions.create');
    Route::post('/create-auction', [AuctionController::class, 'store'])->name('auctions.store');
    Route::post('/auctions/bid', [AuctionController::class, 'bid'])->name('auctions.bid');
    Route::post('/auctions/{id}/finish', [AuctionController::class, 'finish'])->name('auctions.finish');
});

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Route CategoriesTrending
//--------------------------------------------------------------------------------------------------------------------------------------------------------

//Route CategoriesTrending
Route::get('/categories/trending', [CategoryController::class, 'getTrendingCategories'])->name('categories.trending');

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Route PayPal
//--------------------------------------------------------------------------------------------------------------------------------------------------------

//Route PayPal
Route::post('/paypal/create-order', [PayPalController::class, 'createOrder']);
Route::post('/paypal/capture-order/{orderId}', [PayPalController::class, 'captureOrder']);
Route::post('/paypal/delete-product/{productId}', [PayPalController::class, 'deleteProduct']);

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Route Transactions
//--------------------------------------------------------------------------------------------------------------------------------------------------------

//Route Transactions
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');

require __DIR__.'/auth.php';

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//Routes to login with google
//--------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('login-google', function () {
    return Socialite::driver('google')->redirect();
})->name('login-google');
Route::get('google-callback', function () {
    $user = Socialite::driver('google')->user();

    $userExists = User::where('email', $user->email)->first();
    if ($userExists) {
        Auth::login($userExists);
        return redirect('/');
    }else {
        $newUser = User::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => Hash::make('password'),
        ]);
        Auth::login($newUser);
        return redirect('/');
    }
});
//--------------------------------------------------------------------------------------------------------------------------------------------------------


//Route reviews
Route::get('/reviews', [ControllerReview::class, 'index'])->name('reviews.index');
Route::post('/reviewAdd', [ControllerReview::class, 'store'])->name('reviews.store');

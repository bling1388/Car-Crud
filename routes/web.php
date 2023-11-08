<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\CarController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);

// Registration
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Users
Route::middleware('auth')->group(function () {
    Route::get('/purchased-cars', [UserController::class, 'showPurchasedCars'])->name('purchased-cars');
});



// Session

Route::post('/purchase', [UserController::class, 'purchase'])->middleware('auth.buy');
Route::post('/add-to-cart', [UserController::class, 'addToCart'])->name('add');
Route::post('/update-cart-quantity', [UserController::class, 'quantity_update']);
Route::delete('/cart/delete/{id}', [UserController::class, 'deleteFromCart'])->name('cart.delete');




// Cart

Route::get('/cart', [UserController::class, 'cart'])->name('cart');



//User cars
Route::get('/', [CarController::class, 'cars'])->name('cars');


// Admin

Route::middleware(['auth', 'admin'])->group(function () {

    //Dashboard and users
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/user-edit/{id}', [AdminController::class, 'user_edit'])->name('user.edit');
    Route::put('/user-update/{id}', [AdminController::class, 'user_update'])->name('user.update');
    Route::delete('/user-destroy/{id}', [AdminController::class, 'user_destroy'])->name('user.destroy');

    //Cars
    Route::get('/admin/cars', [AdminController::class, 'cars'])->name('admin.cars');
    Route::post('/car-store', [AdminController::class, 'car_store'])->name('admin.car.store');
    Route::get('/car-edit/{id}', [AdminController::class, 'car_edit'])->name('admin.car.edit');
    Route::put('/car-update/{id}', [AdminController::class, 'car_update'])->name('admin.car.update');
    Route::delete('/car-destroy/{id}', [AdminController::class, 'car_destroy'])->name('admin.car.destroy');
    Route::patch('/cars/change-status/{id}', [AdminController::class, 'changeStatus'])->name('cars.changeStatus');

    //Category
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::post('/category-store', [AdminController::class, 'category_store'])->name('admin.category.store');
    Route::get('/category-edit/{id}', [AdminController::class, 'category_edit'])->name('admin.category.edit');
    Route::put('/category-update/{id}', [AdminController::class, 'category_update'])->name('admin.category.update');
    Route::delete('/category-destroy/{id}', [AdminController::class, 'category_destroy'])->name('admin.category.destroy');

    //Subcategory
    Route::get('/admin/subcategories', [AdminController::class, 'subcategories'])->name('admin.subcategories');
    Route::post('/subcategory-store', [AdminController::class, 'subcategory_store'])->name('admin.subcategory.store');
    Route::get('/subcategory-edit/{id}', [AdminController::class, 'subcategory_edit'])->name('admin.subcategory.edit');
    Route::put('/subcategory-update/{id}', [AdminController::class, 'subcategory_update'])->name('admin.subcategory.update');
    Route::delete('/subcategory-destroy/{id}', [AdminController::class, 'subcategory_destroy'])->name('admin.subcategory.destroy');

});
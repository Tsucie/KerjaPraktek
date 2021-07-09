<?php

use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\FacilityController;
use App\Http\Controllers\API\MoreFacilityController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\PromoController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VenueController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Routes Global (guests)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/VenueGetList', [VenueController::class, 'getList']);
Route::get('/Venue/{id}', [VenueController::class, 'show']);

Route::get('/ProductGetList', [ProductController::class, 'getList']);
Route::get('/Product/{id}', [ProductController::class, 'show']);

Route::get('/PromoGetList', [PromoController::class, 'getList']);
Route::get('/PromoGetData/{id}', [PromoController::class], 'show');

// Routes Login Admin
Route::get('/login', 'App\Http\Controllers\Auth\AuthUserAdmin@showLoginForm')->name('login');
Route::post('/login', 'App\Http\Controllers\Auth\AuthUserAdmin@login')->name('login');

// Routes group for Admin
Route::group(['middleware' => 'auth:web'], function () {
    Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
    Route::get('/users', 'App\Http\Controllers\API\UserController@index')->name('users');
    Route::get('/customers', 'App\Http\Controllers\API\CustomerController@index')->name('customers');
    
    // Admin Profile Routes
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
    
    // Route group API Resources (CRUD)
    Route::apiResources([
        'Facility' => FacilityController::class,
        'MoreFacility' => MoreFacilityController::class,
        'Promo' => PromoController::class,
        'User' => UserController::class
    ]);
    Route::get('/Customer/{id}', [CustomerController::class, 'show']);
    // Routes Venue (API)
    Route::get('/Venue', [VenueController::class, 'index'])->name('venues');
    Route::post('/Venue', [VenueController::class, 'store']);
    Route::put('/Venue', [VenueController::class, 'update']);
    Route::delete('/Venue', [VenueController::class, 'destroy']);
    Route::put('/VenuePhoto', [VenueController::class, 'updatePhoto']);
    Route::delete('/VenuePhoto', [VenueController::class, 'destroyPhoto']);

    // Routes Product (API)
    Route::get('/Product', [ProductController::class, 'index'])->name('products');
    Route::post('/Product', [ProductController::class, 'store']);
    Route::put('/Product', [ProductController::class, 'update']);
    Route::delete('/Product', [ProductController::class, 'destroy']);
    Route::put('/ProductPhoto', [ProductController::class, 'updatePhoto']);
    Route::delete('/ProductPhoto', [ProductController::class, 'destroyPhoto']);

    // Route Logout Admin
    Route::post('/logout', 'App\Http\Controllers\Auth\AuthUserAdmin@logout')->name('logout');
});

// Routes SignUp Customer
Route::post('/Customer/SignUp', [CustomerController::class, 'store']);

// Routes SignIn Customer

// Routes group for Customer
Route::group(['middleware' => 'auth:customer'], function () {

});
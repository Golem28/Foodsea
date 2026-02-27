<?php

use Illuminate\Support\Facades\Route;
use App\Application\ChefkochAPI;

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

Route::get('/search', function () {
    $categories = (new ChefkochAPI())->getCategories();

    // Check if there are any validation errors flashed to the session
    if (session()->has('errors')) {
        $errors = session()->get('errors')->getBag('default');
        // Pass the errors to the view
        return view('filters', ["categories" => $categories, "errors" => $errors]);
    }

    return view('filters', ["categories" => $categories]);
})->name('search');

Route::get('/result', function () {
    $min_kochzeit = request()->input('min_kochzeit');
    $max_kochzeit = request()->input('max_kochzeit');
    $zutaten = request()->input('zutaten');
    $categories = request()->input('categories');
    $rating = request()->input('rating');

    return view('recipes', ["min_kochzeit" => $min_kochzeit, "max_kochzeit" => $max_kochzeit, "zutaten" => $zutaten, "categories" => $categories, "rating" => $rating]);

})->name('result');

Route::get('/concept', function () {
    return view('concept');
})->name('concept');

Auth::routes();

// Favourites Routes
Route::get('/favourites', [App\Http\Controllers\FavouriteController::class, 'index'])->name('favourites')->middleware('auth');
Route::get('/favourites/delete/{recepe_id}', [App\Http\Controllers\FavouriteController::class, 'delete'])->name('favourites/delete')->middleware('auth');
Route::get('/favourites/create/{recepe_id}', [App\Http\Controllers\FavouriteController::class, 'create'])->name('favourites/create')->middleware('auth');

// Recipe Routes
Route::get('/recipe/create/', [App\Http\Controllers\RecipeController::class, 'index'])->name('recipe');
Route::get('/recipe/delete/{id}', [App\Http\Controllers\RecipeController::class, 'show'])->name('recipe');

// Home Route
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Authentication Routes...
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Registration Routes...
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Password Reset Routes...
Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset']);


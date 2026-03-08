<?php

use App\Application\Queries\GetAllRecipeCategoriesQuery;
use App\Application\Queries\GetRecipeQuery;
use App\Application\Queries\SearchRecipesQuery;
use App\Domain\Recipe\ValueObject\RecipeFilter;
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

Route::get('/search', function (GetAllRecipeCategoriesQuery $query) {
    $categories = $query->execute(null);

    // Check if there are any validation errors flashed to the session
    if (session()->has('errors')) {
        $errors = session()->get('errors')->getBag('default');
        // Pass the errors to the view
        return view('filters', ["categories" => $categories, "errors" => $errors]);
    }

    return view('filters', ["categories" => $categories]);
})->name('search');

Route::get('/result', action: function () {
    // TODO $categories = request()->input('categories', []);
    // TODO $rating = request()->float('rating');

    $filter = new RecipeFilter(
        [],
        $ingredients = request()->input('ingredients', []),
        request()->string('title'),
        request()->integer('maxTimeTotal'),
        request()->integer('minTimeTotal')
    );

    $recipes = app(SearchRecipesQuery::class)->execute($filter);

    $viewRecipe = [];
    foreach ($recipes as $recipe) {
        $viewRecipe[] = (object) [
            'id' => $recipe->id->getValue(),
            'originUrl' => 'laravel.com',
            'imageUrl' => 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fthumbs.dreamstime.com%2Fz%2Fhealthy-eating-plate-vector-illustration-labeled-educational-food-example-scheme-vegetables-whole-grains-fruit-protein-as-185358717.jpg&f=1&nofb=1&ipt=6d8ae872e424a59e8292d13ad7e621cf17ae0822eb8b0ebff528b1b21b179cde',
            'title' => $recipe->title,
            'isFavourite' => false,
        ];
    }

    return view('recipes', ['recipes' => $viewRecipe]);
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


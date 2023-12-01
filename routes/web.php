<?php

use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages/website/stats');
})->name('stats');

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
    'login' => false
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Website
Route::get('/main', [WebsiteController::class, 'main'])->name('main');
Route::get('/comparison', [WebsiteController::class, 'comparison'])->name('comparison');
Route::get('/discover-most-popular', [WebsiteController::class, 'inDemand'])->name('most-in-demand');
Route::get('/discover-whats-hot', [WebsiteController::class, 'mostSearched'])->name('whats-hot');
Route::get('/reviews', [WebsiteController::class, 'reviews'])->name('reviews');

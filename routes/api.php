<?php

use App\Http\Controllers\WebsiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => '/'], function () {
    Route::group(['prefix' => 'reviews'], function () {
        Route::post('store', [WebsiteController::class, 'storeReviews'])->name('reviews.store');
        Route::get('getReviews', [WebsiteController::class, 'getReviews'])->name('reviews.get');
        Route::post('getShoeReviews', [WebsiteController::class, 'getShoeReviews'])->name('reviews.get_shoe_review');
        Route::post('getShoeReviewsComparison', [WebsiteController::class, 'getShoeReviewsComparison'])->name('reviews.get_shoe_review_comparison');
        Route::post('storeShoeReview', [WebsiteController::class, 'shoeReviews'])->name('reviews.shoe_review_store');
        Route::post('getAverageRating', [WebsiteController::class, 'getAverageRating'])->name('reviews.shoe_average_rating');
    });
});
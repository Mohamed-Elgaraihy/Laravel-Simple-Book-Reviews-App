<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('books.index');
});

Route::Resource('books',BookController::class)->only('index','show');

//Route::Resource('books.reviews',ReviewController::class)->scoped(['review'=>'book'])->only(['store','create']);

Route::get('books/{book}/reviews/create',[ReviewController::class,'create'])->name('books.reviews.create');
//we use middleware to force the user to only make a 3 review through 1 hour as we validate in AppServiceProvide RateLimiter Method
Route::post('books/{book}/reviews',[ReviewController::class,'store'])->name('books.reviews.store')->middleware('throttle:reviews');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleSearchController;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/search', [ArticleSearchController::class, 'index'])->name('articles.search');
Route::get('/api/search-articles', [ArticleSearchController::class, 'search'])->name('articles.api.search');
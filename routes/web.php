<?php

use App\Enums\TaxonomyType;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::inertia('/contact', 'contact')->name('contact');

Route::get('/', HomeController::class)
    ->name('home');
Route::get('pages/{page:slug}', PageController::class)
    ->name('pages.show');

Route::get('news/category/{category:slug}', CategoryController::class)
    ->defaults('type', TaxonomyType::News)
    ->name('news.category');
Route::get('documents/category/{category:slug}', CategoryController::class)
    ->defaults('type', TaxonomyType::Documents)
    ->name('documents.category');

Route::controller(NewsController::class)->group(function () {
    Route::get('news', 'index')
        ->name('news.index');
    Route::get('news/{news:slug}', 'show')
        ->name('news.show');
});

Route::controller(DocumentController::class)->group(function () {
    Route::get('documents', 'index')
        ->name('documents.index');
    Route::get('documents/{document:slug}', 'show')
        ->name('documents.show');
});

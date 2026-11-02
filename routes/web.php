<?php

use App\Enums\GalleryType;
use App\Enums\TaxonomyType;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\GalleryPhotoController;
use App\Http\Controllers\GalleryVideoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PlacementController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)
    ->name('home');
Route::get('pages/{page:slug}', PageController::class)
    ->name('pages.show');
Route::get('announcements/{announcement:slug}', AnnouncementController::class)
    ->name('announcements.show');
Route::get('/contact', ContactController::class)
    ->name('contact');

Route::get('news/category/{category:slug}', CategoryController::class)
    ->setDefaults([
        'component' => 'news/category',
        'type' => TaxonomyType::News,
    ])
    ->name('news.category');
Route::get('documents/category/{category:slug}', CategoryController::class)
    ->setDefaults([
        'component' => 'documents/category',
        'type' => TaxonomyType::Documents,
    ])
    ->name('documents.category');
Route::get('gallery/photos/category/{category:slug}', CategoryController::class)
    ->setDefaults([
        'component' => 'gallery/photos/category',
        'type' => TaxonomyType::Gallery,
        'gallery' => GalleryType::Photos,
    ])
    ->name('gallery.photos.category');
Route::get('gallery/videos/category/{category:slug}', CategoryController::class)
    ->setDefaults([
        'component' => 'gallery/videos/category',
        'type' => TaxonomyType::Gallery,
        'gallery' => GalleryType::Videos,
    ])
    ->name('gallery.videos.category');

Route::controller(PlacementController::class)->group(function () {
    Route::get('/placements', 'index')
        ->name('placements.index');
});

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

Route::controller(GalleryPhotoController::class)->group(function () {
    Route::get('gallery/photos', 'index')
        ->name('gallery.photos.index');
    Route::get('gallery/photos/{gallery:slug}', 'show')
        ->name('gallery.photos.show');
});

Route::controller(GalleryVideoController::class)->group(function () {
    Route::get('gallery/videos', 'index')
        ->name('gallery.videos.index');
    Route::get('gallery/videos/{gallery:slug}', 'show')
        ->name('gallery.videos.show');
});

<?php

namespace App\Http\Controllers;

use App\Enums\GalleryType;
use App\Enums\SortByType;
use App\Enums\StatusType;
use App\Enums\TaxonomyType;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\GalleryResource;
use App\Models\Gallery;
use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GalleryVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $sortBy = $request->enum(
            'sort_by',
            SortByType::class,
            SortByType::Latest
        );

        $gallery = Inertia::scroll(fn () => Gallery::query()
            ->with(['categories', 'media'])
            ->where([
                'type' => GalleryType::Videos,
                'status' => StatusType::Published,
            ])
            ->sortBy($sortBy)
            ->paginate(perPage: 12)
            ->withQueryString()
            ->toResourceCollection(GalleryResource::class)
        );

        $categories = Inertia::once(fn () => Taxonomy::query()
            ->type(TaxonomyType::Gallery->value)
            ->root()
            ->ordered()
            ->get()
            ->toResourceCollection(CategoryResource::class),
        )->as('gallery.categories');

        return Inertia::render('gallery/videos/list', [
            'galleries' => $gallery,
            'categories' => $categories,
            'filters' => ['sort_by' => $sortBy],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery): Response
    {
        $gallery = $gallery->load(['categories', 'views', 'media'])
            ->toResource(GalleryResource::class);

        $suggestions = Inertia::once(fn () => Gallery::query()
            ->with(['categories', 'views', 'media', 'creator'])
            ->where([
                'type' => GalleryType::Videos,
                'status' => StatusType::Published,
            ])
            ->whereKeyNot($gallery->getKey())
            ->inRandomOrder()
            ->limit(4)
            ->get()
            ->toResourceCollection(GalleryResource::class)
        )
            ->as('gallery.videos.suggestions');

        return Inertia::render('gallery/videos/show', [
            'gallery' => $gallery,
            'suggestions' => $suggestions,
        ]);
    }
}

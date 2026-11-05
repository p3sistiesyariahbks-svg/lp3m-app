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
use App\Settings\AppearanceSettings;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Rankbeam\Seo\Data\SEOData;

class GalleryPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, AppearanceSettings $appearance): Response
    {
        $sortBy = $request->enum(
            'sort_by',
            SortByType::class,
            SortByType::Latest
        );

        $gallery = Inertia::scroll(fn () => Gallery::query()
            ->with(['categories', 'photos.media'])
            ->where([
                'type' => GalleryType::Photos,
                'status' => StatusType::Published,
            ])
            ->sortBy($sortBy)
            ->paginate(perPage: $appearance->items_per_page)
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

        return Inertia::render('gallery/photos/list', [
            'galleries' => $gallery,
            'categories' => $categories,
            'filters' => ['sort_by' => $sortBy],
        ])->withSeo(new SEOData(
            title: 'Photos',
            description: 'Documentation of research and community service activities.',
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery): Response
    {
        views($gallery)->record();

        $gallery = $gallery->load(['categories', 'views', 'photos.media'])
            ->toResource(GalleryResource::class);

        $suggestions = Inertia::once(fn () => Gallery::query()
            ->with(['categories', 'views', 'photos.media'])
            ->where([
                'type' => GalleryType::Photos,
                'status' => StatusType::Published,
            ])
            ->whereKeyNot($gallery->getKey())
            ->inRandomOrder()
            ->limit(4)
            ->get()
            ->toResourceCollection(GalleryResource::class)
        )
            ->as('gallery.photos.suggestions');

        return Inertia::render('gallery/photos/show', [
            'gallery' => $gallery,
            'suggestions' => $suggestions,
        ])->withSeo($gallery->resource);
    }
}

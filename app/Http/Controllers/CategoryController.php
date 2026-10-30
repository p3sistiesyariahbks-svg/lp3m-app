<?php

namespace App\Http\Controllers;

use App\Enums\GalleryType;
use App\Enums\SortByType;
use App\Enums\StatusType;
use App\Enums\TaxonomyType;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\GalleryResource;
use App\Http\Resources\NewsResource;
use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Request $request,
        Taxonomy $category,
        string $component,
        TaxonomyType $type,
        ?GalleryType $gallery = null,
    ) {
        $sortBy = $request->enum(
            'sort_by',
            SortByType::class,
            SortByType::Latest
        );

        $items = Inertia::scroll(fn () => match ($type) {
            TaxonomyType::News => $category->news()
                ->with(['categories', 'media'])
                ->where('news.status', StatusType::Published)
                ->sortBy($sortBy)
                ->paginate(perPage: 12)
                ->withQueryString()
                ->toResourceCollection(NewsResource::class),
            TaxonomyType::Documents => $category->documents()
                ->with(['categories', 'media'])
                ->where('documents.status', StatusType::Published)
                ->sortBy($sortBy)
                ->paginate(perPage: 12)
                ->withQueryString()
                ->toResourceCollection(DocumentResource::class),
            TaxonomyType::Gallery => $category->galleries()
                ->with(['categories', 'media', 'photos.media'])
                ->where([
                    'galleries.type' => $gallery,
                    'galleries.status' => StatusType::Published,
                ])
                ->sortBy($sortBy)
                ->paginate(perPage: 12)
                ->withQueryString()
                ->toResourceCollection(GalleryResource::class),
        });

        $category = $category->toResource(CategoryResource::class);

        $categories = Inertia::once(fn () => Taxonomy::query()
            ->type($type->value)
            ->root()
            ->ordered()
            ->get()
            ->toResourceCollection(CategoryResource::class)
        )->as("{$type->value}.categories");

        return Inertia::render($component, [
            'items' => $items,
            'category' => $category,
            'categories' => $categories,
            'filters' => ['sort_by' => $sortBy],
        ]);
    }
}

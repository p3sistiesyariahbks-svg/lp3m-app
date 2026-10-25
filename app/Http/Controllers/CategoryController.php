<?php

namespace App\Http\Controllers;

use App\Enums\SortByType;
use App\Enums\StatusType;
use App\Enums\TaxonomyType;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\NewsResource;
use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Taxonomy $category, TaxonomyType $type)
    {
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
        });

        $category = $category->toResource(CategoryResource::class);

        $categories = Inertia::once(fn () => Taxonomy::getNestedTree($type->value)
            ->toResourceCollection(CategoryResource::class)
        )->as("{$type->value}.categories");

        return Inertia::render("{$type->value}/category", [
            'items' => $items,
            'category' => $category,
            'categories' => $categories,
            'filters' => ['sort_by' => $sortBy],
        ]);
    }
}

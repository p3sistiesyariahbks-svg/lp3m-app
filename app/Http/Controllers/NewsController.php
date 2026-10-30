<?php

namespace App\Http\Controllers;

use App\Enums\SortByType;
use App\Enums\StatusType;
use App\Enums\TaxonomyType;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\NewsResource;
use App\Models\News;
use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
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

        $news = Inertia::scroll(fn () => News::query()
            ->with(['categories', 'media'])
            ->where('status', StatusType::Published)
            ->sortBy($sortBy)
            ->paginate(perPage: 12)
            ->withQueryString()
            ->toResourceCollection(NewsResource::class)
        );

        $categories = Inertia::once(fn () => Taxonomy::query()
            ->type(TaxonomyType::News->value)
            ->root()
            ->ordered()
            ->get()
            ->toResourceCollection(CategoryResource::class)
        )->as('news.categories');

        return Inertia::render('news/list', [
            'news' => $news,
            'categories' => $categories,
            'filters' => ['sort_by' => $sortBy],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news): Response
    {
        $news = $news->load(['categories', 'views', 'media'])
            ->toResource(NewsResource::class);

        $suggestions = Inertia::once(fn () => News::query()
            ->with(['categories', 'views', 'media'])
            ->where('status', StatusType::Published)
            ->whereKeyNot($news->getKey())
            ->inRandomOrder()
            ->limit(4)
            ->get()
            ->toResourceCollection(NewsResource::class)
        )
            ->as('news.suggestions');

        return Inertia::render('news/show', [
            'news' => $news,
            'suggestions' => $suggestions,
        ]);
    }
}

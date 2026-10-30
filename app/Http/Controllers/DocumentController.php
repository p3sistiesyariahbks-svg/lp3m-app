<?php

namespace App\Http\Controllers;

use App\Enums\SortByType;
use App\Enums\StatusType;
use App\Enums\TaxonomyType;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DocumentController extends Controller
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

        $documents = Inertia::scroll(fn () => Document::query()
            ->with(['categories', 'media'])
            ->where('status', StatusType::Published)
            ->sortBy($sortBy)
            ->paginate(perPage: 12)
            ->withQueryString()
            ->toResourceCollection(DocumentResource::class)
        );

        $categories = Inertia::once(fn () => Taxonomy::query()
            ->type(TaxonomyType::Documents->value)
            ->root()
            ->ordered()
            ->get()
            ->toResourceCollection(CategoryResource::class)
        )->as('documents.categories');

        return Inertia::render('documents/list', [
            'documents' => $documents,
            'categories' => $categories,
            'filters' => ['sort_by' => $sortBy],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document): Response
    {
        $document = $document->load('media')
            ->toResource(DocumentResource::class);

        return Inertia::render('documents/show', [
            'document' => $document,
        ]);
    }
}

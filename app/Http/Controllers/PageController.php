<?php

namespace App\Http\Controllers;

use App\Http\Resources\PageResource;
use App\Models\Page;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Page $page): Response
    {
        $page = $page->load(['media'])
            ->toResource(PageResource::class);

        return Inertia::render('pages/show', [
            'page' => $page,
        ]);
    }
}

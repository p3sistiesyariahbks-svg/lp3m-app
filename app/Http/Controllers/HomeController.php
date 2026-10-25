<?php

namespace App\Http\Controllers;

use App\Enums\StatusType;
use App\Http\Resources\NewsResource;
use App\Http\Resources\PartnerResource;
use App\Models\News;
use App\Models\Partner;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $news = Inertia::once(fn () => News::query()
            ->with(['categories', 'views', 'media'])
            ->where('status', StatusType::Published)
            ->inRandomOrder()
            ->limit(4)
            ->get()
            ->toResourceCollection(NewsResource::class)
        )
            ->as('home.news');

        $partners = Inertia::once(fn () => Partner::query()
            ->with(['media'])
            ->where('status', StatusType::Published)
            ->latest('published_at')
            ->get()
            ->toResourceCollection(PartnerResource::class)
        );

        return Inertia::render('home', [
            'news' => $news,
            'partners' => $partners,
        ]);
    }
}

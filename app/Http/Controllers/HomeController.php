<?php

namespace App\Http\Controllers;

use App\Enums\GalleryType;
use App\Enums\StatusType;
use App\Http\Resources\AnnouncementResource;
use App\Http\Resources\GalleryResource;
use App\Http\Resources\NewsResource;
use App\Http\Resources\PartnerResource;
use App\Models\Announcement;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Partner;
use App\Settings\AppearanceSettings;
use App\Settings\SeoSettings;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Rankbeam\Seo\Data\SEOData;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Request $request,
        SeoSettings $seo,
        AppearanceSettings $appearance,
    ): Response {
        $news = Inertia::once(fn () => News::query()
            ->with(['categories', 'views', 'media'])
            ->where('status', StatusType::Published)
            ->latest('published_at')
            ->limit(6)
            ->get()
            ->toResourceCollection(NewsResource::class)
        )
            ->as('home.news');

        $photos = Inertia::once(fn () => Gallery::query()
            ->with(['categories', 'photos.media'])
            ->where([
                'type' => GalleryType::Photos,
                'status' => StatusType::Published,
            ])
            ->latest('published_at')
            ->limit(6)
            ->get()
            ->toResourceCollection(GalleryResource::class)
        )
            ->as('home.gallery.photos');

        $videos = Inertia::once(fn () => Gallery::query()
            ->with(['categories', 'media'])
            ->where([
                'type' => GalleryType::Videos,
                'status' => StatusType::Published,
            ])
            ->latest('published_at')
            ->limit(6)
            ->get()
            ->toResourceCollection(GalleryResource::class)
        )
            ->as('home.gallery.videos');

        $announcements = Inertia::scroll(fn () => Announcement::query()
            ->where('status', StatusType::Published)
            ->latest('published_at')
            ->paginate(perPage: $appearance->items_per_page, pageName: 'announcements_page')
            ->withQueryString()
            ->toResourceCollection(AnnouncementResource::class)
        );

        $partners = Inertia::once(fn () => Partner::query()
            ->with(['media'])
            ->where('status', StatusType::Published)
            ->latest('published_at')
            ->get()
            ->toResourceCollection(PartnerResource::class)
        );

        return Inertia::render('home', [
            'news' => $news,
            'photos' => $photos,
            'videos' => $videos,
            'announcements' => $announcements,
            'partners' => $partners,
        ])->withSeo(new SEOData(
            title: $seo->homepage_title ?: 'Home',
            description: 'Advancing knowledge and serving communities through research and collaboration.',
        ));
    }
}

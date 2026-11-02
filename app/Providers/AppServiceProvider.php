<?php

namespace App\Providers;

use App\Enums\StatusType;
use App\Livewire\MenuItems;
use App\Models\Announcement;
use App\Models\Document;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Page;
use Carbon\CarbonImmutable;
use Filament\Tables\Enums\ColumnManagerLayout;
use Filament\Tables\Enums\ColumnManagerResetActionPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\FiltersResetActionPosition;
use Filament\Tables\Table;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Inertia\Response;
use Livewire\Livewire;
use Rankbeam\Seo\Data\SEOData;
use Rankbeam\Seo\Facades\SEO;
use Spatie\Sitemap\Tags\Url;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureSitemaps();

        JsonResource::withoutWrapping();

        Response::macro('withSeo', function (Model|SEOData $seo) {
            return $this->with('seo', SEO::forInertia($seo))
                ->withViewData(['seoModel' => $seo]);
        });

        Stat::configureUsing(function (Stat $component): void {
            $component->placeholder('-');
        });

        Table::configureUsing(function (Table $table): void {
            $table
                ->paginated([5, 10, 25, 50])
                ->defaultPaginationPageOption(10)
                ->extremePaginationLinks()
                ->filtersLayout(FiltersLayout::Modal)
                ->filtersResetActionPosition(FiltersResetActionPosition::Footer)
                ->columnManagerLayout(ColumnManagerLayout::Modal)
                ->columnManagerResetActionPosition(ColumnManagerResetActionPosition::Footer)
                ->reorderableColumns();
        });

        Livewire::component('menu-builder-items', MenuItems::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    /**
     * Register the sitemap sources for SEO.
     */
    protected function configureSitemaps(): void
    {
        SEO::sitemaps()->register('static', fn () => [
            Url::create(route('home'))->setPriority(1.0),
            Url::create(route('contact'))->setPriority(5.0),
            Url::create(route('placements.index'))->setPriority(8.0),
            Url::create(route('news.index'))->setPriority(8.0),
            Url::create(route('documents.index'))->setPriority(8.0),
            Url::create(route('gallery.photos.index'))->setPriority(8.0),
            Url::create(route('gallery.videos.index'))->setPriority(8.0),
        ]);

        SEO::sitemaps()->register('pages', fn () => Page::query()
            ->where('status', StatusType::Published)
            ->get()
            ->map(fn (Page $page) => Url::create($page->getUrlForSEO())
                ->setLastModificationDate($page->updated_at)
                ->setPriority(0.8)
            )
        );

        SEO::sitemaps()->register('announcements', fn () => Announcement::query()
            ->where('status', StatusType::Published)
            ->get()
            ->map(fn (Announcement $announcement) => Url::create($announcement->getUrlForSEO())
                ->setLastModificationDate($announcement->updated_at)
                ->setPriority(0.8)
            )
        );

        SEO::sitemaps()->register('news', fn () => News::query()
            ->where('status', StatusType::Published)
            ->get()
            ->map(fn (News $news) => Url::create($news->getUrlForSEO())
                ->setLastModificationDate($news->updated_at)
                ->setPriority(0.8)
            )
        );

        SEO::sitemaps()->register('documents', fn () => Document::query()
            ->where('status', StatusType::Published)
            ->get()
            ->map(fn (Document $document) => Url::create($document->getUrlForSEO())
                ->setLastModificationDate($document->updated_at)
                ->setPriority(0.8)
            )
        );

        SEO::sitemaps()->register('galleries', fn () => Gallery::query()
            ->where('status', StatusType::Published)
            ->get()
            ->map(fn (Gallery $gallery) => Url::create($gallery->getUrlForSEO())
                ->setLastModificationDate($gallery->updated_at)
                ->setPriority(0.8)
            )
        );
    }
}

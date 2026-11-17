<?php

namespace App\Providers;

use App\Enums\StatusType;
use App\Livewire\MenuItems;
use App\Models\Announcement;
use App\Models\Document;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Page;
use App\Settings\AppearanceSettings;
use App\Settings\EmailSettings;
use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentTimezone;
use Filament\Tables\Enums\ColumnManagerLayout;
use Filament\Tables\Enums\ColumnManagerResetActionPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\FiltersResetActionPosition;
use Filament\Tables\Table;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        $this->configureSettings();
        $this->configureSitemaps();

        JsonResource::withoutWrapping();

        Response::macro('withSeo', function (Model|SEOData $seo) {
            return $this->with('seo', SEO::forInertia($seo))
                ->withViewData(['seoModel' => $seo]);
        });

        Date::serializeUsing(function (CarbonInterface $date) {
            return $date->timezone(app(GeneralSettings::class)->timezone)
                ->format(app(GeneralSettings::class)->date_format);
        });

        FilamentTimezone::set(
            fn (): string => app(GeneralSettings::class)->timezone,
        );

        FilamentColor::register(fn (): array => [
            'primary' => Color::hex(app(AppearanceSettings::class)->brand_color),
        ]);

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
                ->reorderableColumns()
                ->lazyLoaded()
                ->defaultDateDisplayFormat(
                    fn (): string => app(GeneralSettings::class)->date_format,
                )
                ->defaultDateTimeDisplayFormat(
                    fn (): string => app(GeneralSettings::class)->date_format,
                );
        });

        Schema::configureUsing(function (Schema $schema) {
            $schema
                ->defaultDateDisplayFormat(
                    fn (): string => app(GeneralSettings::class)->date_format,
                )
                ->defaultDateTimeDisplayFormat(
                    fn (): string => app(GeneralSettings::class)->date_format,
                );
        });

        DatePicker::configureUsing(function (DatePicker $picker): void {
            $picker->displayFormat(
                fn (): string => app(GeneralSettings::class)->date_format,
            );
        });

        DateTimePicker::configureUsing(function (DateTimePicker $picker): void {
            $picker->displayFormat(
                fn (): string => app(GeneralSettings::class)->date_format,
            );
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
     * Apply the stored settings to the runtime configuration.
     */
    protected function configureSettings(): void
    {
        Config::set([
            'app.name' => app(GeneralSettings::class)->site_name,
            'app.timezone' => app(GeneralSettings::class)->timezone,
            'app.locale' => app(GeneralSettings::class)->site_language->value,
        ]);

        Config::set([
            'seo.site_name' => app(GeneralSettings::class)->site_name,
            'seo.title_suffix' => app(SeoSettings::class)->title_suffix,
            'seo.default_og_image' => transform(
                app(SeoSettings::class)->og_image,
                fn (string $path) => Storage::disk('public')->url($path),
            ),
            'seo.verification' => app(SeoSettings::class)->verification,
            'seo.favicon' => transform(
                app(GeneralSettings::class)->favicon,
                fn (string $path) => Storage::disk('public')->url($path),
            ),
        ]);

        Config::set([
            'mail.default' => app(EmailSettings::class)->mail_driver->value,
            'mail.mailers.smtp.host' => app(EmailSettings::class)->smtp_host,
            'mail.mailers.smtp.port' => app(EmailSettings::class)->smtp_port,
            'mail.mailers.smtp.username' => app(EmailSettings::class)->smtp_username,
            'mail.mailers.smtp.password' => app(EmailSettings::class)->smtp_password,
            'mail.from.address' => app(EmailSettings::class)->from_email_address,
            'mail.from.name' => app(EmailSettings::class)->from_name,
            'services.postmark.key' => app(EmailSettings::class)->postmark_api_key,
            'services.resend.key' => app(EmailSettings::class)->resend_api_key,
        ]);
    }

    /**
     * Register the sitemap sources for SEO.
     */
    protected function configureSitemaps(): void
    {
        SEO::sitemaps()->register('static', fn () => [
            Url::create(route('home'))->setPriority(1.0),
            Url::create(route('contact'))->setPriority(0.5),
            Url::create(route('news.index'))->setPriority(0.8),
            Url::create(route('documents.index'))->setPriority(0.8),
            Url::create(route('gallery.photos.index'))->setPriority(0.8),
            Url::create(route('gallery.videos.index'))->setPriority(0.8),
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

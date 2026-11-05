<?php

namespace App\Http\Middleware;

use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Settings\AppearanceSettings;
use App\Settings\GeneralSettings;
use App\Settings\SocialLinksSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'menu' => [
                'header' => fn () => Menu::location('header')?->toResource(MenuResource::class),
                'footer' => fn () => Menu::location('footer')?->toResource(MenuResource::class),
            ],
            'settings' => [
                'general' => fn () => [
                    ...app(GeneralSettings::class)->toArray(),
                    'site_logo' => transform(
                        app(GeneralSettings::class)->site_logo,
                        fn (string $path): string => Storage::disk('public')->url($path),
                    ),
                ],
                'socialLinks' => fn () => app(SocialLinksSettings::class)->toArray(),
                'appearance' => fn () => app(AppearanceSettings::class)->toArray(),
            ],
        ]);
    }
}

<?php

namespace App\Providers\Filament;

use App\Filament\Workspace\Pages\Auth\EditProfile;
use App\Settings\AppearanceSettings;
use AzGasim\FilamentUnsavedChangesModal\FilamentUnsavedChangesModalPlugin;
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Data\AuthPageConfig;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Cog\Laravel\Ban\Http\Middleware\LogsOutBannedUser;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Enums\Platform;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class WorkspacePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('workspace')
            ->path('workspace')
            ->topNavigation()
            ->unsavedChangesAlerts()
            ->viteTheme('resources/css/filament/workspace/theme.css')
            ->login()
            ->colors([])
            ->defaultThemeMode(app(AppearanceSettings::class)->default_theme)
            ->discoverResources(in: app_path('Filament/Workspace/Resources'), for: 'App\Filament\Workspace\Resources')
            ->discoverPages(in: app_path('Filament/Workspace/Pages'), for: 'App\Filament\Workspace\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Workspace/Widgets'), for: 'App\Filament\Workspace\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                LogsOutBannedUser::class,
            ])
            ->profile(EditProfile::class, isSimple: false)
            ->plugins([
                AuthDesignerPlugin::make()
                    ->defaults(fn (AuthPageConfig $config) => $config
                        ->media(
                            media: transform(
                                app(AppearanceSettings::class)->media_path,
                                fn (string $path) => Storage::disk('public')->url($path),
                            ),
                            alt: app(AppearanceSettings::class)->media_alt,
                        )
                        ->mediaPosition(app(AppearanceSettings::class)->layout_style)
                        ->blur(app(AppearanceSettings::class)->blur)
                    )
                    ->login()
                    ->passwordReset(fn ($config) => $config
                        ->mediaPosition(MediaPosition::Left)
                        ->mediaSize(app(AppearanceSettings::class)->media_size)
                    ),
                FilamentUnsavedChangesModalPlugin::make()
                    ->modalWidth('md')
                    ->modalIcon('heroicon-o-exclamation-triangle')
                    ->modalIconColor('warning')
                    ->stayButtonColor('gray')
                    ->leaveButtonColor('danger'),
            ])
            ->globalSearchFieldSuffix(fn (): ?string => match (Platform::detect()) {
                Platform::Windows, Platform::Linux => 'CTRL+K',
                Platform::Mac => '⌘K',
                default => null,
            });
    }
}

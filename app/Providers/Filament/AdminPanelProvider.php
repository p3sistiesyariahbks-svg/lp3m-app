<?php

namespace App\Providers\Filament;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Filament\Pages\ManageGeneral;
use App\Filament\Resources\Announcements\AnnouncementResource;
use App\Filament\Resources\DocumentCategories\DocumentCategoryResource;
use App\Filament\Resources\Documents\DocumentResource;
use App\Filament\Resources\GalleryCategories\GalleryCategoryResource;
use App\Filament\Resources\GalleryPhotos\GalleryPhotoResource;
use App\Filament\Resources\GalleryVideos\GalleryVideoResource;
use App\Filament\Resources\Menus\MenuResource;
use App\Filament\Resources\News\NewsResource;
use App\Filament\Resources\NewsCategories\NewsCategoryResource;
use App\Filament\Resources\Pages\PageResource;
use App\Filament\Resources\Partners\PartnerResource;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuLocation;
use App\Models\Page;
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Data\AuthPageConfig;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Cog\Laravel\Ban\Http\Middleware\LogsOutBannedUser;
use Datlechin\FilamentMenuBuilder\FilamentMenuBuilderPlugin;
use Datlechin\FilamentMenuBuilder\MenuPanel\ModelMenuPanel;
use Datlechin\FilamentMenuBuilder\MenuPanel\StaticMenuPanel;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\GridDirection;
use Filament\Support\Enums\Platform;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Vite;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use SpyApp\ThemeEdinburgh\ThemeEdinburghPlugin;
use Waguilar\FilamentGuardian\FilamentGuardianPlugin;

use function Filament\Support\original_request;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\Filament\Clusters')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
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
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder
                    ->items([
                        NavigationItem::make('Dashboard')
                            ->icon(Heroicon::OutlinedHome)
                            ->isActiveWhen(fn (): bool => original_request()->routeIs('filament.admin.pages.dashboard'))
                            ->url(fn (): string => Dashboard::getUrl()),
                    ])
                    ->groups([
                        NavigationGroup::make('News')
                            ->items([
                                NavigationItem::make('Add News')
                                    ->icon(Heroicon::OutlinedPlus)
                                    ->isActiveWhen(fn (): bool => original_request()->routeIs('filament.admin.resources.news.create'))
                                    ->url(fn (): string => NewsResource::getUrl('create')),
                                NavigationItem::make(NewsResource::getNavigationLabel())
                                    ->icon(NewsResource::getNavigationIcon())
                                    ->isActiveWhen(fn (): bool => original_request()->routeIs([
                                        'filament.admin.resources.news.index',
                                        'filament.admin.resources.news.edit',
                                    ]))
                                    ->url(fn (): string => NewsResource::getUrl('index')),
                                ...NewsCategoryResource::getNavigationItems(),
                            ]),
                        NavigationGroup::make('Pages')
                            ->items([
                                NavigationItem::make('Add Page')
                                    ->icon(Heroicon::OutlinedPlus)
                                    ->isActiveWhen(fn (): bool => original_request()->routeIs('filament.admin.resources.pages.create'))
                                    ->url(fn (): string => PageResource::getUrl('create')),
                                NavigationItem::make(PageResource::getNavigationLabel())
                                    ->icon(PageResource::getNavigationIcon())
                                    ->isActiveWhen(fn (): bool => original_request()->routeIs([
                                        'filament.admin.resources.pages.index',
                                        'filament.admin.resources.pages.edit',
                                    ]))
                                    ->url(fn (): string => PageResource::getUrl('index')),
                            ]),
                        NavigationGroup::make('Announcements')
                            ->items([
                                NavigationItem::make('Add Announcement')
                                    ->icon(Heroicon::OutlinedPlus)
                                    ->isActiveWhen(fn (): bool => original_request()->routeIs('filament.admin.resources.announcements.create'))
                                    ->url(fn (): string => AnnouncementResource::getUrl('create')),
                                NavigationItem::make(AnnouncementResource::getNavigationLabel())
                                    ->icon(AnnouncementResource::getNavigationIcon())
                                    ->isActiveWhen(fn (): bool => original_request()->routeIs([
                                        'filament.admin.resources.announcements.index',
                                        'filament.admin.resources.announcements.edit',
                                    ]))
                                    ->url(fn (): string => AnnouncementResource::getUrl('index')),
                            ]),
                        NavigationGroup::make('Documents')
                            ->items([
                                NavigationItem::make('Add Document')
                                    ->icon(Heroicon::OutlinedPlus)
                                    ->isActiveWhen(fn (): bool => original_request()->routeIs('filament.admin.resources.documents.create'))
                                    ->url(fn (): string => DocumentResource::getUrl('create')),
                                NavigationItem::make(DocumentResource::getNavigationLabel())
                                    ->icon(DocumentResource::getNavigationIcon())
                                    ->isActiveWhen(fn (): bool => original_request()->routeIs([
                                        'filament.admin.resources.documents.index',
                                        'filament.admin.resources.documents.edit',
                                    ]))
                                    ->url(fn (): string => DocumentResource::getUrl('index')),
                                ...DocumentCategoryResource::getNavigationItems(),
                            ]),
                        NavigationGroup::make('Gallery')
                            ->items([
                                ...GalleryPhotoResource::getNavigationItems(),
                                ...GalleryVideoResource::getNavigationItems(),
                                ...GalleryCategoryResource::getNavigationItems(),
                            ]),
                        NavigationGroup::make('Partners')
                            ->items([
                                NavigationItem::make('Add Partner')
                                    ->icon(Heroicon::OutlinedPlus)
                                    ->isActiveWhen(fn (): bool => original_request()->routeIs('filament.admin.resources.partners.create'))
                                    ->url(fn (): string => PartnerResource::getUrl('create')),
                                NavigationItem::make(PartnerResource::getNavigationLabel())
                                    ->icon(PartnerResource::getNavigationIcon())
                                    ->isActiveWhen(fn (): bool => original_request()->routeIs([
                                        'filament.admin.resources.partners.index',
                                        'filament.admin.resources.partners.edit',
                                    ]))
                                    ->url(fn (): string => PartnerResource::getUrl('index')),
                            ]),
                        NavigationGroup::make('Administrator')
                            ->items([
                                ...MenuResource::getNavigationItems(),
                                ...UserResource::getNavigationItems(),
                                ...RoleResource::getNavigationItems(),
                                NavigationItem::make(SettingsCluster::getNavigationLabel())
                                    ->icon(SettingsCluster::getNavigationIcon())
                                    ->isActiveWhen(fn (): bool => original_request()->routeIs('filament.admin.settings.*'))
                                    ->url(fn (): string => ManageGeneral::getUrl()),
                            ]),
                    ]);
            })
            ->plugins([
                ThemeEdinburghPlugin::make(),
                AuthDesignerPlugin::make()
                    ->defaults(fn (AuthPageConfig $config) => $config
                        ->media(Vite::asset('resources/images/background.webp'), alt: 'ISNJ Bengkalis')
                        ->mediaPosition(MediaPosition::Cover)
                        ->blur(0)
                    )
                    ->login()
                    ->passwordReset(fn ($config) => $config
                        ->mediaPosition(MediaPosition::Left)
                        ->mediaSize('50%')
                    )
                    ->themeToggle(),
                FilamentGuardianPlugin::make()
                    ->superAdmin()
                    ->superAdminRoleName('Super Admin')
                    ->superAdminIntercept('before')
                    ->navigationLabel('Roles & Permissions')
                    ->navigationIcon('heroicon-o-shield-check')
                    ->activeNavigationIcon(null)
                    ->navigationGroup('Administrator')
                    ->navigationSort(0)
                    ->navigationBadge(null)
                    ->navigationBadgeColor(null)
                    ->navigationParentItem(null)
                    ->registerNavigation()
                    ->modelLabel('Role & Permission')
                    ->pluralModelLabel('Roles & Permissions')
                    ->slug('roles')
                    ->roleSectionLabel('Role Details')
                    ->roleSectionDescription('')
                    ->roleSectionIcon(false)
                    ->roleSectionAside(false)
                    ->permissionsSectionLabel('Permissions')
                    ->permissionsSectionDescription('')
                    ->permissionsSectionIcon(false)
                    ->permissionsSectionAside(false)
                    ->showResourcesTab()
                    ->showPagesTab()
                    ->showWidgetsTab()
                    ->showCustomPermissionsTab()
                    ->resourcesTabIcon(false)
                    ->pagesTabIcon(false)
                    ->widgetsTabIcon(false)
                    ->customTabIcon(false)
                    ->permissionCheckboxColumns(4)
                    ->permissionCheckboxGridDirection(GridDirection::Column)
                    ->collapseResourceSections(false)
                    ->resourceSectionColumns(1)
                    ->showResourceSectionIcon(false)
                    ->searchIcon(null)
                    ->permissionAssignedIcon(null)
                    ->selectAllOnIcon(false)
                    ->selectAllOffIcon(false)
                    ->contentTabLabel(null)
                    ->contentTabIcon(null)
                    ->contentTabPosition(null)
                    ->combineRelationManagerTabsWithContent(false)
                    ->combineRelationManagerTabsWithContentOnView(false)
                    ->combineRelationManagerTabsWithContentOnEdit(false),
                FilamentMenuBuilderPlugin::make()
                    ->addLocations([
                        'header' => 'Header',
                        'footer' => 'Footer',
                    ])
                    ->addMenuPanels([
                        ModelMenuPanel::make()
                            ->model(Page::class),
                        StaticMenuPanel::make()
                            ->add('Home', '/')
                            ->add('Placements', '/placements')
                            ->add('News', '/news')
                            ->add('Document', '/documents')
                            ->add('Photo', '/gallery/photos')
                            ->add('Video', '/gallery/videos')
                            ->add('Contact', '/contact'),
                    ])
                    ->showCustomLinkPanel()
                    ->showCustomTextPanel()
                    ->addMenuFields([])
                    ->addMenuItemFields([])
                    ->navigationLabel('Menus')
                    ->navigationGroup('Administrator')
                    ->navigationIcon('heroicon-o-bars-3')
                    ->navigationSort(0)
                    ->navigationCountBadge(false)
                    ->enableIndentActions()
                    ->maxDepth(2)
                    ->translatable([])
                    ->translatableMenuFields([])
                    ->translatableMenuItemFields([])
                    ->usingMenuModel(Menu::class)
                    ->usingMenuItemModel(MenuItem::class)
                    ->usingMenuLocationModel(MenuLocation::class)
                    ->usingResource(MenuResource::class),
            ])
            ->globalSearchFieldSuffix(fn (): ?string => match (Platform::detect()) {
                Platform::Windows, Platform::Linux => 'CTRL+K',
                Platform::Mac => '⌘K',
                default => null,
            });
    }
}

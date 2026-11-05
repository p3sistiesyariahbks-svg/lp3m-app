<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Settings\SeoSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ManageSeo extends SettingsPage
{
    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static ?string $navigationLabel = 'SEO';

    protected static string $settings = SeoSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Group::make([
                    Section::make('SEO Defaults')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextInput::make('homepage_title')
                                        ->nullable()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('Homepage Title')
                                        ->placeholder('Welcome to My Site')
                                        ->helperText('Title shown on the homepage'),
                                    TextInput::make('title_suffix')
                                        ->nullable()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('Title Suffix')
                                        ->placeholder('| My Site')
                                        ->helperText('Appended to all page titles (e.g., " | My Site")'),
                                    Textarea::make('meta_description')
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(1024)
                                        ->label('Default Meta Description')
                                        ->autosize()
                                        ->helperText('Used when pages don\'t have their own description (max 160 chars)')
                                        ->columnSpanFull(),
                                    FileUpload::make('og_image')
                                        ->nullable()
                                        ->image()
                                        ->maxSize(1024)
                                        ->acceptedFileTypes([
                                            'image/png',
                                            'image/jpeg',
                                        ])
                                        ->getUploadedFileNameForStorageUsing(
                                            fn (TemporaryUploadedFile $file): string => 'og-image.'.$file->extension(),
                                        )
                                        ->label('Default Social Image')
                                        ->imageEditor()
                                        ->disk('public')
                                        ->visibility('public')
                                        ->downloadable()
                                        ->helperText('Used for social sharing when pages don\'t have their own image')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    Section::make('Search Engine Indexing')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextInput::make('verification.google')
                                        ->nullable()
                                        ->string()
                                        ->alphaDash()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('Google Search Console')
                                        ->helperText('Meta tag: google-site-verification'),
                                    TextInput::make('verification.bing')
                                        ->nullable()
                                        ->string()
                                        ->alphaDash()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('Bing Webmaster Tools')
                                        ->helperText('Meta tag: msvalidate.01'),
                                    TextInput::make('verification.yandex')
                                        ->nullable()
                                        ->string()
                                        ->alphaDash()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('Yandex Webmaster')
                                        ->helperText('Meta tag: yandex-verification'),
                                    TextInput::make('verification.baidu')
                                        ->nullable()
                                        ->string()
                                        ->alphaDash()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('Baidu Webmaster')
                                        ->helperText('Meta tag: baidu-site-verification'),
                                    TextInput::make('verification.naver')
                                        ->nullable()
                                        ->string()
                                        ->alphaDash()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('Naver Search Advisor')
                                        ->helperText('Meta tag: naver-site-verification'),
                                    TextInput::make('verification.seznam')
                                        ->nullable()
                                        ->string()
                                        ->alphaDash()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('Seznam Webmaster')
                                        ->helperText('Meta tag: seznam-wmt'),
                                    TextInput::make('verification.pinterest')
                                        ->nullable()
                                        ->string()
                                        ->alphaDash()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('Pinterest')
                                        ->helperText('Meta tag: p:domain_verify'),
                                    TextInput::make('verification.facebook')
                                        ->nullable()
                                        ->string()
                                        ->alphaDash()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('Facebook (Meta)')
                                        ->helperText('Meta tag: facebook-domain-verification'),
                                ]),
                            Text::make(new HtmlString(Blade::render(<<<'BLADE'
                                An XML sitemap is available for submitting to search engines at
                                <x-filament::link :href="$url" target="_blank">
                                    {{ $url }}
                                </x-filament::link>
                                BLADE,
                                ['url' => route('seo.sitemap.index')]))),
                        ]),
                ]),
            ]);
    }
}

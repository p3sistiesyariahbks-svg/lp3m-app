<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Enums\StatusType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Str;
use Kisame76\FilamentAdvancedRichEditor\Forms\Components\AdvancedRichEditor;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)
                    ->schema([
                        Group::make([
                            Section::make('Page Content')
                                ->schema([
                                    TextInput::make('title')
                                        ->required()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('Title')
                                        ->autofocus()
                                        ->live(true)
                                        ->afterStateUpdated(function (Get $get, Set $set, string $operation) {
                                            if ($operation === 'create') {
                                                $slug = Str::slug($get('title'));
                                                $set('slug', $slug);
                                            }
                                        }),
                                    AdvancedRichEditor::make('content')
                                        ->required()
                                        ->minLength(3)
                                        ->maxLength(65535)
                                        ->toolbarAlignment(Alignment::Left)
                                        ->mediaLibrary(false)
                                        ->headingLevels([1, 2, 3, 4, 5, 6])
                                        ->fileAttachmentsMaxSize(2048)
                                        ->resizableImages()
                                        ->helperText('Use the toolbar to format text, add headings, lists, links, and images.'),
                                ]),
                            Section::make('Advanced Settings')
                                ->schema([
                                    TextInput::make('slug')
                                        ->required()
                                        ->alphaDash()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->validationAttribute('url slug')
                                        ->label('URL Slug')
                                        ->prefix('/pages/')
                                        ->helperText('Use only lowercase letters, numbers, and hyphens.'),
                                    Group::make([
                                        Text::make('SEO Settings'),
                                        Group::make([
                                            TextInput::make('seo_title')
                                                ->dehydrated(false)
                                                ->nullable()
                                                ->string()
                                                ->minLength(3)
                                                ->maxLength(100)
                                                ->label('SEO Title')
                                                ->helperText('Leave empty to use page title.'),
                                            Textarea::make('meta_description')
                                                ->dehydrated(false)
                                                ->nullable()
                                                ->string()
                                                ->minLength(3)
                                                ->maxLength(255)
                                                ->label('Meta Description')
                                                ->autosize()
                                                ->helperText('Recommended: 150-160 characters.'),
                                        ])
                                            ->dense(false),
                                    ])
                                        ->dense(),
                                ])
                                ->collapsed(),
                        ])
                            ->columnSpan(2),
                        Group::make([
                            Section::make('Publishing')
                                ->schema([
                                    Select::make('status')
                                        ->required()
                                        ->string()
                                        ->enum(StatusType::class)
                                        ->options(StatusType::class)
                                        ->native(false)
                                        ->default(StatusType::Draft)
                                        ->selectablePlaceholder(false)
                                        ->live(),
                                    FusedGroup::make([
                                        DatePicker::make('published_date')
                                            ->requiredIf('status', StatusType::Scheduled)
                                            ->afterOrEqual('today')
                                            ->hiddenLabel()
                                            ->native(false)
                                            ->default(now()->addDay())
                                            ->minDate(now()),
                                        TimePicker::make('published_time')
                                            ->requiredIf('status', StatusType::Scheduled)
                                            ->hiddenLabel()
                                            ->seconds(false)
                                            ->default('09:00'),
                                    ])
                                        ->label('Publish Date & Time')
                                        ->columns(2)
                                        ->helperText('The page will be published automatically at this date and time')
                                        ->visible(fn (Get $get): bool => $get('status') === StatusType::Scheduled),
                                ]),
                            Section::make('Featured Image')
                                ->schema([
                                    SpatieMediaLibraryFileUpload::make('thumbnail')
                                        ->nullable()
                                        ->image()
                                        ->maxSize(1024)
                                        ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                        ->hiddenLabel()
                                        ->imageEditor()
                                        ->collection('thumbnails')
                                        ->disk('public')
                                        ->visibility('public')
                                        ->downloadable()
                                        ->helperText('This image appears in page previews and social media shares.'),
                                ]),
                        ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}

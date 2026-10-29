<?php

namespace App\Filament\Resources\GalleryPhotos\Schemas;

use App\Enums\StatusType;
use App\Enums\TaxonomyType;
use App\Models\Taxonomy;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class GalleryPhotoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)
                    ->schema([
                        Group::make([
                            Section::make('Photo Content')
                                ->schema([
                                    TextInput::make('title')
                                        ->required()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('Title')
                                        ->autofocus()
                                        ->live(true)
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            $slug = Str::slug($get('title'));
                                            $set('slug', $slug);
                                        }),
                                    Repeater::make('images')
                                        ->relationship('photos')
                                        ->orderColumn('sort_order')
                                        ->schema([
                                            SpatieMediaLibraryFileUpload::make('src')
                                                ->nullable()
                                                ->image()
                                                ->maxSize(1024)
                                                ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                                ->hiddenLabel()
                                                ->imageEditor()
                                                ->collection('images')
                                                ->multiple(false)
                                                ->disk('public')
                                                ->visibility('public'),
                                            TextInput::make('alt')
                                                ->nullable()
                                                ->string()
                                                ->minLength(3)
                                                ->maxLength(100)
                                                ->label('Image Alt')
                                                ->live(onBlur: true),
                                        ])
                                        ->label('Images')
                                        ->defaultItems(1)
                                        ->addActionLabel('Add image')
                                        ->reorderableWithButtons()
                                        ->collapsible()
                                        ->itemLabel(fn (array $state): ?string => $state['alt'] ?? null)
                                        ->minItems(1)
                                        ->maxItems(10),
                                    Textarea::make('description')
                                        ->nullable()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(65535)
                                        ->label('Description')
                                        ->autosize(),
                                ]),
                            Section::make('Advanced Settings')
                                ->schema([
                                    TextInput::make('slug')
                                        ->required()
                                        ->alphaDash()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->unique(ignoreRecord: true)
                                        ->validationAttribute('url slug')
                                        ->label('URL Slug')
                                        ->prefix('/gallery/photos/')
                                        ->belowContent('Use only lowercase letters, numbers, and hyphens.'),
                                    Group::make([
                                        Text::make('SEO Settings'),
                                        Group::make([
                                            TextInput::make('seo_title')
                                                ->nullable()
                                                ->string()
                                                ->minLength(3)
                                                ->maxLength(100)
                                                ->label('SEO Title')
                                                ->belowContent('Leave empty to use post title.'),
                                            Textarea::make('meta_description')
                                                ->nullable()
                                                ->string()
                                                ->minLength(3)
                                                ->maxLength(255)
                                                ->label('Meta Description')
                                                ->autosize()
                                                ->belowContent('Recommended: 150-160 characters.'),
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
                                        ->belowContent('The news will be published automatically at this date and time')
                                        ->visible(fn (Get $get): bool => $get('status') === StatusType::Scheduled),
                                ]),
                            Section::make('Categories')
                                ->schema([
                                    Select::make('categories')
                                        ->required()
                                        ->label('Name')
                                        ->relationship('categories', 'name')
                                        ->getOptionLabelFromRecordUsing(fn (Taxonomy $record): string => str_repeat('—', $record->depth)." {$record->name}")
                                        ->createOptionModalHeading('Category')
                                        ->createOptionForm([
                                            TextInput::make('name')
                                                ->required()
                                                ->string()
                                                ->minLength(3)
                                                ->maxLength(100)
                                                ->label('Name'),
                                            Select::make('parent_id')
                                                ->label('Parent Category')
                                                ->relationship(
                                                    'parent',
                                                    'name',
                                                    modifyQueryUsing: fn (Builder $query) => $query->where('type', TaxonomyType::Gallery),
                                                )
                                                ->getOptionLabelFromRecordUsing(fn (Taxonomy $record): string => str_repeat('—', $record->depth)." {$record->name}")
                                                ->native(false)
                                                ->preload(),
                                            Textarea::make('description')
                                                ->string()
                                                ->minLength(3)
                                                ->maxLength(1024)
                                                ->label('Description')
                                                ->autosize(),
                                        ])
                                        ->createOptionUsing(function (array $data): string {
                                            return Taxonomy::create([
                                                'name' => $data['name'],
                                                'parent_id' => $data['parent_id'],
                                                'description' => $data['description'],
                                                'type' => TaxonomyType::Gallery,
                                            ])->getKey();
                                        })
                                        ->multiple()
                                        ->native(false)
                                        ->preload(),
                                ]),
                        ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}

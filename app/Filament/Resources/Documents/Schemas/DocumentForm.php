<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Enums\StatusType;
use App\Enums\TaxonomyType;
use App\Models\Taxonomy;
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
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)
                    ->schema([
                        Group::make([
                            Section::make('Upload Document')
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
                                    Tabs::make('Tabs')
                                        ->tabs([
                                            Tab::make('Upload File')
                                                ->schema([
                                                    SpatieMediaLibraryFileUpload::make('file_path')
                                                        ->requiredWithout('file_url')
                                                        ->prohibits('file_url')
                                                        ->markAsRequired()
                                                        ->maxSize(5120)
                                                        ->acceptedFileTypes([
                                                            'application/pdf',
                                                            'application/msword',
                                                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                                            'application/vnd.ms-excel',
                                                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                                            'application/vnd.ms-powerpoint',
                                                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                                        ])
                                                        ->hiddenLabel()
                                                        ->imageEditor()
                                                        ->collection('documents')
                                                        ->multiple(false)
                                                        ->disk('public')
                                                        ->visibility('public')
                                                        ->downloadable()
                                                        ->helperText('Accepted File Types: pdf, doc, xls and ppt only.'),
                                                ]),
                                            Tab::make('From URL')
                                                ->schema([
                                                    TextInput::make('file_url')
                                                        ->requiredWithout('file_path')
                                                        ->prohibits('file_path')
                                                        ->markAsRequired()
                                                        ->activeUrl()
                                                        ->validationAttribute('file url')
                                                        ->label('File URL')
                                                        ->placeholder('https://example.com')
                                                        ->helperText('Make sure the URL is publicly accessible.'),
                                                ]),
                                        ])
                                        ->persistTab()
                                        ->id('file-tabs'),
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
                                        ->prefix('/documents/')
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
                                                ->helperText('Leave empty to use document title.'),
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
                                        ->helperText('The document will be published automatically at this date and time')
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
                                        ->multiple(false)
                                        ->disk('public')
                                        ->visibility('public')
                                        ->downloadable()
                                        ->helperText('This image appears in document previews and social media shares.'),
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
                                                    modifyQueryUsing: fn (Builder $query) => $query->where('type', TaxonomyType::Documents),
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
                                                'type' => TaxonomyType::Documents,
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

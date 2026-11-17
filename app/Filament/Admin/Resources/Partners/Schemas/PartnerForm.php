<?php

namespace App\Filament\Admin\Resources\Partners\Schemas;

use App\Enums\StatusType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)
                    ->schema([
                        Group::make([
                            Section::make('Partner Information')
                                ->schema([
                                    TextInput::make('name')
                                        ->required()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('Name')
                                        ->autofocus(),
                                    SpatieMediaLibraryFileUpload::make('logo')
                                        ->required()
                                        ->image()
                                        ->maxSize(1024)
                                        ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                        ->label('Logo')
                                        ->imageEditor()
                                        ->collection('logos')
                                        ->multiple(false)
                                        ->disk('public')
                                        ->visibility('public')
                                        ->downloadable()
                                        ->helperText('This image appears in the partners section.'),
                                    TextInput::make('url')
                                        ->required()
                                        ->activeUrl()
                                        ->label('URL')
                                        ->placeholder('https://example.com')
                                        ->helperText('Make sure the URL is publicly accessible.'),
                                ]),
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
                                            ->minDate(now())
                                            ->dehydrated(false),
                                        TimePicker::make('published_time')
                                            ->requiredIf('status', StatusType::Scheduled)
                                            ->hiddenLabel()
                                            ->seconds(false)
                                            ->default('09:00')
                                            ->dehydrated(false),
                                    ])
                                        ->label('Publish Date & Time')
                                        ->columns(2)
                                        ->helperText('The partner will be published automatically at this date and time')
                                        ->visible(fn (Get $get): bool => $get('status') === StatusType::Scheduled),
                                ]),
                        ]),
                    ]),
            ]);
    }
}

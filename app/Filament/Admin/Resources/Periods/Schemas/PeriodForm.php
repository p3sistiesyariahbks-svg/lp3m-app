<?php

namespace App\Filament\Admin\Resources\Periods\Schemas;

use App\Enums\ProgramType;
use App\Enums\StatusType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PeriodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)
                    ->schema([
                        Group::make([
                            Section::make('Period Information')
                                ->schema([
                                    TextInput::make('name')
                                        ->required()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('Name')
                                        ->autofocus(),
                                    Radio::make('program')
                                        ->required()
                                        ->string()
                                        ->enum(ProgramType::class)
                                        ->options(ProgramType::class)
                                        ->default(ProgramType::KKN)
                                        ->inline(),
                                    Group::make([
                                        Text::make('Program Date'),
                                        Grid::make(2)
                                            ->schema([
                                                DatePicker::make('program_starts_at')
                                                    ->required()
                                                    ->afterOrEqual('registration_closes_at')
                                                    ->native(false)
                                                    ->default(now()->addMonths(3))
                                                    ->minDate(now())
                                                    ->validationAttribute('start date')
                                                    ->label('Start Date'),
                                                DatePicker::make('program_ends_at')
                                                    ->required()
                                                    ->afterOrEqual('program_starts_at')
                                                    ->native(false)
                                                    ->default(now()->addMonths(6))
                                                    ->minDate(now())
                                                    ->validationAttribute('end date')
                                                    ->label('End Date'),
                                            ]),
                                    ])
                                        ->dense(),
                                    Group::make([
                                        Text::make('Registration Date'),
                                        Grid::make(2)
                                            ->schema([
                                                DatePicker::make('registration_opens_at')
                                                    ->required()
                                                    ->afterOrEqual('today')
                                                    ->native(false)
                                                    ->default(now()->addDay())
                                                    ->minDate(now())
                                                    ->validationAttribute('open at')
                                                    ->label('Open At'),
                                                DatePicker::make('registration_closes_at')
                                                    ->required()
                                                    ->afterOrEqual('registration_opens_at')
                                                    ->native(false)
                                                    ->default(now()->addDay())
                                                    ->minDate(now())
                                                    ->validationAttribute('close at')
                                                    ->label('Close At'),
                                            ]),
                                    ])
                                        ->dense(),
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('fee')
                                                ->required()
                                                ->numeric()
                                                ->minValue(1_000)
                                                ->maxValue(10_000_000)
                                                ->label('Fee')
                                                ->prefix('Rp.')
                                                ->helperText('Fee for registering in this period.'),
                                        ]),
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
                                        ->helperText('The announcement will be published automatically at this date and time')
                                        ->visible(fn (Get $get): bool => $get('status') === StatusType::Scheduled),
                                ]),
                        ]),
                    ]),
            ]);
    }
}

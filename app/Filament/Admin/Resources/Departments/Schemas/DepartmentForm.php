<?php

namespace App\Filament\Admin\Resources\Departments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)
                    ->schema([
                        Group::make([
                            Section::make('Department Information')
                                ->schema([
                                    TextInput::make('name')
                                        ->required()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('Name')
                                        ->autofocus()
                                        ->live(true)
                                        ->afterStateUpdated(function (Get $get, Set $set, string $operation) {
                                            if ($operation === 'create') {
                                                $code = Str::of($get('name'))
                                                    ->acronym()
                                                    ->upper()
                                                    ->toString();
                                                $set('code', $code);
                                            }
                                        }),
                                    TextInput::make('code')
                                        ->required()
                                        ->string()
                                        ->maxLength(100)
                                        ->unique(ignoreRecord: true)
                                        ->label('Code'),
                                ]),
                        ])
                            ->columnSpan(2),
                        Group::make([
                            Section::make('Status')
                                ->schema([
                                    Toggle::make('is_active')
                                        ->required()
                                        ->label('Visibility')
                                        ->helperText('Controls whether this product appears in sales channels.')
                                        ->inline(),
                                ]),
                        ]),
                    ]),
            ]);
    }
}

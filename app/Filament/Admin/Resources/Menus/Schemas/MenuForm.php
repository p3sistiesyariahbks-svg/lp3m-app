<?php

namespace App\Filament\Admin\Resources\Menus\Schemas;

use Datlechin\FilamentMenuBuilder\Resources\MenuResource as BaseMenuResource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return BaseMenuResource::form($schema)
            ->columns(1)
            ->components([
                Section::make('Menu Details')
                    ->columns(1)
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->string()
                                    ->minLength(3)
                                    ->maxLength(100)
                                    ->label('Name')
                                    ->autofocus()
                                    ->columnSpan(3),
                                ToggleButtons::make('is_visible')
                                    ->required()
                                    ->label('Visibility')
                                    ->boolean(trueLabel: 'Visible', falseLabel: 'Hidden')
                                    ->colors([1 => 'primary', 0 => 'danger'])
                                    ->grouped()
                                    ->default(true),
                            ]),
                    ]),
            ]);
    }
}

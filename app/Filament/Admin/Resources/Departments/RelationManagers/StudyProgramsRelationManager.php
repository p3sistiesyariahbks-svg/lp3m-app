<?php

namespace App\Filament\Admin\Resources\Departments\RelationManagers;

use App\Enums\DegreeLevel;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class StudyProgramsRelationManager extends RelationManager
{
    protected static string $relationship = 'studyPrograms';

    protected static ?string $title = 'Study Programs';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('name')
                    ->required()
                    ->string()
                    ->minLength(3)
                    ->maxLength(100)
                    ->label('Name')
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
                Grid::make(2)
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->string()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->label('Code'),
                        Select::make('degree')
                            ->required()
                            ->enum(DegreeLevel::class)
                            ->options(DegreeLevel::class)
                            ->label('Degree')
                            ->native(false),
                    ]),
                Toggle::make('is_active')
                    ->required()
                    ->label('Visibility')
                    ->helperText('Controls whether this product appears in sales channels.')
                    ->inline(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->words(10)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('degree')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Visibility')
                    ->sortable()
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->color('gray')
                        ->hiddenLabel(),
                    DeleteAction::make()
                        ->color('gray')
                        ->hiddenLabel(),
                ])
                    ->buttonGroup(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->queryStringIdentifier('study-programs');
    }
}

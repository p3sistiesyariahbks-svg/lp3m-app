<?php

namespace App\Filament\Admin\Resources\Periods\RelationManagers;

use App\Models\Placement;
use Devletes\FilamentProgressBar\Tables\Columns\ProgressBarColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PlacementsRelationManager extends RelationManager
{
    protected static string $relationship = 'placements';

    protected static ?string $title = 'Placements';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Select::make('location_id')
                    ->required()
                    ->label('Location')
                    ->relationship(
                        'location',
                        'name',
                        modifyQueryUsing: fn (Builder $query) => $query->where('is_active', true),
                    )
                    ->native(false)
                    ->searchable()
                    ->preload(),
                Select::make('supervisor_id')
                    ->required()
                    ->label('Supervisor')
                    ->relationship('supervisor', 'name')
                    ->native(false)
                    ->preload(),
                Grid::make(2)
                    ->schema([
                        TextInput::make('quota')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->label('Quota')
                            ->suffix('People')
                            ->helperText('Number of available slots (1-100).'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('location.name')
                    ->label('Location')
                    ->description(fn (Placement $record): ?string => $record->location?->address)
                    ->words(10)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location.level')
                    ->label('Level')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('supervisor.name')
                    ->label('Supervisor')
                    ->searchable()
                    ->sortable(),
                ProgressBarColumn::make('filled')
                    ->state(fn (Placement $record) => [
                        'current' => $record->filled,
                        'total' => $record->quota,
                    ])
                    ->showProgressValue()
                    ->hidePercentage()
                    ->height('12px')
                    ->textPosition('inside')
                    ->thresholdDirection('ascending')
                    ->warningThreshold(50)
                    ->dangerThreshold(100),
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
            ->queryStringIdentifier('placements');
    }
}

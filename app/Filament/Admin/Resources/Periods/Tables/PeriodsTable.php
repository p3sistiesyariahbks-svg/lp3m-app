<?php

namespace App\Filament\Admin\Resources\Periods\Tables;

use App\Models\Period;
use Carbon\CarbonInterface;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PeriodsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->words(10)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('program')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('placements_count')
                    ->label('Locations')
                    ->counts('placements')
                    ->numeric()
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make('program_starts_at')
                    ->label('Program Date')
                    ->formatStateUsing(fn (CarbonInterface $state, Period $record): string => "{$state->toDateString()} – {$record->program_ends_at->toDateString()}"
                    )
                    ->sortable(),
                TextColumn::make('registration_opens_at')
                    ->label('Registration Date')
                    ->formatStateUsing(fn (CarbonInterface $state, Period $record): string => "{$state->toDateString()} – {$record->registration_closes_at->toDateString()}"
                    )
                    ->sortable(),
                TextColumn::make('fee')
                    ->label('Fee')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->default(0)
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->placeholder('Unpublished')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('creator.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('editor.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->since()
                    ->isoDateTimeTooltip()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->since()
                    ->isoDateTimeTooltip()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            ->queryStringIdentifier('periods');
    }
}

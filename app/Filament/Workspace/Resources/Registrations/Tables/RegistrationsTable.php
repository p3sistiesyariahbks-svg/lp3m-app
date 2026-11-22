<?php

namespace App\Filament\Workspace\Resources\Registrations\Tables;

use App\Models\Registration;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('period.name')
                    ->label('Period')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('period.program')
                    ->label('Program')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('placement.location.name')
                    ->label('Location')
                    ->placeholder('Unassigned')
                    ->description(fn (Registration $record): ?string => $record->placement?->location?->address)
                    ->words(10)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('submitted_at')
                    ->label('Submitted At')
                    ->dateTime()
                    ->placeholder('Not submitted')
                    ->sortable(),
                TextColumn::make('note')
                    ->label('Note')
                    ->placeholder('-')
                    ->wrap(),
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
                //
            ])
            ->queryStringIdentifier('registrations');
    }
}

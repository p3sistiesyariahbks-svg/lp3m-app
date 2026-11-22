<?php

namespace App\Filament\Admin\Resources\Registrations\Tables;

use App\Filament\Admin\Resources\Registrations\Actions\ReviewRegistrationAction;
use App\Models\Registration;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Student')
                    ->description(fn (Registration $record): string => $record->user?->email)
                    ->words(10)
                    ->searchable()
                    ->sortable(),
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
                    ViewAction::make()
                        ->color('gray')
                        ->hiddenLabel(),
                    ReviewRegistrationAction::make()
                        ->color('gray')
                        ->hiddenLabel()
                        ->icon(Heroicon::OutlinedCheckCircle),
                ])
                    ->buttonGroup(),
            ])
            ->toolbarActions([
                //
            ])
            ->queryStringIdentifier('registrations');
    }
}

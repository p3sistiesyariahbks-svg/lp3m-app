<?php

namespace App\Filament\Admin\Resources\Registrations\Tables;

use App\Filament\Admin\Resources\Registrations\Actions\ConfirmPaymentAction;
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
                TextColumn::make('latestPayment.status')
                    ->label('Payment Status')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->since()
                    ->isoDateTimeTooltip()
                    ->sortable(),
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
                    ConfirmPaymentAction::make()
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

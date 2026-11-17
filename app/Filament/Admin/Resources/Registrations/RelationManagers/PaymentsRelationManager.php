<?php

namespace App\Filament\Admin\Resources\Registrations\RelationManagers;

use App\Models\Payment;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;
use Livewire\Attributes\On;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Payment History';

    #[On('payment-updated')]
    public function refreshPayments(): void
    {
        $this->resetTable();
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('amount')
                    ->label('Paid')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('verifier.name')
                    ->label('Verified By')
                    ->placeholder('Unverified')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('verified_at')
                    ->label('Verified At')
                    ->dateTime()
                    ->placeholder('Unverified')
                    ->sortable(),
                TextColumn::make('note')
                    ->label('Notes')
                    ->placeholder('No notes')
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
                MediaAction::make('payment_proof')
                    ->label('View Payment Proof')
                    ->link()
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->modalHeading('Payment Proof')
                    ->modalWidth(Width::TwoExtraLarge)
                    ->media(fn (Payment $record): ?string => $record->getFirstMedia('payment-proof')
                        ?->getTemporaryUrl(now()->addMinutes(5)),
                    )
                    ->disabled(fn (Payment $record): bool => ! $record->hasMedia('payment-proof')),
            ], position: RecordActionsPosition::BeforeCells)
            ->toolbarActions([
                //
            ])
            ->queryStringIdentifier('payments');
    }
}

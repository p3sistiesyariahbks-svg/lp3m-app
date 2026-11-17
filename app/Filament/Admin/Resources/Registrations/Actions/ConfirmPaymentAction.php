<?php

namespace App\Filament\Admin\Resources\Registrations\Actions;

use App\Enums\PaymentStatus;
use App\Models\Registration;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\Width;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;
use Livewire\Component;

class ConfirmPaymentAction
{
    public static function make(): Action
    {
        return MediaAction::make('confirm')
            ->label('Confirm Payment')
            ->modalHeading('Confirm Payment')
            ->modalWidth(Width::TwoExtraLarge)
            ->media(fn (Registration $record): ?string => $record->latestPayment
                ?->getFirstMedia('payment-proof')
                ?->getTemporaryUrl(now()->addMinutes(5)),
            )
            ->schema([
                Group::make([
                    TextEntry::make('period.fee')
                        ->label('Bill')
                        ->placeholder('-')
                        ->inlineLabel()
                        ->alignEnd()
                        ->money('IDR', locale: 'id', decimalPlaces: 0)
                        ->weight(FontWeight::Bold),
                    TextEntry::make('latestPayment.amount')
                        ->label('Paid')
                        ->placeholder('-')
                        ->inlineLabel()
                        ->alignEnd()
                        ->money('IDR', locale: 'id', decimalPlaces: 0)
                        ->weight(FontWeight::Bold),
                    TextEntry::make('latestPayment.status')
                        ->label('Status')
                        ->placeholder('-')
                        ->inlineLabel()
                        ->alignEnd()
                        ->badge(),
                ])
                    ->dense(),
            ])
            ->modalSubmitAction(fn (Action $action): Action => $action->label('Verify')
                ->color('success')
            )
            ->modalCancelAction(fn (Action $action): Action => $action->label('Cancel'))
            ->action(function (Registration $record, Action $action, Component $livewire): void {
                $record->latestPayment()->update([
                    'status' => PaymentStatus::Verified,
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                ]);
                $record->load('latestPayment');

                $livewire->dispatch('payment-updated');

                $action->success();
            })
            ->successNotificationTitle('Payment verified')
            ->extraModalFooterActions([
                Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->modalHeading('Reject Payment')
                    ->modalWidth(Width::Medium)
                    ->schema([
                        Textarea::make('note')
                            ->label('Reason')
                            ->required()
                            ->string()
                            ->minLength(3)
                            ->maxLength(1024)
                            ->autosize(),
                    ])
                    ->action(function (Registration $record, array $data, Action $action, Component $livewire): void {
                        $record->latestPayment()->update([
                            'status' => PaymentStatus::Rejected,
                            'verified_by' => auth()->id(),
                            'verified_at' => now(),
                            'note' => $data['note'],
                        ]);
                        $record->load('latestPayment');

                        $livewire->dispatch('payment-updated');

                        $action->success();
                    })
                    ->successNotificationTitle('Payment rejected')
                    ->cancelParentActions(),
            ]);
    }
}

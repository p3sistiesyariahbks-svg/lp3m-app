<?php

namespace App\Filament\Admin\Resources\Registrations\Actions;

use App\Enums\RegistrationStatus;
use App\Models\Registration;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class ReviewRegistrationAction
{
    public static function make(): Action
    {
        return Action::make('review')
            ->label('Review Registration')
            ->requiresConfirmation()
            ->modalIcon(Heroicon::OutlinedClipboardDocumentCheck)
            ->modalHeading('Review Registration')
            ->modalDescription(fn (Registration $record): string => "Are you sure you want to approve the registration of {$record->user->name} for {$record->period->name}?")
            ->disabled(fn (Registration $record): bool => $record->status !== RegistrationStatus::Submitted)
            ->modalSubmitAction(fn (Action $action): Action => $action->label('Approve')
                ->color('success')
            )
            ->modalCancelAction(fn (Action $action): Action => $action->label('Cancel'))
            ->action(function (Registration $record, Action $action): void {
                $record->update([
                    'status' => RegistrationStatus::Approved,
                    'note' => null,
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                ]);

                $action->success();
            })
            ->successNotificationTitle('Registration approved')
            ->extraModalFooterActions([
                Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->modalHeading('Reject Registration')
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
                    ->action(function (Registration $record, array $data, Action $action): void {
                        $record->update([
                            'status' => RegistrationStatus::Rejected,
                            'note' => $data['note'],
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                        ]);

                        $action->success();
                    })
                    ->successNotificationTitle('Registration rejected')
                    ->cancelParentActions(),
            ]);
    }
}

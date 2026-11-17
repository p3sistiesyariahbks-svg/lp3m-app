<?php

namespace App\Filament\Workspace\Resources\Placements\Actions;

use App\Filament\Workspace\Resources\Registrations\RegistrationResource;
use App\Models\Placement;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

class RegisterPlacementAction
{
    public static function make(): Action
    {
        return Action::make('register')
            ->label('Register')
            ->icon(Heroicon::OutlinedPencilSquare)
            ->color('primary')
            ->button()
            ->requiresConfirmation()
            ->modalHeading('Confirm Registration')
            ->modalDescription(fn (Placement $record): string => "Are you sure you want to register for {$record->location->name}?")
            ->modalSubmitActionLabel('Confirm')
            ->modalCancelActionLabel('Cancel')
            ->databaseTransaction()
            ->action(function (Placement $record, Action $action): void {
                $record->registrations()->create([
                    'user_id' => auth()->id(),
                    'period_id' => $record->period_id,
                ]);
                $record->increment('filled');

                $action->success();
            })
            ->successNotificationTitle('Registration created')
            ->successRedirectUrl(fn (): string => RegistrationResource::getUrl('index'));
    }
}

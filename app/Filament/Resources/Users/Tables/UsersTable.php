<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use STS\FilamentImpersonate\Actions\Impersonate;
use Waguilar\FilamentGuardian\Facades\Guardian;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('avatar')
                    ->alt(fn (User $record): string => $record->name)
                    ->imageHeight(40)
                    ->circular()
                    ->collection('avatars'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email Address')
                    ->copyable()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone_number')
                    ->label('Phone Number')
                    ->placeholder('Not Provided')
                    ->copyable()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->separator(',')
                    ->limitList(2)
                    ->sortable(),
                IconColumn::make('email_verified_at')
                    ->label('Is Verified')
                    ->state(fn (User $record): bool => filled($record->email_verified_at))
                    ->boolean()
                    ->sortable()
                    ->alignCenter(),
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
                TernaryFilter::make('email_verified_at')
                    ->label('Is Verified')
                    ->nullable()
                    ->native(false),
                TernaryFilter::make('banned_at')
                    ->label('Is Banned')
                    ->nullable()
                    ->native(false),
            ])
            ->recordActions([
                ActionGroup::make([
                    Impersonate::make()
                        ->color('gray')
                        ->hiddenLabel()
                        ->hidden(fn (User $record): bool => Guardian::userIsSuperAdmin($record)),
                    Action::make('ban')
                        ->icon(Heroicon::NoSymbol)
                        ->color('gray')
                        ->hiddenLabel()
                        ->requiresConfirmation()
                        ->modalIcon(Heroicon::OutlinedNoSymbol)
                        ->modalHeading(fn (Action $action): string => "Ban {$action->getRecordTitle()}")
                        ->action(fn (User $record) => $record->ban())
                        ->successNotificationTitle('Banned')
                        ->hidden(
                            fn (User $record): bool => $record->isBanned() || Guardian::userIsSuperAdmin($record),
                        ),
                    Action::make('unban')
                        ->icon(Heroicon::CheckCircle)
                        ->color('gray')
                        ->hiddenLabel()
                        ->requiresConfirmation()
                        ->modalIcon(Heroicon::OutlinedCheckCircle)
                        ->modalHeading(fn (Action $action): string => "Unban {$action->getRecordTitle()}")
                        ->action(fn (User $record) => $record->unban())
                        ->successNotificationTitle('Unbanned')
                        ->hidden(
                            fn (User $record): bool => $record->isNotBanned() || Guardian::userIsSuperAdmin($record),
                        ),
                    EditAction::make()
                        ->color('gray')
                        ->hiddenLabel()
                        ->hidden(fn (User $record): bool => Guardian::userIsSuperAdmin($record)),
                    DeleteAction::make()
                        ->color('gray')
                        ->hiddenLabel()
                        ->hidden(fn (User $record): bool => Guardian::userIsSuperAdmin($record)),
                ])
                    ->buttonGroup(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('ban')
                        ->icon(Heroicon::NoSymbol)
                        ->color('primary')
                        ->label('Ban selected')
                        ->requiresConfirmation()
                        ->modalIcon(Heroicon::OutlinedNoSymbol)
                        ->modalHeading(fn (BulkAction $action): string => "Ban selected {$action->getTitleCasePluralModelLabel()}")
                        ->action(fn (Collection $records) => $records->each->ban())
                        ->successNotificationTitle('Banned'),
                    BulkAction::make('unban')
                        ->icon(Heroicon::CheckCircle)
                        ->color('primary')
                        ->label('Unban selected')
                        ->requiresConfirmation()
                        ->modalIcon(Heroicon::OutlinedCheckCircle)
                        ->modalHeading(fn (BulkAction $action): string => "Unban selected {$action->getTitleCasePluralModelLabel()}")
                        ->action(fn (Collection $records) => $records->each->unban())
                        ->successNotificationTitle('Unbanned'),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn (User $record): bool => ! Guardian::userIsSuperAdmin($record),
            )
            ->queryStringIdentifier('users');
    }
}

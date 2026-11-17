<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Roles\Tables;

use App\Models\Role;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Waguilar\FilamentGuardian\Base\Roles\Tables\BaseRolesTable;
use Waguilar\FilamentGuardian\Facades\Guardian;

class RolesTable extends BaseRolesTable
{
    public static function configure(Table $table): Table
    {
        return parent::configure($table)
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->grow(),
                TextColumn::make('users_count')
                    ->label('Users')
                    ->counts('users')
                    ->numeric()
                    ->default(0)
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make('permissions_count')
                    ->label('Permissions')
                    ->counts('permissions')
                    ->numeric()
                    ->default(0)
                    ->sortable()
                    ->alignEnd(),
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
                        ->hiddenLabel()
                        ->hidden(fn (Role $record): bool => Guardian::isSuperAdminRole($record)),
                    DeleteAction::make()
                        ->color('gray')
                        ->hiddenLabel()
                        ->hidden(fn (Role $record): bool => Guardian::isSuperAdminRole($record)),
                ])
                    ->buttonGroup(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->queryStringIdentifier('roles');
    }
}

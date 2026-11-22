<?php

namespace App\Filament\Workspace\Resources\Registrations;

use App\Filament\Workspace\Resources\Registrations\Pages\EditRegistration;
use App\Filament\Workspace\Resources\Registrations\Pages\ListRegistrations;
use App\Filament\Workspace\Resources\Registrations\Schemas\RegistrationForm;
use App\Filament\Workspace\Resources\Registrations\Tables\RegistrationsTable;
use App\Models\Registration;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return RegistrationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RegistrationsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id())
            ->with([
                'period',
                'placement.location',
                'placement.supervisor',
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRegistrations::route('/'),
            'edit' => EditRegistration::route('/{record}/edit'),
        ];
    }
}

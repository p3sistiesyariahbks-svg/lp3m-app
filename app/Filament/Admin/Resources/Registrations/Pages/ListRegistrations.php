<?php

namespace App\Filament\Admin\Resources\Registrations\Pages;

use App\Enums\RegistrationStatus;
use App\Filament\Admin\Resources\Registrations\RegistrationResource;
use App\Models\Registration;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListRegistrations extends ListRecords
{
    protected static string $resource = RegistrationResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(
                    fn (): int => Registration::query()
                        ->count()
                )
                ->lazyLoadedBadge(),
            'draft' => Tab::make('Draft')
                ->badge(
                    fn (): int => Registration::query()
                        ->where('status', RegistrationStatus::Draft)
                        ->count()
                )
                ->lazyLoadedBadge()
                ->modifyQueryUsing(
                    fn (Builder $query): Builder => $query->where('status', RegistrationStatus::Draft)
                ),
            'submitted' => Tab::make('Submitted')
                ->badge(
                    fn (): int => Registration::query()
                        ->where('status', RegistrationStatus::Submitted)
                        ->count()
                )
                ->lazyLoadedBadge()
                ->modifyQueryUsing(
                    fn (Builder $query): Builder => $query->where('status', RegistrationStatus::Submitted)
                ),
            'approved' => Tab::make('Approved')
                ->badge(
                    fn (): int => Registration::query()
                        ->where('status', RegistrationStatus::Approved)
                        ->count()
                )
                ->lazyLoadedBadge()
                ->modifyQueryUsing(
                    fn (Builder $query): Builder => $query->where('status', RegistrationStatus::Approved)
                ),
            'rejected' => Tab::make('Rejected')
                ->badge(
                    fn (): int => Registration::query()
                        ->where('status', RegistrationStatus::Rejected)
                        ->count()
                )
                ->lazyLoadedBadge()
                ->modifyQueryUsing(
                    fn (Builder $query): Builder => $query->where('status', RegistrationStatus::Rejected)
                ),
        ];
    }
}

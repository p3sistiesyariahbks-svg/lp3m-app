<?php

namespace App\Filament\Admin\Resources\Registrations\Pages;

use App\Enums\PaymentStatus;
use App\Filament\Admin\Resources\Registrations\RegistrationResource;
use App\Filament\Admin\Resources\Registrations\Widgets\RegistrationStatsOverview;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListRegistrations extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = RegistrationResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(
                    fn (Builder $query): Builder => $query->whereHas(
                        'latestPayment',
                        fn (Builder $query): Builder => $query->where('status', PaymentStatus::Pending)
                    )
                ),
            'verified' => Tab::make('Verified')
                ->modifyQueryUsing(
                    fn (Builder $query): Builder => $query->whereHas(
                        'latestPayment',
                        fn (Builder $query): Builder => $query->where('status', PaymentStatus::Verified)
                    )
                ),
            'rejected' => Tab::make('Rejected')
                ->modifyQueryUsing(
                    fn (Builder $query): Builder => $query->whereHas(
                        'latestPayment',
                        fn (Builder $query): Builder => $query->where('status', PaymentStatus::Rejected)
                    )
                ),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RegistrationStatsOverview::class,
        ];
    }
}

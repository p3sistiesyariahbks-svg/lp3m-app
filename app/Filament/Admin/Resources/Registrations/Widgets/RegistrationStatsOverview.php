<?php

namespace App\Filament\Admin\Resources\Registrations\Widgets;

use App\Enums\PaymentStatus;
use App\Filament\Admin\Resources\Registrations\Pages\ListRegistrations;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class RegistrationStatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListRegistrations::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total Registrations',
                $this->getPageTableQuery()
                    ->count()
            )
                ->icon(Heroicon::OutlinedClipboardDocumentList)
                ->description('All registrations'),
            Stat::make(
                'Pending',
                $this->getPageTableQuery()
                    ->whereHas(
                        'latestPayment',
                        fn (Builder $query): Builder => $query->where('status', PaymentStatus::Pending)
                    )
                    ->count()
            )
                ->icon(Heroicon::OutlinedClock)
                ->description('Awaiting verification'),
            Stat::make(
                'Verified',
                $this->getPageTableQuery()
                    ->whereHas(
                        'latestPayment',
                        fn (Builder $query): Builder => $query->where('status', PaymentStatus::Verified)
                    )
                    ->count()
            )
                ->icon(Heroicon::OutlinedCheckCircle)
                ->description('Verified payments'),
            Stat::make(
                'Rejected',
                $this->getPageTableQuery()
                    ->whereHas(
                        'latestPayment',
                        fn (Builder $query): Builder => $query->where('status', PaymentStatus::Rejected)
                    )
                    ->count()
            )
                ->icon(Heroicon::OutlinedXCircle)
                ->description('Rejected payments'),
        ];
    }
}

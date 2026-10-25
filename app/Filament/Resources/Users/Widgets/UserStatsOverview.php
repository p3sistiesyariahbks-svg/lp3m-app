<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Filament\Resources\Users\Pages\ListUsers;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Waguilar\FilamentGuardian\Facades\Guardian;

class UserStatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListUsers::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total Users',
                $this->getPageTableQuery()
                    ->count()
            )
                ->icon(Heroicon::OutlinedUsers)
                ->description('Registered users'),
            Stat::make(
                'Active Users',
                $this->getPageTableQuery()
                    ->withoutBanned()
                    ->count()
            )
                ->icon(Heroicon::OutlinedUserPlus)
                ->description('Currently active'),
            Stat::make(
                'Banned Users',
                $this->getPageTableQuery()
                    ->onlyBanned()
                    ->count()
            )
                ->icon(Heroicon::OutlinedUserMinus)
                ->description('Suspended accounts'),
            Stat::make(
                'Admin Users',
                $this->getPageTableQuery()
                    ->role(Guardian::getSuperAdminRoleName())
                    ->count()
            )
                ->icon(Heroicon::OutlinedShieldCheck)
                ->description('Administrators'),
        ];
    }
}

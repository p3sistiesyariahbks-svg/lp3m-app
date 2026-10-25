<?php

namespace App\Providers;

use App\Livewire\MenuItems;
use Carbon\CarbonImmutable;
use Filament\Tables\Enums\ColumnManagerLayout;
use Filament\Tables\Enums\ColumnManagerResetActionPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\FiltersResetActionPosition;
use Filament\Tables\Table;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        JsonResource::withoutWrapping();

        Stat::configureUsing(function (Stat $component): void {
            $component->placeholder('-');
        });

        Table::configureUsing(function (Table $table): void {
            $table
                ->paginated([5, 10, 25, 50])
                ->defaultPaginationPageOption(10)
                ->extremePaginationLinks()
                ->filtersLayout(FiltersLayout::Modal)
                ->filtersResetActionPosition(FiltersResetActionPosition::Footer)
                ->columnManagerLayout(ColumnManagerLayout::Modal)
                ->columnManagerResetActionPosition(ColumnManagerResetActionPosition::Footer)
                ->reorderableColumns();
        });

        Livewire::component('menu-builder-items', MenuItems::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}

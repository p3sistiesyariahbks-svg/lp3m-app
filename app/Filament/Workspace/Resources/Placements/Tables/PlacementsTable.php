<?php

namespace App\Filament\Workspace\Resources\Placements\Tables;

use App\Filament\Workspace\Resources\Placements\Actions\RegisterPlacementAction;
use App\Models\Placement;
use Carbon\CarbonInterface;
use Devletes\FilamentProgressBar\Tables\Columns\ProgressBarColumn;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlacementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    Split::make([
                        TextColumn::make('location.name')
                            ->description(
                                fn (Placement $record): ?string => $record->location?->address
                            )
                            ->words(10)
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold)
                            ->searchable()
                            ->sortable(),
                        TextColumn::make('period.program')
                            ->badge()
                            ->searchable()
                            ->sortable()
                            ->grow(false)
                            ->alignEnd(),
                    ]),
                    Stack::make([
                        Split::make([
                            TextColumn::make('period_label')
                                ->color('gray')
                                ->state('Period')
                                ->grow(false),
                            TextColumn::make('period.name')
                                ->searchable()
                                ->sortable()
                                ->wrap()
                                ->alignEnd(),
                        ]),
                        Split::make([
                            TextColumn::make('program_date_label')
                                ->color('gray')
                                ->state('Program Date')
                                ->grow(false),
                            TextColumn::make('period.program_starts_at')
                                ->formatStateUsing(
                                    fn (CarbonInterface $state, Placement $record): string => "{$state->toDateString()} – {$record->period->program_ends_at->toDateString()}",
                                )
                                ->sortable()
                                ->wrap()
                                ->alignEnd(),
                        ]),
                        Split::make([
                            TextColumn::make('registration_date_label')
                                ->color('gray')
                                ->state('Registration Date')
                                ->grow(false),
                            TextColumn::make('period.registration_opens_at')
                                ->formatStateUsing(
                                    fn (CarbonInterface $state, Placement $record): string => "{$state->toDateString()} – {$record->period->registration_closes_at->toDateString()}",
                                )
                                ->sortable()
                                ->wrap()
                                ->alignEnd(),
                        ]),
                        Split::make([
                            TextColumn::make('supervisor_label')
                                ->color('gray')
                                ->state('Supervisor')
                                ->grow(false),
                            TextColumn::make('supervisor.name')
                                ->searchable()
                                ->sortable()
                                ->wrap()
                                ->alignEnd(),
                        ]),
                    ])
                        ->space(1),
                    ProgressBarColumn::make('filled')
                        ->state(fn (Placement $record) => [
                            'current' => $record->filled,
                            'total' => $record->quota,
                        ])
                        ->showProgressValue()
                        ->hidePercentage()
                        ->height('12px')
                        ->textPosition('inside')
                        ->thresholdDirection('ascending')
                        ->warningThreshold(50)
                        ->dangerThreshold(100),
                ])
                    ->space(3),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('View Detail')
                    ->icon(null)
                    ->color('gray')
                    ->button(),
                RegisterPlacementAction::make(),
            ])
            ->toolbarActions([
                //
            ])
            ->recordUrl(null)
            ->reorderableColumns(false)
            ->queryStringIdentifier('placements');
    }
}

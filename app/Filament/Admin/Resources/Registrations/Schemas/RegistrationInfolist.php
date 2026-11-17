<?php

namespace App\Filament\Admin\Resources\Registrations\Schemas;

use App\Models\Registration;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;

class RegistrationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)
                    ->schema([
                        Group::make([
                            Section::make('Registration Information')
                                ->schema([
                                    TextEntry::make('user.name')
                                        ->label('Student'),
                                    TextEntry::make('user.email')
                                        ->label('Email'),
                                    TextEntry::make('period.name')
                                        ->label('Period'),
                                    TextEntry::make('period.program')
                                        ->label('Program')
                                        ->badge(),
                                    TextEntry::make('placement.location.name')
                                        ->label('Location')
                                        ->placeholder('Unassigned'),
                                    TextEntry::make('placement.location.address')
                                        ->label('Address')
                                        ->placeholder('-'),
                                ])
                                ->columns(2),
                        ])
                            ->columnSpan(2),
                        Group::make([
                            Section::make('Current Payment')
                                ->schema([
                                    Group::make([
                                        TextEntry::make('period.fee')
                                            ->label('Bill')
                                            ->placeholder('-')
                                            ->inlineLabel()
                                            ->alignEnd()
                                            ->money('IDR', locale: 'id', decimalPlaces: 0)
                                            ->weight(FontWeight::Bold),
                                        TextEntry::make('latestPayment.amount')
                                            ->label('Paid')
                                            ->placeholder('-')
                                            ->inlineLabel()
                                            ->alignEnd()
                                            ->money('IDR', locale: 'id', decimalPlaces: 0)
                                            ->weight(FontWeight::Bold),
                                        TextEntry::make('latestPayment.status')
                                            ->label('Status')
                                            ->placeholder('-')
                                            ->inlineLabel()
                                            ->alignEnd()
                                            ->badge(),
                                    ])
                                        ->dense(),
                                    Actions::make([
                                        MediaAction::make('payment_proof')
                                            ->label('View Payment Proof')
                                            ->link()
                                            ->icon(Heroicon::OutlinedDocumentText)
                                            ->modalHeading('Payment Proof')
                                            ->modalWidth(Width::TwoExtraLarge)
                                            ->media(fn (Registration $record): ?string => $record->latestPayment
                                                ?->getFirstMedia('payment-proof')
                                                ?->getTemporaryUrl(now()->addMinutes(5)),
                                            )
                                            ->disabled(fn (Registration $record): bool => ! $record->latestPayment
                                                ?->hasMedia('payment-proof'),
                                            ),
                                    ]),
                                ]),
                        ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}

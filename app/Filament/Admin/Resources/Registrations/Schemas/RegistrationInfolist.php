<?php

namespace App\Filament\Admin\Resources\Registrations\Schemas;

use App\Enums\DegreeLevel;
use App\Enums\LivingWith;
use App\Models\Registration;
use Carbon\CarbonInterface;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;

class RegistrationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Overview')
                    ->schema([
                        TextEntry::make('period.name')
                            ->label('Period')
                            ->placeholder('-'),
                        TextEntry::make('period.program')
                            ->label('Program')
                            ->badge()
                            ->placeholder('-'),
                        TextEntry::make('period.registration_opens_at')
                            ->label('Registration Date')
                            ->formatStateUsing(fn (CarbonInterface $state, Registration $record): string => "{$state->toDateString()} – {$record->period->registration_closes_at->toDateString()}"
                            )
                            ->placeholder('-'),
                        TextEntry::make('period.program_starts_at')
                            ->label('Program Date')
                            ->formatStateUsing(fn (CarbonInterface $state, Registration $record): string => "{$state->toDateString()} – {$record->period->program_ends_at->toDateString()}"
                            )
                            ->placeholder('-'),
                        TextEntry::make('period.fee')
                            ->label('Fee')
                            ->money('IDR', locale: 'id', decimalPlaces: 0)
                            ->default(0),
                        TextEntry::make('user.name')
                            ->label('Student')
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->label('Registered At')
                            ->dateTime(),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge(),
                        TextEntry::make('submitted_at')
                            ->label('Submitted At')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('note')
                            ->label('Note')
                            ->placeholder('-'),
                    ])
                    ->columns(4),
                Section::make('Personal Information')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('studentProfile.nik')
                                    ->label('NIK')
                                    ->placeholder('-')
                                    ->columnSpan(1),
                                TextEntry::make('studentProfile.nim')
                                    ->label('NIM')
                                    ->placeholder('-')
                                    ->columnSpan(1),
                                TextEntry::make('studentProfile.studyProgram.degree')
                                    ->label('Study Program')
                                    ->formatStateUsing(fn (DegreeLevel $state, Registration $record): string => "{$state->getLabel()} - {$record->studentProfile->studyProgram->name}",
                                    )
                                    ->placeholder('-')
                                    ->columnSpan(1),
                            ]),
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('studentProfile.name')
                                    ->label('Full Name')
                                    ->placeholder('-')
                                    ->columnSpan(2),
                                TextEntry::make('studentProfile.gender')
                                    ->label('Gender')
                                    ->badge()
                                    ->placeholder('-')
                                    ->columnSpan(1),
                            ]),
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('studentProfile.birth_place')
                                    ->label('Place of Birth')
                                    ->placeholder('-')
                                    ->columnSpan(1),
                                TextEntry::make('studentProfile.birth_date')
                                    ->label('Date of Birth')
                                    ->date()
                                    ->placeholder('-')
                                    ->columnSpan(1),
                                TextEntry::make('studentProfile.religion')
                                    ->label('Religion')
                                    ->placeholder('-')
                                    ->columnSpan(1),
                            ]),
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('studentProfile.address')
                                    ->label('Residential Address')
                                    ->placeholder('-')
                                    ->columnSpan(1),
                                TextEntry::make('studentProfile.living_with')
                                    ->label('Living With')
                                    ->formatStateUsing(fn (LivingWith $state, Registration $record): string => $state === LivingWith::Other
                                        ? $record->studentProfile->living_with_other
                                        : $state->getLabel()
                                    )
                                    ->placeholder('-')
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->columns(1)
                    ->collapsible(),
                Section::make('Parent Information')
                    ->schema([
                        Group::make([
                            Text::make('Father Detail'),
                            Grid::make(4)
                                ->schema([
                                    TextEntry::make('father.name')
                                        ->label('Name')
                                        ->placeholder('-')
                                        ->columnSpan(1),
                                    TextEntry::make('father.birth_place')
                                        ->label('Place of Birth')
                                        ->placeholder('-')
                                        ->columnSpan(1),
                                    TextEntry::make('father.birth_date')
                                        ->label('Date of Birth')
                                        ->date()
                                        ->placeholder('-')
                                        ->columnSpan(1),
                                    TextEntry::make('father.education')
                                        ->label('Education')
                                        ->placeholder('-')
                                        ->columnSpan(1),
                                ]),
                        ])
                            ->dense(),
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('father.occupation')
                                    ->label('Occupation')
                                    ->placeholder('-')
                                    ->columnSpan(1),
                                TextEntry::make('father.address')
                                    ->label('Full Address')
                                    ->placeholder('-')
                                    ->columnSpan(1),
                            ]),
                        Group::make([
                            Text::make('Mother Detail'),
                            Grid::make(4)
                                ->schema([
                                    TextEntry::make('mother.name')
                                        ->label('Name')
                                        ->placeholder('-')
                                        ->columnSpan(1),
                                    TextEntry::make('mother.birth_place')
                                        ->label('Place of Birth')
                                        ->placeholder('-')
                                        ->columnSpan(1),
                                    TextEntry::make('mother.birth_date')
                                        ->label('Date of Birth')
                                        ->date()
                                        ->placeholder('-')
                                        ->columnSpan(1),
                                    TextEntry::make('mother.education')
                                        ->label('Education')
                                        ->placeholder('-')
                                        ->columnSpan(1),
                                ]),
                        ])
                            ->dense(),
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('mother.occupation')
                                    ->label('Occupation')
                                    ->placeholder('-')
                                    ->columnSpan(1),
                                TextEntry::make('mother.address')
                                    ->label('Full Address')
                                    ->placeholder('-')
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->columns(1)
                    ->collapsible(),
                Section::make('Contact Information')
                    ->schema([
                        TextEntry::make('user.email')
                            ->label('Email Address')
                            ->placeholder('-')
                            ->columnSpan(1),
                        TextEntry::make('user.phone_number')
                            ->label('Phone Number')
                            ->placeholder('-')
                            ->columnSpan(1),
                    ])
                    ->columns(4)
                    ->collapsible(),
                Section::make('Documents')
                    ->schema([
                        Actions::make([
                            MediaAction::make('computer_certificate')
                                ->label('View Computer Certificate')
                                ->link()
                                ->icon(Heroicon::OutlinedDocumentText)
                                ->modalHeading('Computer Certificate')
                                ->modalWidth(Width::TwoExtraLarge)
                                ->modalContent(fn (MediaAction $action): View|Htmlable => $action->getContentView())
                                ->media(fn (Registration $record): ?string => $record->getFirstMedia('computer-certificate')
                                    ?->getTemporaryUrl(now()->addMinutes(5)),
                                )
                                ->disabled(fn (Registration $record): bool => ! $record->hasMedia('computer-certificate')),
                        ])
                            ->label('Computer Certificate')
                            ->columnSpan(1),
                        Actions::make([
                            MediaAction::make('skpi_certificate')
                                ->label('View SKPI Certificate')
                                ->link()
                                ->icon(Heroicon::OutlinedDocumentText)
                                ->modalHeading('SKPI Certificate')
                                ->modalWidth(Width::TwoExtraLarge)
                                ->modalContent(fn (MediaAction $action): View|Htmlable => $action->getContentView())
                                ->media(fn (Registration $record): ?string => $record->getFirstMedia('skpi-certificate')
                                    ?->getTemporaryUrl(now()->addMinutes(5)),
                                )
                                ->disabled(fn (Registration $record): bool => ! $record->hasMedia('skpi-certificate')),
                        ])
                            ->label('SKPI Certificate')
                            ->columnSpan(1),
                        Actions::make([
                            MediaAction::make('parental_consent_letter')
                                ->label('View Parental Consent Letter')
                                ->link()
                                ->icon(Heroicon::OutlinedDocumentText)
                                ->modalHeading('Parental Consent Letter')
                                ->modalWidth(Width::TwoExtraLarge)
                                ->modalContent(fn (MediaAction $action): View|Htmlable => $action->getContentView())
                                ->media(fn (Registration $record): ?string => $record->getFirstMedia('parental-consent-letter')
                                    ?->getTemporaryUrl(now()->addMinutes(5)),
                                )
                                ->disabled(fn (Registration $record): bool => ! $record->hasMedia('parental-consent-letter')),
                        ])
                            ->label('Parental Consent Letter')
                            ->columnSpan(1),
                    ])
                    ->columns(4)
                    ->collapsible(),
                Section::make('Payment')
                    ->schema([
                        Actions::make([
                            MediaAction::make('id_card')
                                ->label('View ID Card')
                                ->link()
                                ->icon(Heroicon::OutlinedDocumentText)
                                ->modalHeading('ID Card')
                                ->modalWidth(Width::TwoExtraLarge)
                                ->modalContent(fn (MediaAction $action): View|Htmlable => $action->getContentView())
                                ->media(fn (Registration $record): ?string => $record->getFirstMedia('id-card')
                                    ?->getTemporaryUrl(now()->addMinutes(5)),
                                )
                                ->disabled(fn (Registration $record): bool => ! $record->hasMedia('id-card')),
                        ])
                            ->label('ID Card')
                            ->columnSpan(1),
                        Actions::make([
                            MediaAction::make('receipt')
                                ->label('View Receipt')
                                ->link()
                                ->icon(Heroicon::OutlinedDocumentText)
                                ->modalHeading('Receipt')
                                ->modalWidth(Width::TwoExtraLarge)
                                ->modalContent(fn (MediaAction $action): View|Htmlable => $action->getContentView())
                                ->media(fn (Registration $record): ?string => $record->getFirstMedia('receipt')
                                    ?->getTemporaryUrl(now()->addMinutes(5)),
                                )
                                ->disabled(fn (Registration $record): bool => ! $record->hasMedia('receipt')),
                        ])
                            ->label('Receipt')
                            ->columnSpan(1),
                        TextEntry::make('period.fee')
                            ->label('Bill')
                            ->money('IDR', locale: 'id', decimalPlaces: 0)
                            ->placeholder('-')
                            ->columnSpan(1),
                        TextEntry::make('amount')
                            ->label('Paid')
                            ->money('IDR', locale: 'id', decimalPlaces: 0)
                            ->placeholder('-')
                            ->columnSpan(1),
                    ])
                    ->columns(4)
                    ->collapsible(),
                Section::make('Location Information')
                    ->schema([
                        TextEntry::make('placement.location.name')
                            ->label('Name')
                            ->placeholder('-')
                            ->columnSpan(1),
                        TextEntry::make('placement.location.level')
                            ->label('Level')
                            ->badge()
                            ->placeholder('-')
                            ->columnSpan(1),
                        TextEntry::make('placement.location.address')
                            ->label('Address')
                            ->placeholder('-')
                            ->columnSpan(1),
                        TextEntry::make('placement.supervisor.name')
                            ->label('Supervisor')
                            ->placeholder('-')
                            ->columnSpan(1),
                    ])
                    ->columns(4)
                    ->collapsible(),
            ]);
    }
}

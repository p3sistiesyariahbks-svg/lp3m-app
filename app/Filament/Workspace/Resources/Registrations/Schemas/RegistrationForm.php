<?php

namespace App\Filament\Workspace\Resources\Registrations\Schemas;

use App\Enums\DegreeLevel;
use App\Enums\EducationLevel;
use App\Enums\Gender;
use App\Enums\LivingWith;
use App\Enums\Religion;
use App\Models\Registration;
use App\Models\StudyProgram;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class RegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Wizard::make([
                    Step::make('Personal Information')
                        ->key('personalInformationStep')
                        ->schema(
                            Schema::make()
                                ->components([
                                    Section::make('Personal Information')
                                        ->relationship('studentProfile')
                                        ->schema([
                                            Grid::make(4)
                                                ->schema([
                                                    TextInput::make('nik')
                                                        ->required()
                                                        ->regex('/^\d+$/')
                                                        ->length(16)
                                                        ->unique(ignoreRecord: true)
                                                        ->inputMode('numeric')
                                                        ->validationAttribute('NIK')
                                                        ->label('NIK')
                                                        ->helperText('The 16-digit identification number.')
                                                        ->autofocus()
                                                        ->columnSpan(1),
                                                    TextInput::make('nim')
                                                        ->required()
                                                        ->regex('/^\d+$/')
                                                        ->maxLength(255)
                                                        ->unique(ignoreRecord: true)
                                                        ->inputMode('numeric')
                                                        ->validationAttribute('NIM')
                                                        ->label('NIM')
                                                        ->columnSpan(1),
                                                    Select::make('study_program_id')
                                                        ->required()
                                                        ->relationship(
                                                            name: 'studyProgram',
                                                            titleAttribute: 'name',
                                                            modifyQueryUsing: fn (Builder $query) => $query->where('is_active', true),
                                                        )
                                                        ->getOptionLabelFromRecordUsing(fn (StudyProgram $record): string => "{$record->degree->getLabel()} - {$record->name}")
                                                        ->searchable()
                                                        ->preload()
                                                        ->native(false)
                                                        ->validationAttribute('study program')
                                                        ->label('Study Program')
                                                        ->columnSpan(1),
                                                ]),
                                            Grid::make(4)
                                                ->schema([
                                                    TextInput::make('name')
                                                        ->required()
                                                        ->string()
                                                        ->minLength(3)
                                                        ->maxLength(255)
                                                        ->default(fn (): ?string => auth()->user()?->name)
                                                        ->validationAttribute('full name')
                                                        ->label('Full Name')
                                                        ->columnSpan(2),
                                                    Select::make('gender')
                                                        ->required()
                                                        ->string()
                                                        ->enum(Gender::class)
                                                        ->options(Gender::class)
                                                        ->default(Gender::Male)
                                                        ->native(false)
                                                        ->label('Gender')
                                                        ->columnSpan(1),
                                                ]),
                                            Grid::make(4)
                                                ->schema([
                                                    TextInput::make('birth_place')
                                                        ->required()
                                                        ->string()
                                                        ->minLength(3)
                                                        ->maxLength(255)
                                                        ->validationAttribute('place of birth')
                                                        ->label('Place of Birth')
                                                        ->columnSpan(1),
                                                    DatePicker::make('birth_date')
                                                        ->required()
                                                        ->before('today')
                                                        ->native(false)
                                                        ->default(now()->subDay())
                                                        ->maxDate(now()->subDay())
                                                        ->validationAttribute('date of birth')
                                                        ->label('Date of Birth')
                                                        ->columnSpan(1),
                                                    Select::make('religion')
                                                        ->nullable()
                                                        ->string()
                                                        ->enum(Religion::class)
                                                        ->options(Religion::class)
                                                        ->default(Religion::Islam)
                                                        ->native(false)
                                                        ->label('Religion')
                                                        ->columnSpan(1),
                                                ]),
                                            Grid::make(4)
                                                ->schema([
                                                    Textarea::make('address')
                                                        ->required()
                                                        ->string()
                                                        ->minLength(3)
                                                        ->maxLength(1024)
                                                        ->validationAttribute('residential address')
                                                        ->label('Residential Address')
                                                        ->autosize()
                                                        ->helperText('Your current residential address.')
                                                        ->columnSpan(2),
                                                ]),
                                            Grid::make(4)
                                                ->schema([
                                                    Select::make('living_with')
                                                        ->required()
                                                        ->string()
                                                        ->enum(LivingWith::class)
                                                        ->validationAttribute('living with')
                                                        ->options(LivingWith::class)
                                                        ->default(LivingWith::Parents)
                                                        ->native(false)
                                                        ->live()
                                                        ->afterStateUpdated(function (Set $set, ?LivingWith $state): void {
                                                            if ($state !== LivingWith::Other) {
                                                                $set('living_with_other', null);
                                                            }
                                                        })
                                                        ->label('Living With')
                                                        ->columnSpan(1),
                                                    TextInput::make('living_with_other')
                                                        ->requiredIf('living_with', LivingWith::Other)
                                                        ->string()
                                                        ->minLength(3)
                                                        ->maxLength(255)
                                                        ->validationAttribute('please specify')
                                                        ->label('Please Specify')
                                                        ->visible(fn (Get $get): bool => $get('living_with') === LivingWith::Other)
                                                        ->columnSpan(1),
                                                ]),
                                        ])
                                        ->columns(1),
                                    Section::make('Parent Information')
                                        ->schema([
                                            Group::make([
                                                Group::make([
                                                    Text::make('Father Detail'),
                                                    Grid::make(4)
                                                        ->schema([
                                                            TextInput::make('name')
                                                                ->nullable()
                                                                ->string()
                                                                ->minLength(3)
                                                                ->maxLength(255)
                                                                ->validationAttribute('father name')
                                                                ->label('Father Name')
                                                                ->live(onBlur: true)
                                                                ->columnSpan(2),
                                                        ]),
                                                ])
                                                    ->dense(),
                                                Grid::make(4)
                                                    ->schema([
                                                        TextInput::make('birth_place')
                                                            ->nullable()
                                                            ->string()
                                                            ->minLength(3)
                                                            ->maxLength(255)
                                                            ->validationAttribute('place of birth')
                                                            ->label('Place of Birth')
                                                            ->columnSpan(1),
                                                        DatePicker::make('birth_date')
                                                            ->nullable()
                                                            ->before('today')
                                                            ->native(false)
                                                            ->maxDate(now()->subDay())
                                                            ->validationAttribute('date of birth')
                                                            ->label('Date of Birth')
                                                            ->columnSpan(1),
                                                    ]),
                                                Grid::make(4)
                                                    ->schema([
                                                        Select::make('education')
                                                            ->nullable()
                                                            ->string()
                                                            ->enum(EducationLevel::class)
                                                            ->options(EducationLevel::class)
                                                            ->native(false)
                                                            ->validationAttribute('highest education')
                                                            ->label('Highest Education')
                                                            ->columnSpan(1),
                                                        TextInput::make('occupation')
                                                            ->nullable()
                                                            ->string()
                                                            ->maxLength(255)
                                                            ->label('Occupation')
                                                            ->columnSpan(1),
                                                    ]),
                                                Grid::make(4)
                                                    ->schema([
                                                        Textarea::make('address')
                                                            ->nullable()
                                                            ->string()
                                                            ->minLength(3)
                                                            ->maxLength(1024)
                                                            ->validationAttribute('full address')
                                                            ->label('Full Address')
                                                            ->autosize()
                                                            ->columnSpan(2),
                                                    ]),
                                            ])
                                                ->relationship('father'),
                                            Group::make([
                                                Group::make([
                                                    Text::make('Mother Detail'),
                                                    Grid::make(4)
                                                        ->schema([
                                                            TextInput::make('name')
                                                                ->nullable()
                                                                ->string()
                                                                ->minLength(3)
                                                                ->maxLength(255)
                                                                ->validationAttribute('mother name')
                                                                ->label('Mother Name')
                                                                ->live(onBlur: true)
                                                                ->columnSpan(2),
                                                        ]),
                                                ])
                                                    ->dense(),
                                                Grid::make(4)
                                                    ->schema([
                                                        TextInput::make('birth_place')
                                                            ->nullable()
                                                            ->string()
                                                            ->minLength(3)
                                                            ->maxLength(255)
                                                            ->validationAttribute('place of birth')
                                                            ->label('Place of Birth')
                                                            ->columnSpan(1),
                                                        DatePicker::make('birth_date')
                                                            ->nullable()
                                                            ->before('today')
                                                            ->native(false)
                                                            ->maxDate(now()->subDay())
                                                            ->validationAttribute('date of birth')
                                                            ->label('Date of Birth')
                                                            ->columnSpan(1),
                                                    ]),
                                                Grid::make(4)
                                                    ->schema([
                                                        Select::make('education')
                                                            ->nullable()
                                                            ->string()
                                                            ->enum(EducationLevel::class)
                                                            ->options(EducationLevel::class)
                                                            ->native(false)
                                                            ->validationAttribute('highest education')
                                                            ->label('Highest Education')
                                                            ->columnSpan(1),
                                                        TextInput::make('occupation')
                                                            ->nullable()
                                                            ->string()
                                                            ->maxLength(255)
                                                            ->label('Occupation')
                                                            ->columnSpan(1),
                                                    ]),
                                                Grid::make(4)
                                                    ->schema([
                                                        Textarea::make('address')
                                                            ->nullable()
                                                            ->string()
                                                            ->minLength(3)
                                                            ->maxLength(1024)
                                                            ->validationAttribute('full address')
                                                            ->label('Full Address')
                                                            ->autosize()
                                                            ->columnSpan(2),
                                                    ]),
                                            ])
                                                ->relationship('mother'),
                                        ])
                                        ->columns(1)
                                        ->collapsed(),
                                    Section::make('Contact Information')
                                        ->relationship('user')
                                        ->schema([
                                            Grid::make(4)
                                                ->schema([
                                                    TextInput::make('email')
                                                        ->required()
                                                        ->email()
                                                        ->unique(ignoreRecord: true)
                                                        ->validationAttribute('email address')
                                                        ->label('Email Address')
                                                        ->columnSpan(1),
                                                    TextInput::make('phone_number')
                                                        ->required()
                                                        ->tel()
                                                        ->minLength(6)
                                                        ->maxLength(20)
                                                        ->validationAttribute('phone number')
                                                        ->label('Phone Number')
                                                        ->placeholder('+12345678900000')
                                                        ->columnSpan(1),
                                                ]),
                                        ])
                                        ->columns(1),
                                ])
                                ->lazyLoaded(),
                        ),
                    Step::make('Requirements')
                        ->key('requirementsStep')
                        ->schema(
                            Schema::make()
                                ->components([
                                    Section::make('Required Document')
                                        ->schema([
                                            Grid::make(4)
                                                ->schema([
                                                    SpatieMediaLibraryFileUpload::make('computer_certificate')
                                                        ->required()
                                                        ->acceptedFileTypes([
                                                            'image/jpeg',
                                                            'image/png',
                                                            'application/pdf',
                                                        ])
                                                        ->maxSize(5120)
                                                        ->validationAttribute('computer certificate')
                                                        ->label('Computer Certificate')
                                                        ->collection('computer-certificate')
                                                        ->multiple(false)
                                                        ->disk('local')
                                                        ->visibility('private')
                                                        ->downloadable()
                                                        ->columnSpan(2),
                                                ]),
                                            Grid::make(4)
                                                ->schema([
                                                    SpatieMediaLibraryFileUpload::make('skpi_certificate')
                                                        ->required()
                                                        ->acceptedFileTypes([
                                                            'image/jpeg',
                                                            'image/png',
                                                            'application/pdf',
                                                        ])
                                                        ->maxSize(5120)
                                                        ->validationAttribute('SKPI certificate')
                                                        ->label('SKPI Certificate')
                                                        ->collection('skpi-certificate')
                                                        ->multiple(false)
                                                        ->disk('local')
                                                        ->visibility('private')
                                                        ->downloadable()
                                                        ->columnSpan(2),
                                                ]),
                                            Grid::make(4)
                                                ->schema([
                                                    SpatieMediaLibraryFileUpload::make('parental_consent')
                                                        ->required()
                                                        ->acceptedFileTypes([
                                                            'image/jpeg',
                                                            'image/png',
                                                            'application/pdf',
                                                        ])
                                                        ->maxSize(5120)
                                                        ->validationAttribute('parental consent letter')
                                                        ->label('Parental Consent Letter')
                                                        ->collection('parental-consent-letter')
                                                        ->multiple(false)
                                                        ->disk('local')
                                                        ->visibility('private')
                                                        ->downloadable()
                                                        ->columnSpan(2),
                                                ]),
                                        ])
                                        ->columns(1),
                                ])
                                ->lazyLoaded(),
                        ),
                    Step::make('Payment')
                        ->key('paymentStep')
                        ->schema(
                            Schema::make()
                                ->components([
                                    Section::make('Identity Verification')
                                        ->schema([
                                            Grid::make(4)
                                                ->schema([
                                                    SpatieMediaLibraryFileUpload::make('id_card')
                                                        ->required()
                                                        ->acceptedFileTypes([
                                                            'image/jpeg',
                                                            'image/png',
                                                            'application/pdf',
                                                        ])
                                                        ->maxSize(5120)
                                                        ->validationAttribute('ID card')
                                                        ->label('ID Card')
                                                        ->collection('id-card')
                                                        ->multiple(false)
                                                        ->disk('local')
                                                        ->visibility('private')
                                                        ->downloadable()
                                                        ->columnSpan(2),
                                                ]),
                                        ])
                                        ->columns(1),
                                    Section::make('Payment Information')
                                        ->schema([
                                            Grid::make(4)
                                                ->schema([
                                                    SpatieMediaLibraryFileUpload::make('receipt')
                                                        ->required()
                                                        ->acceptedFileTypes([
                                                            'image/jpeg',
                                                            'image/png',
                                                            'application/pdf',
                                                        ])
                                                        ->maxSize(5120)
                                                        ->label('Receipt')
                                                        ->collection('receipt')
                                                        ->multiple(false)
                                                        ->disk('local')
                                                        ->visibility('private')
                                                        ->downloadable()
                                                        ->columnSpan(2),
                                                ]),
                                            Grid::make(4)
                                                ->schema([
                                                    TextInput::make('amount')
                                                        ->required()
                                                        ->numeric()
                                                        ->integer()
                                                        ->minValue(1)
                                                        ->label('Amount')
                                                        ->prefix('Rp')
                                                        ->helperText('Amount shown on the receipt.')
                                                        ->columnSpan(1),
                                                ]),
                                        ])
                                        ->columns(1),
                                ])
                                ->lazyLoaded(),
                        ),
                    Step::make('Review')
                        ->key('reviewStep')
                        ->schema(
                            Schema::make()
                                ->components([
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
                                        ->columns(1),
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
                                        ->columns(1),
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
                                        ->columns(4),
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
                                        ->columns(4),
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
                                        ->columns(4),
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
                                        ->columns(4),
                                    Checkbox::make('agreement')
                                        ->label('I declare that all the information I have provided is true and correct.')
                                        ->accepted()
                                        ->validationMessages(['accepted' => 'You must confirm that your data is correct before finishing.'])
                                        ->dehydrated(false),
                                ])
                                ->lazyLoaded(),
                        ),
                ])
                    ->key('registrationWizard')
                    ->persistStepInQueryString()
                    ->skippable()
                    ->nextAction(fn (Action $action) => $action->label('Next step'))
                    ->previousAction(fn (Action $action) => $action->label('Previous step'))
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                        <x-filament::button
                            type="submit"
                            size="sm"
                            wire:target="save"
                        >
                            Submit
                        </x-filament::button>
                    BLADE))),
            ]);
    }
}

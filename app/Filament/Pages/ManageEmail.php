<?php

namespace App\Filament\Pages;

use App\Enums\MailDriver;
use App\Enums\MailEncryption;
use App\Filament\Clusters\Settings\SettingsCluster;
use App\Settings\EmailSettings;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Callout;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageEmail extends SettingsPage
{
    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $navigationLabel = 'Email';

    protected static string $settings = EmailSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Group::make([
                    Section::make('Enable Email')
                        ->description('Enable email functionality.')
                        ->afterHeader([
                            Toggle::make('enable_email')
                                ->hiddenLabel()
                                ->live(),
                        ])
                        ->schema([
                            Grid::make()
                                ->schema([
                                    Select::make('mail_driver')
                                        ->required()
                                        ->string()
                                        ->enum(MailDriver::class)
                                        ->validationAttribute('mail driver')
                                        ->label('Mail Driver')
                                        ->options(MailDriver::class)
                                        ->native(false)
                                        ->selectablePlaceholder(false)
                                        ->live()
                                        ->disabled(fn (Get $get) => ! $get('enable_email')),
                                    TextInput::make('postmark_api_key')
                                        ->required()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('API Key')
                                        ->visible(fn (Get $get): bool => $get('mail_driver') === MailDriver::Postmark)
                                        ->columnSpanFull(),
                                    TextInput::make('resend_api_key')
                                        ->required()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('API Key')
                                        ->visible(fn (Get $get): bool => $get('mail_driver') === MailDriver::Resend)
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    Section::make('SMTP Configuration')
                        ->description('Configure your SMTP server settings.')
                        ->schema([
                            Callout::make()
                                ->description('Use TLS encryption for secure email delivery. Port 587 for TLS, 465 for SSL.')
                                ->info(),
                            Grid::make()
                                ->schema([
                                    TextInput::make('smtp_host')
                                        ->requiredIf('mail_driver', MailDriver::Smtp)
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->validationAttribute('smtp host')
                                        ->label('SMTP Host')
                                        ->prefixIcon(Heroicon::OutlinedServerStack)
                                        ->placeholder('smtp.example.com')
                                        ->disabled(fn (Get $get) => ! $get('enable_email')),
                                    TextInput::make('smtp_port')
                                        ->requiredIf('mail_driver', MailDriver::Smtp)
                                        ->numeric()
                                        ->minValue(1)
                                        ->maxValue(100)
                                        ->validationAttribute('smtp port')
                                        ->label('SMTP Port')
                                        ->belowContent('587 for TLS, 465 for SSL, 25 for unencrypted.')
                                        ->disabled(fn (Get $get) => ! $get('enable_email')),
                                    TextInput::make('smtp_username')
                                        ->requiredIf('mail_driver', MailDriver::Smtp)
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->validationAttribute('smtp username')
                                        ->label('SMTP Username')
                                        ->disabled(fn (Get $get) => ! $get('enable_email')),
                                    TextInput::make('smtp_password')
                                        ->requiredIf('mail_driver', MailDriver::Smtp)
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->validationAttribute('smtp password')
                                        ->label('SMTP Password')
                                        ->disabled(fn (Get $get) => ! $get('enable_email')),
                                    Select::make('encryption')
                                        ->requiredIf('mail_driver', MailDriver::Smtp)
                                        ->string()
                                        ->enum(MailEncryption::class)
                                        ->label('Encryption')
                                        ->options(MailEncryption::class)
                                        ->native(false)
                                        ->selectablePlaceholder(false)
                                        ->disabled(fn (Get $get) => ! $get('enable_email')),
                                ]),
                        ])
                        ->collapsible(),
                    Section::make('From Address')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextInput::make('from_email_address')
                                        ->required()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('From Email Address')
                                        ->disabled(fn (Get $get) => ! $get('enable_email')),
                                    TextInput::make('from_name')
                                        ->required()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('From Name')
                                        ->disabled(fn (Get $get) => ! $get('enable_email')),
                                ]),
                        ]),
                ]),
            ]);
    }
}

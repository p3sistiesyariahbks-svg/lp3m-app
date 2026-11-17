<?php

namespace App\Filament\Admin\Pages;

use App\Enums\MailDriver;
use App\Filament\Admin\Clusters\Settings\SettingsCluster;
use App\Settings\EmailSettings;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                    Section::make('Mail Driver')
                        ->description('Choose how emails are delivered.')
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
                                        ->live(),
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
                                ->description('Encryption follows the port: 465 uses SSL/TLS, 587 uses STARTTLS.')
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
                                        ->placeholder('smtp.example.com'),
                                    TextInput::make('smtp_port')
                                        ->requiredIf('mail_driver', MailDriver::Smtp)
                                        ->numeric()
                                        ->minValue(1)
                                        ->maxValue(100)
                                        ->validationAttribute('smtp port')
                                        ->label('SMTP Port'),
                                    TextInput::make('smtp_username')
                                        ->requiredIf('mail_driver', MailDriver::Smtp)
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->validationAttribute('smtp username')
                                        ->label('SMTP Username'),
                                    TextInput::make('smtp_password')
                                        ->requiredIf('mail_driver', MailDriver::Smtp)
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->password()
                                        ->revealable()
                                        ->validationAttribute('smtp password')
                                        ->label('SMTP Password'),
                                ]),
                        ])
                        ->collapsible(),
                    Section::make('From Address')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextInput::make('from_email_address')
                                        ->required()
                                        ->email()
                                        ->maxLength(100)
                                        ->label('From Email Address'),
                                    TextInput::make('from_name')
                                        ->required()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('From Name'),
                                ]),
                        ]),
                ]),
            ]);
    }
}

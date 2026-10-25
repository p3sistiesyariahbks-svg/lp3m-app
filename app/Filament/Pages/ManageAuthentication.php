<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Settings\AuthenticationSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageAuthentication extends SettingsPage
{
    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static ?string $navigationLabel = 'Authentication';

    protected static string $settings = AuthenticationSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Group::make([
                    Section::make('Enable User Registration')
                        ->description('Allow new users to register on the website.')
                        ->afterHeader([
                            Toggle::make('enable_user_registration')
                                ->hiddenLabel(),
                        ]),
                    Section::make('Require Email Verification')
                        ->description('New users must verify their email address.')
                        ->afterHeader([
                            Toggle::make('require_email_verification')
                                ->hiddenLabel(),
                        ]),
                    Section::make('Enable Google Login')
                        ->description('Allow users to sign in with their Google account.')
                        ->afterHeader([
                            Toggle::make('enable_google_login')
                                ->hiddenLabel()
                                ->live(),
                        ])
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextInput::make('google_client_id')
                                        ->requiredIf('enable_google_login', true)
                                        ->markAsRequired()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->validationAttribute('google client id')
                                        ->label('Google Client ID')
                                        ->placeholder('xxxxxxxxxxxx.apps.googleusercontent.com')
                                        ->disabled(fn (Get $get) => ! $get('enable_google_login')),
                                    TextInput::make('google_client_secret')
                                        ->requiredIf('enable_google_login', true)
                                        ->markAsRequired()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->validationAttribute('google client secret')
                                        ->label('Google Client Secret')
                                        ->placeholder('GOCSPX-xxxxxxxxxxxx')
                                        ->disabled(fn (Get $get) => ! $get('enable_google_login')),
                                ]),
                        ]),
                    Section::make('Enable GitHub Login')
                        ->description('Allow users to sign in with their GitHub account.')
                        ->afterHeader([
                            Toggle::make('enable_github_login')
                                ->hiddenLabel()
                                ->live(),
                        ])
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextInput::make('github_client_id')
                                        ->requiredIf('enable_github_login', true)
                                        ->markAsRequired()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->validationAttribute('github client id')
                                        ->label('GitHub Client ID')
                                        ->placeholder('Iv1.xxxxxxxxxxxx')
                                        ->disabled(fn (Get $get) => ! $get('enable_github_login')),
                                    TextInput::make('github_client_secret')
                                        ->requiredIf('enable_github_login', true)
                                        ->markAsRequired()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->validationAttribute('github client secret')
                                        ->label('GitHub Client Secret')
                                        ->placeholder('xxxxxxxxxxxxxxxxxxxxxxxxxxxx')
                                        ->disabled(fn (Get $get) => ! $get('enable_github_login')),
                                ]),
                        ]),
                    Section::make('Enable Facebook Login')
                        ->description('Allow users to sign in with their Facebook account.')
                        ->afterHeader([
                            Toggle::make('enable_facebook_login')
                                ->hiddenLabel()
                                ->live(),
                        ])
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextInput::make('facebook_app_id')
                                        ->requiredIf('enable_facebook_login', true)
                                        ->markAsRequired()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->validationAttribute('facebook app id')
                                        ->label('Facebook App ID')
                                        ->placeholder('xxxxxxxxxxxx')
                                        ->disabled(fn (Get $get) => ! $get('enable_facebook_login')),
                                    TextInput::make('facebook_app_secret')
                                        ->requiredIf('enable_facebook_login', true)
                                        ->markAsRequired()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->validationAttribute('facebook app secret')
                                        ->label('Facebook App Secret')
                                        ->placeholder('xxxxxxxxxxxxxxxxxxxxxxxxxxxx')
                                        ->disabled(fn (Get $get) => ! $get('enable_facebook_login')),
                                ]),
                        ]),
                ]),
            ]);
    }
}

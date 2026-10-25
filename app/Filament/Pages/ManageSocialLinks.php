<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Settings\SocialLinksSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageSocialLinks extends SettingsPage
{
    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShare;

    protected static ?string $navigationLabel = 'Social Links';

    protected static string $settings = SocialLinksSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Social Links')
                    ->schema([
                        TextInput::make('facebook')
                            ->nullable()
                            ->string()
                            ->minLength(3)
                            ->maxLength(100)
                            ->label('Facebook')
                            ->placeholder('https://facebook.com/yourpage'),
                        TextInput::make('twitter')
                            ->nullable()
                            ->string()
                            ->minLength(3)
                            ->maxLength(100)
                            ->label('Twitter/X Handle')
                            ->placeholder('@yourhandle')
                            ->belowContent('Include the @ symbol.'),
                        TextInput::make('instagram')
                            ->nullable()
                            ->string()
                            ->minLength(3)
                            ->maxLength(100)
                            ->label('Instagram')
                            ->placeholder('https://instagram.com/yourpage'),
                        TextInput::make('linkedin')
                            ->nullable()
                            ->string()
                            ->minLength(3)
                            ->maxLength(100)
                            ->label('LinkedIn')
                            ->placeholder('https://linkedin.com/company/yourcompany'),
                        TextInput::make('youtube')
                            ->nullable()
                            ->string()
                            ->minLength(3)
                            ->maxLength(100)
                            ->label('YouTube')
                            ->placeholder('https://youtube.com/@yourchannel'),
                        TextInput::make('github')
                            ->nullable()
                            ->string()
                            ->minLength(3)
                            ->maxLength(100)
                            ->label('GitHub')
                            ->placeholder('https://github.com/yourorg'),
                        TextInput::make('discord')
                            ->nullable()
                            ->string()
                            ->minLength(3)
                            ->maxLength(100)
                            ->label('Discord')
                            ->placeholder('https://discord.gg/yourserver'),
                    ]),
            ]);
    }
}

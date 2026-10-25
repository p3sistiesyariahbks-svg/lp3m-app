<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Str;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('User Information')
                    ->columns(2)
                    ->schema([
                        Group::make([
                            SpatieMediaLibraryFileUpload::make('avatar')
                                ->hiddenLabel()
                                ->avatar()
                                ->image()
                                ->imageEditor()
                                ->circleCropper()
                                ->maxSize(2048)
                                ->acceptedFileTypes(['image/png', 'image/jpeg'])
                                ->collection('avatars')
                                ->alignCenter(),
                            Group::make([
                                Text::make('Upload Avatar')
                                    ->weight(FontWeight::Medium)
                                    ->color('neutral'),
                                Text::make('PNG, JPG up to 2MB'),
                            ])
                                ->gap(false)
                                ->extraAttributes(['class' => 'text-center']),
                        ])
                            ->dense(false)
                            ->columnSpanFull(),
                        TextInput::make('name')
                            ->required()
                            ->string()
                            ->minLength(3)
                            ->maxLength(255)
                            ->autofocus()
                            ->columnSpanFull(),
                        TextInput::make('email')
                            ->required()
                            ->email()
                            ->unique(table: 'users', column: 'email')
                            ->label('Email Address')
                            ->columnSpan(1),
                        TextInput::make('phone_number')
                            ->tel()
                            ->label('Phone Number')
                            ->columnSpan(1),
                        Group::make([
                            Text::make('Set a secure password for the new user'),
                            Grid::make(2)
                                ->schema([
                                    TextInput::make('password')
                                        ->password()
                                        ->minLength(8)
                                        ->maxLength(255)
                                        ->required()
                                        ->confirmed()
                                        ->revealable()
                                        ->belowContent(Schema::start([
                                            Action::make('generatePassword')
                                                ->action(function (Set $set): void {
                                                    $password = Str::password(16);
                                                    $set('password', $password);
                                                    $set('password_confirmation', $password);
                                                }),
                                        ])),
                                    TextInput::make('password_confirmation')
                                        ->password()
                                        ->label('Password Confirmation')
                                        ->revealable()
                                        ->dehydrated(false),
                                ])
                                ->dense(false),
                        ])
                            ->dense(true)
                            ->columnSpanFull(),
                        CheckboxList::make('roles')
                            ->required()
                            ->minItems(1)
                            ->relationship('roles', 'name')
                            ->label('Roles')
                            ->columns(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
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

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel(false)
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
                                ->disk('public')
                                ->visibility('public')
                                ->downloadable()
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
                        $this->getNameFormComponent()
                            ->columnSpanFull(),
                        $this->getEmailFormComponent()
                            ->columnSpan(1),
                        TextInput::make('phone_number')
                            ->tel()
                            ->minLength(6)
                            ->maxLength(20)
                            ->label('Phone Number')
                            ->placeholder('+12345678900000')
                            ->columnSpan(1),
                        Group::make([
                            Text::make('Leave blank to keep your current password'),
                            Grid::make(2)
                                ->schema([
                                    $this->getPasswordFormComponent()
                                        ->belowContent(Schema::start([
                                            Action::make('generatePassword')
                                                ->action(function (Set $set): void {
                                                    $password = Str::password(16);
                                                    $set('password', $password);
                                                    $set('passwordConfirmation', $password);
                                                }),
                                        ])),
                                    $this->getPasswordConfirmationFormComponent(),
                                    $this->getCurrentPasswordFormComponent()
                                        ->columnSpanFull(),
                                ])
                                ->dense(false),
                        ])
                            ->dense(true)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

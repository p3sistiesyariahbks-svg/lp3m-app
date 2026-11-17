<?php

namespace App\Filament\Workspace\Resources\Registrations\Schemas;

use App\Models\Registration;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
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
                        ->afterValidation(function (Step $component, Registration $record): void {
                            Notification::make()
                                ->success()
                                ->title("{$component->getLabel()} saved")
                                ->send();
                        })
                        ->schema(
                            Schema::make()
                                ->components([
                                    // ...
                                ])
                                ->lazyLoaded(),
                        ),
                    Step::make('Payment')
                        ->key('paymentStep')
                        ->afterValidation(function (Step $component, Registration $record): void {
                            Notification::make()
                                ->success()
                                ->title("{$component->getLabel()} saved")
                                ->send();
                        })
                        ->schema(
                            Schema::make()
                                ->components([
                                    // ...
                                ])
                                ->lazyLoaded(),
                        ),
                    Step::make('Activity Report')
                        ->key('reportStep')
                        ->afterValidation(function (Step $component, Registration $record): void {
                            Notification::make()
                                ->success()
                                ->title("{$component->getLabel()} saved")
                                ->send();
                        })
                        ->schema(
                            Schema::make()
                                ->components([
                                    // ...
                                ])
                                ->lazyLoaded(),
                        ),
                    Step::make('Completion')
                        ->key('completionStep')
                        ->afterValidation(function (Step $component, Registration $record): void {
                            Notification::make()
                                ->success()
                                ->title("{$component->getLabel()} saved")
                                ->send();
                        })
                        ->schema(
                            Schema::make()
                                ->components([
                                    // ...
                                ])
                                ->lazyLoaded(),
                        ),
                ])
                    ->key('registrationWizard')
                    ->skippable(false)
                    ->nextAction(fn (Action $action) => $action->label('Next step'))
                    ->previousAction(fn (Action $action) => $action->label('Previous step'))
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                        <x-filament::button
                            type="submit"
                            size="sm"
                        >
                            Finish
                        </x-filament::button>
                    BLADE))),
            ]);
    }
}

<?php

namespace App\Listeners;

use App\Settings\AppearanceSettings;
use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelSettings\Events\SavingSettings;

class DeleteSettingFiles
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SavingSettings $event): void
    {
        if ($event->settings instanceof GeneralSettings) {
            if (
                $event->originalValues['site_logo'] &&
                $event->originalValues['site_logo'] !== $event->properties['site_logo']
            ) {
                Storage::disk('public')->delete($event->originalValues['site_logo']);
            }

            if (
                $event->originalValues['favicon'] &&
                $event->originalValues['favicon'] !== $event->properties['favicon']
            ) {
                Storage::disk('public')->delete($event->originalValues['favicon']);
            }
        }

        if ($event->settings instanceof SeoSettings) {
            if (
                $event->originalValues['og_image'] &&
                $event->originalValues['og_image'] !== $event->properties['og_image']
            ) {
                Storage::disk('public')->delete($event->originalValues['og_image']);
            }
        }

        if ($event->settings instanceof AppearanceSettings) {
            if (
                $event->originalValues['media_path'] &&
                $event->originalValues['media_path'] !== $event->properties['media_path']
            ) {
                Storage::disk('public')->delete($event->originalValues['media_path']);
            }
        }
    }
}

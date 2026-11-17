<?php

use App\Enums\StatusType;
use App\Models\Announcement;
use App\Models\Document;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Period;
use Illuminate\Support\Facades\Schedule;

Schedule::command('auth:clear-resets')->everyFifteenMinutes();
Schedule::command('ban:delete-expired')->everyFifteenMinutes();
Schedule::command('seo:sitemap')->daily();

Schedule::call(function (): void {
    foreach ([
        Announcement::class,
        Document::class,
        Gallery::class,
        News::class,
        Page::class,
        Partner::class,
        Period::class,
    ] as $model) {
        $model::query()
            ->where('status', StatusType::Scheduled)
            ->where('published_at', '<=', now())
            ->update(['status' => StatusType::Published]);
    }
})
    ->name('publish-scheduled-records')
    ->everyMinute();

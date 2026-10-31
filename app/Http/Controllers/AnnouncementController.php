<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use Inertia\Inertia;
use Inertia\Response;

class AnnouncementController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Announcement $announcement): Response
    {
        views($announcement)->record();

        $announcement = $announcement->toResource(AnnouncementResource::class);

        return Inertia::render('announcements/show', [
            'announcement' => $announcement,
        ]);
    }
}

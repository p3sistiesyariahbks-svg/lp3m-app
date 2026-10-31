<?php

namespace App\Observers;

use App\Models\Gallery;
use Illuminate\Support\Str;
use JordJD\PHPSummary\SummaryTool;
use Soundasleep\Html2Text;

class GalleryObserver
{
    /**
     * Handle the Gallery "saving" event.
     */
    public function saving(Gallery $gallery): void
    {
        $text = Html2Text::convert($gallery->content, [
            'ignore_errors' => true,
            'drop_links' => true,
            'char_set' => 'auto',
        ]);

        $gallery->description = Str::limit(
            (new SummaryTool($text))->getSummary(),
            160
        );
    }
}

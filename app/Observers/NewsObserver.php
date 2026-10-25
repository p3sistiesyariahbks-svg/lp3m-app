<?php

namespace App\Observers;

use App\Models\News;
use Illuminate\Support\Str;
use JordJD\PHPSummary\SummaryTool;
use Mtownsend\ReadTime\ReadTime;
use Soundasleep\Html2Text;

class NewsObserver
{
    /**
     * Handle the News "saving" event.
     */
    public function saving(News $news): void
    {
        $text = Html2Text::convert($news->content, [
            'ignore_errors' => true,
            'drop_links' => true,
            'char_set' => 'auto',
        ]);

        $news->description = Str::limit(
            (new SummaryTool($text))->getSummary(),
            160
        );
        $news->reading_time = (new ReadTime($text))
            ->abbreviated()
            ->omitSeconds()
            ->wpm(230)
            ->get();
    }
}

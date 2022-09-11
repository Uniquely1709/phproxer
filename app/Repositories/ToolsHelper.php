<?php

namespace App\Repositories;

use App\Models\Episodes;
use App\Models\Series;

class ToolsHelper
{
    public static function pathBuilder(string $folder, string $filename):string
    {
        $series = Series::where('ProxerId', $folder)->first();
        return sprintf(
            '%1$s%2$s%3$s',
            $series->TitleORG,
            '/',
            $filename,
        );
    }

    public static function nameBuilder(int $seriesId, Episodes $episode):string
    {
        $series = Series::where('ProxerId', $seriesId)->first();

        return sprintf(
            '%1$s%2$s%3$s%4$s',
            $series->TitleORG,
            ' - Episode ',
            $episode->EpisodeID,
            '.mp4'
        );
    }
}

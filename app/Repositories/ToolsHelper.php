<?php

namespace App\Repositories;

use App\Models\Episodes;
use App\Models\Series;

class ToolsHelper
{
    public static function pathBuilder(string $folder, string $filename): string
    {
        dump($folder);
        $series = Series::where('ProxerId', $folder)->first();
        dump($series);
        return sprintf(
            '%1$s%2$s%3$s%4$s',
            '/anime/',
            $series->TitleORG,
            '/',
            $filename,
        );
    }

    public static function nameBuilder(int $seriesId, Episodes $episode): string
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

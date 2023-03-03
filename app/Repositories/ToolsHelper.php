<?php

namespace App\Repositories;

use App\Models\Episodes;
use App\Models\Series;

class ToolsHelper
{
    public static function pathBuilder(Series $series, string $filename): string
    {
        return sprintf(
            '%1$s%2$s%3$s%4$s%5$s%6$s',
            '/anime/',
            $series->Title,
            '/',
            'Season '.$series->Season,
            '/',
            $filename,
        );
    }

    public static function nameBuilder(int $seriesId, Episodes $episode): string
    {
        $series = Series::where('ProxerId', $seriesId)->first();

        return sprintf(
            '%1$s%2$s%3$s%4$s%5$s',
            $series->Title,
            '-Season'.$series->Season,
            '-Episode',
            $episode->EpisodeID,
            '.mp4'
        );
    }
}

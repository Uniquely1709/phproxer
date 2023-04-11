<?php

namespace App\Repositories;

use App\Models\Episodes;
use App\Models\Series;
use Storage;

class ToolsHelper
{
    public static function pathBuilder(Series $series, string $filename): string
    {
        if (10>$series->Season) {
            $seasonNumber = '0'.$series->Season;
        } else {
            $seasonNumber = $series->Season;
        }
        return sprintf(
            '%1$s%2$s%3$s%4$s%5$s%6$s',
            '/anime/',
            $series->Title,
            '/',
            'Season '.$seasonNumber,
            '/',
            $filename,
        );

    }

    public static function nameBuilder(int $seriesId, Episodes $episode): string
    {
        $series = Series::where('ProxerId', $seriesId)->first();

        if (10>$series->Season) {
            $seasonNumber = '0'.$series->Season;
        } else {
            $seasonNumber = $series->Season;
        }

        if (10>$episode->EpisodeID) {
            $episodeNumber = '0'.$episode->EpisodeID;
        } else {
            $episodeNumber = $episode->EpisodeID;
        }

        return sprintf(
            '%1$s%2$s%3$s%4$s%5$s',
            $series->Title,
            '-Season'.$seasonNumber,
            '-Episode',
            $episodeNumber,
            '.mp4'
        );
    }

    public static function storeCookies(array $cookies): bool
    {
        return Storage::disk('local')->put('cookies.json', json_encode($cookies));
    }

    public static function getCookies(): ?array
    {
        $file = Storage::disk('local')->get('cookies.json');
        if (empty($file)) {
            return null;
        }
        return json_decode($file, true);
    }
}

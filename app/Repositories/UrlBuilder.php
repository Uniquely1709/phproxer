<?php

namespace App\Repositories;

class UrlBuilder
{
    private string $baseUrl = 'https://proxer.me/';
    private string $seriesOverview = '/list#top';
    private string $seriesDetails = '/details#top';
    private string $enSub = 'engsub';

    public function getSeries(int $seriesId):string
    {
        return sprintf(
            '%1$s%2$s%3$s%4$s',
            $this->baseUrl,
            'info/',
            $seriesId,
            $this->seriesOverview,
        );
    }

    public function getOverview(int $seriesId):string
    {
        return sprintf(
            '%1$s%2$s%3$s%4$s',
            $this->baseUrl,
            'info/',
            $seriesId,
            $this->seriesDetails,
        );
    }

    public function getEpisodeId(int $seriesId, int $episodeId):string
    {
        return sprintf(
            '%1$s%2$s%3$s%4$s%5$s',
            $this->baseUrl,
            'watch/',
            $seriesId.'/',
            $episodeId.'/',
            $this->enSub,
        );
    }

    public function baseUrl():string
    {
        return $this->baseUrl;
    }
}

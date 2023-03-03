<?php

namespace App\Console\Commands;

use App\Models\Episodes;
use App\Models\Series;
use App\Repositories\Logger;
use App\Repositories\ProxerVideoHelper;
use App\Repositories\UrlBuilder;
use Illuminate\Console\Command;

class CheckUnpublishedEpisodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phproxer:checkUnpublishedEpisodes {episodeId=null}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if unpublished episode is published now';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Logger::debug('Started CheckUnpublishedEpisodes Command');
        $recursive = false;
        $episodeId = $this->argument('episodeId');
        if ("null" === $episodeId) {
            $recursive = true;
            Logger::debug('Getting all upcoming unpublished Episodes...');
            $series =Series::inProgress();
            $opens = [];
            foreach ($series as $serie) {
                $opens[] = $serie->nextEpisode();
            }
        } else {
            Logger::debug('Checking only episode id '.$episodeId.' provided by command call');
            $opens = Episodes::query()
                ->where('id', $episodeId)
                ->get();
        }

        $proxer = new ProxerVideoHelper();
        $urlBuilder = new UrlBuilder();
        $proxer->login();
        foreach ($opens as $open) {
            $this->checkEpisode($open, $recursive);
        }
        Logger::debug('Finished CheckUnpublishedEpisodes Command');
        return self::SUCCESS;
    }

    private function checkEpisode(Episodes $episode, bool $recursive = true): void
    {
        $proxer = new ProxerVideoHelper();
        $urlBuilder = new UrlBuilder();


        $episodeId = $episode->EpisodeID;
        $seriesId = $episode->serie()->first()->ProxerId;
        $url = $urlBuilder->getEpisodeId($seriesId, $episodeId);
        $released = $proxer->checkEpisodeReleased($url);

        if (true === $released) {
            $episode->update([
                'Published'=>true,
            ]);
            $next = $episode->next();
            if (null !== $next && $recursive) {
                if ($next->epNumber <= $episode->parent->Episodes) {
                    $this->checkEpisode($next);
                } else {
                    $episode->parent()->update([
                        'next_episode_id' => null
                    ]);
                }
            }
        } else {
            $episode->parent()->update([
                'next_episode_id' => $episode->id
            ]);
        }
    }
}

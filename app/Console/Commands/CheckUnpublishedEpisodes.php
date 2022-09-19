<?php

namespace App\Console\Commands;

use App\Models\Episodes;
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
    protected $signature = 'phproxer:checkUnpublishedEpisodes';

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
        $opens = Episodes::where('Downloaded', false)
            ->where('DownloadUrl', null)
            ->where('Retries', '<=', 5)
            ->where('Published', false)
            ->get();

        $proxer = new ProxerVideoHelper();
        $urlBuilder = new UrlBuilder();
        $proxer->login();
        foreach ($opens as $open) {
            dump($open->EpisodeID);
            dump($open->serie()->first()->ProxerId);
            $episodeId = $open->EpisodeID;
            $seriesId = $open->serie()->first()->ProxerId;
            $url = $urlBuilder->getEpisodeId($seriesId, $episodeId);
            dump($url);

        }
        return 0;
    }
}

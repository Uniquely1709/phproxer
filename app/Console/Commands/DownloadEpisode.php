<?php

namespace App\Console\Commands;

use App\Models\Episodes;
use App\Repositories\ProxerHelper;
use App\Repositories\ProxerVideoHelper;
use App\Repositories\UrlBuilder;
use Goutte\Client;
use Illuminate\Console\Command;

class DownloadEpisode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phproxer:downloadEpisode {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'downloads an episode already indexed by id';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('id');

        $episode = Episodes::where('id', $id)->first();
        $seriesId = $episode->serie()->first()->ProxerId;

        $urlBuilder = new UrlBuilder();

        $url = $urlBuilder->getEpisodeId($seriesId, $id);

        $video = new ProxerVideoHelper();
        $video->login();
        if(!$video->downloadEpisode($url, $seriesId, $episode)){
            $episode->update(['Retries'=>$episode->Retries+1]);
        }
        return 0;
    }
}

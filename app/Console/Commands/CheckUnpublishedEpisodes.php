<?php

namespace App\Console\Commands;

use App\Models\Episodes;
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

        $episodeId = $this->argument('episodeId');
        if ($episodeId > 1){
            Logger::debug('Getting all unpublished Episodes...');
            $opens = Episodes::query()
                ->where('Downloaded', false)
                ->where('DownloadUrl', null)
                ->where('Retries', '<=', 5)
                ->where('Published', false)
                ->get();
        }else{
            Logger::debug('Checking only episode id '.$episodeId.' provided by command call');
            $opens = Episodes::query()
                ->where('id', $episodeId)
                ->get();
        }

        $proxer = new ProxerVideoHelper();
        $urlBuilder = new UrlBuilder();
        $proxer->login();

        foreach ($opens as $open) {
            $episodeId = $open->EpisodeID;
            $seriesId = $open->serie()->first()->ProxerId;
            $url = $urlBuilder->getEpisodeId($seriesId, $episodeId);
            $released = $proxer->checkEpisodeReleased($url);
            $open->update([
                'Published'=>$released,
            ]);
        }
        Logger::debug('Finished CheckUnpublishedEpisodes Command');
        return self::SUCCESS;
    }
}

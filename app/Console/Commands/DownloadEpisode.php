<?php

namespace App\Console\Commands;

use App\Models\Episodes;
use App\Models\Series;
use App\Notifications\SendTelegram;
use App\Repositories\ToolsHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class DownloadEpisode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phproxer:downloadEpisode {id} {--queue=downloads}';

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
        $series = Series::where('id', $episode->series_id)->first();


        $episodePath = ToolsHelper::nameBuilder($series->ProxerId, $episode);

        $vid = file_get_contents($episode->DownloadUrl);

        $state = Storage::disk(config('phproxer.proxer_storage'))->put(ToolsHelper::pathBuilder($series->ProxerId, $episodePath),$vid);

        if(!$state){
            $episode->update(['Retries'=>$episode->Retries+1]);
        }else{
            $episode->update(['Downloaded' => true]);
            Notification::send('',new SendTelegram('Downloaded Episode '.$episode->EpisodeID.' from "'.$series->TitleORG.'"'));

        }
//        $seriesId = $episode->serie()->first()->ProxerId;
//
//        $urlBuilder = new UrlBuilder();
//
//        $url = $urlBuilder->getEpisodeId($seriesId, $id);
//
//        $video = new ProxerVideoHelper();
//        $video->login();
//        if(!$video->downloadEpisode($url, $seriesId, $episode)){
//            $episode->update(['Retries'=>$episode->Retries+1]);
//        }else{
//            $episode->update(['Downloaded' => true]);
//        }
        return 0;
    }
}

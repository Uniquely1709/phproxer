<?php

namespace App\Console\Commands;

use App\Models\Episodes;
use App\Models\Series;
use App\Notifications\SendTelegram;
use App\Repositories\ProxerVideoHelper;
use App\Repositories\UrlBuilder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class AddSeries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phproxer:addSeries {id} {season} {title?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new Series to PHProxer identified by Proxer ID';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $seriesId = $this->argument('id');
        $title = $this->argument('title');
        $season = $this->argument('season');

        $urlBuilder = new UrlBuilder();

        $proxer = new ProxerVideoHelper();

        dump('Adding '.$seriesId.' to phproxer..');

        $proxer->login();
        $episodes = $proxer->getNumberOfEpisodes($seriesId);
        //        $originalTitle = $proxer->getOriginalTitle();

        //TODO add EN and GER Title
        //        $enTitle = $proxer->getEnTitle();
        //        dd($enTitle);

        $serie = Series::create([
            'TitleEN' => '',
            'TitleGER' => '',
            'TitleORG' => '',
            'ProxerId' => $seriesId,
            'Published'=> true,
            'Completed' => false,
            'Episodes' => $episodes,
            'Title' => $title,
            'Season' => $season
        ]);
        dump($serie->id);

        for ($i = 1; $i <= $episodes; $i++) {
            $episode = Episodes::create([
                'series_id' => $serie->id,
                'EpisodeId' => $i,
                'epNumber' => $i,
                'Downloaded' => false,
                'Published'=> false,
                'Retries' => 0,
            ]);
            if (1 === $i) {
                $serie->update([
                    'next_episode_id' => $episode->id,
                ]);
            }
        }

        $serie->Scraped = true;
        $serie->save();
        Notification::send('', new SendTelegram('Added Series "'.$serie->TitleORG.'"'));

        return 0;
    }
}

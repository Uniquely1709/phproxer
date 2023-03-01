<?php

namespace App\Console\Commands;

use App\Models\Episodes;
use App\Models\Series;
use App\Notifications\SendTelegram;
use App\Repositories\ProxerHelper;
use App\Repositories\UrlBuilder;
use Goutte\Client;
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
        $client = new Client();
        $proxer = new ProxerHelper($seriesId);

        dump('Adding '.$seriesId.' to phproxer..');

        $name = $proxer->login();

        dump('Logged in as '.$name);

        $episodes = $proxer->getNumberOfEpisodes();
        $originalTitle = $proxer->getOriginalTitle();

        //TODO add EN and GER Title
//        $enTitle = $proxer->getEnTitle();
//        dd($enTitle);

        $serie = Series::create([
            'TitleEN' => '',
            'TitleGER' => '',
            'TitleORG' => $originalTitle,
            'ProxerId' => $seriesId,
            'Published'=> true,
            'Completed' => false,
            'Episodes' => $episodes['lastEpisode'],
            'Title' => $title,
            'Season' => $season
        ]);
        dump($serie->id);

        for ($i = 1; $i <= $episodes['lastEpisode']; $i++) {
            Episodes::create([
                'series_id' => $serie->id,
                'EpisodeId' => $i,
                'Downloaded' => false,
                'Published'=> false,
                'Retries' => 0,
            ]);
        }

        $serie->Scraped = true;
        $serie->save();
        Notification::send('', new SendTelegram('Added Series "'.$serie->TitleORG.'"'));

        return 0;
    }
}

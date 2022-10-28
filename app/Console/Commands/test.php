<?php

namespace App\Console\Commands;

use App\Models\Episodes;
use App\Models\Series;
use App\Notifications\SendTelegram;
use App\Repositories\ProxerHelper;
use App\Repositories\ProxerVideoHelper;
use App\Repositories\UrlBuilder;
use Goutte\Client;
use Illuminate\Console\Command;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use DMore\ChromeDriver\ChromeDriver;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phproxer:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $files = \Storage::disk('minio')->allFiles();
        dump($files);
//        Notification::send('',new SendTelegram('pimmel'));
//        $client = new Client();
//        $url = 'https://proxer.me/';
//        $lastEpisode = null;
//
//        $crawler = $client->request('GET', $url);
//        $loginButton = $crawler->filter("#loginNav")->text();
//        $link  = $crawler->selectLink($loginButton)->link();
//        $crawler = $client->click($link);
//
//        $form = $crawler->selectButton('Login')->form();
//        $crawler = $client->submit($form, ['username' => 'dolphinxjd', 'password' => 'carpentry-delete-violate']);
//
//        $openProfileButton = $crawler->filter("#loginNav")->text();
//        $link1  = $crawler->selectLink($openProfileButton)->link();
//        $crawler = $client->click($link1);
//
//        $userLoggedIn = $crawler->filter('#uname')->text();
//
//        dump($userLoggedIn);
//
//        $crawler = $client->request('GET', "https://proxer.me/info/74/list#top");
//
//        dump('check if page navigation exists..');
//        $data = $crawler->filter('#contentList > p:nth-child(1) > a.menu.active')->each(function ($node){
//            return $node;
//        });
//        if(empty($data)){
//            dump('navigation does not exits');
//
//        }else{
//            dump('navigation exists');
//            dump('getting last page');
//            $lastPage = $crawler->filter('#contentList > p:nth-child(1) > a')->last()->text();
//            $lastPageLink = $crawler->selectLink($lastPage)->link();
//            $crawler = $client->click($lastPageLink);
//
//        }
//        $lastEpisode = $crawler->filter('tr > td:nth-child(1)')->last()->text();
//        dump('last episode is '.$lastEpisode);


//        $crawler = $client->request('GET', "https://proxer.me/info/74/details#top");
//        dump($titleOrg);
//        $id = 3;
//        $episode = Episodes::where('id', $id)->first();
//        $seriesId = $episode->serie()->first()->ProxerId;
////
//        $urlBuilder = new UrlBuilder();
////        $client = new Client();
////        $proxer = new ProxerHelper($seriesId);
////
////        $name = $proxer->login();
////
////        dump('Logged in as '.$name);
////
//        $url = $urlBuilder->getEpisodeId($seriesId, $id);
////
////        dump($proxer->getVideoUrl($urlBuilder->getEpisodeId($seriesId, $id)));
//
//        $video = new ProxerVideoHelper();
//        $video->login();
//        $video->downloadEpisode($url, $seriesId, $episode);
//        return 0;
    }
}
#box-table-a > tbody > tr:nth-child(13) > td:nth-child(1)

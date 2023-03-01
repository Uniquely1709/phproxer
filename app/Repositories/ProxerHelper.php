<?php

namespace App\Repositories;

use Goutte\Client;

class ProxerHelper
{
    private UrlBuilder $urlBuilder;
    private Client $client;
    private string $username;
    private string $password;
    private int $seriesId;

    public function __construct(int $seriesId)
    {
        $this->urlBuilder = new UrlBuilder();
        $this->client = new Client();

        $username = config('phproxer.proxer_username');
        if (null === $username) {
            die('No valid proxer username');
        }
        $password = config('phproxer.proxer_password');
        if (null === $password) {
            die('No valid proxer username');
        }
        $this->username = $username;
        $this->password = $password;
        $this->seriesId = $seriesId;
    }

    private function sleep(): void
    {
        sleep(2);
    }

    public function login(): string|null
    {
        $crawler = $this->client->request('GET', $this->urlBuilder->baseUrl());
        $this->sleep();
        $loginButton = $crawler->filter("#loginNav")->text();
        $loginButtonLink  = $crawler->selectLink($loginButton)->link();
        $crawler = $this->client->click($loginButtonLink);
        $this->sleep();

        $form = $crawler->selectButton('Login')->form();
        $crawler = $this->client->submit($form, ['username' => $this->username, 'password' => $this->password]);
        $this->sleep();

        $openProfileButton = $crawler->filter("#loginNav")->text();
        $openProfileButtonLink  = $crawler->selectLink($openProfileButton)->link();
        $crawler = $this->client->click($openProfileButtonLink);
        $this->sleep();

        return $crawler->filter('#uname')->text();
    }

    public function getNumberOfEpisodes(): array
    {
        $crawler = $this->client->request('GET', $this->urlBuilder->getSeries($this->seriesId));
        $this->sleep();

        dump('check if page navigation exists..');

        $data = $crawler->filter('#contentList > p:nth-child(1) > a.menu.active')->each(fn ($node) => $node);

        if (empty($data)) {
            dump('navigation does not exits');
        } else {
            dump('navigation exists');
            dump('getting last page');
            $lastPage = $crawler->filter('#contentList > p:nth-child(1) > a')->last()->text();
            $lastPageLink = $crawler->selectLink($lastPage)->link();
            $crawler = $this->client->click($lastPageLink);
            $this->sleep();
        }
        $lastEpisode = $crawler->filter('tr > td:nth-child(1)')->last()->text();
        dump('last episode is '.$lastEpisode);
        return ['lastEpisode'=>$lastEpisode];
    }

    public function getOriginalTitle(): string
    {
        $crawler = $this->client->request('GET', $this->urlBuilder->getOverview($this->seriesId));
        $this->sleep();
        return $crawler->filter('#main > span > span > span')->text();
    }

    public function getEnTitle(): string
    {
        $crawler = $this->client->request('GET', $this->urlBuilder->getOverview($this->seriesId));
        $this->sleep();
        return $crawler->filter('#main > span > table > tbody > tr > td:nth-child(2) > table > tbody > tr:nth-child(2) > td:nth-child(2)')->text();
    }

    public function updateSeriesId(int $seriesId): void
    {
        $this->seriesId = $seriesId;
    }

    public function getVideoUrl(string $episodeUrl): string
    {
        $crawler = $this->client->request('GET', $episodeUrl);
        $this->sleep();
        return $crawler->filter('#wContainer')->text();
    }
}

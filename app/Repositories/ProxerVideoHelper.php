<?php

namespace App\Repositories;

use App\Models\Episodes;
use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use DMore\ChromeDriver\ChromeDriver;
use Illuminate\Support\Facades\Storage;
use TwoCaptcha\TwoCaptcha;

class ProxerVideoHelper
{
    private string $username;
    private string $password;
    private string $captchaKey;
    private Mink $mink;

    public function __construct()
    {
        $this->urlBuilder = new UrlBuilder();
        $this->mink = new Mink([
            'browser' => new Session(new ChromeDriver('http://localhost:9222', null, $this->urlBuilder->baseUrl(), [
                'chrome' => [
                    'args' => [
                        '--user-agent=Mozilla/5.0 (Windows NT 4.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36',
                    ]
                ]
            ]))
        ]);
        $this->mink->setDefaultSessionName('browser');

        $username = config('phproxer.proxer_username');
        if (null === $username) {
            die('No valid proxer username');
        }
        $password = config('phproxer.proxer_password');
        if (null === $password) {
            die('No valid proxer password');
        }
        $captchaKey = config('phproxer.proxer_2captcha_api');
        if (null === $captchaKey) {
            die('No valid captcha key');
        }
        $this->username = $username;
        $this->password = $password;
        $this->captchaKey = $captchaKey;
    }

    public function testStoredCookies(): bool
    {
        $this->mink->getSession()->visit('https://proxer.me/');

        $cookieStorage = ToolsHelper::getCookies();

        if (empty($cookieStorage)) {
            return false;
        }
        $keys = array_keys($cookieStorage);
        foreach ($keys as $key) {
            $this->mink->getSession()->setCookie($key, $cookieStorage[$key]);
        }

        $this->mink->getSession()->visit('https://proxer.me/user#top');
        $page = $this->mink->getSession()->getPage();
        sleep(3);
        //validate that cookies worked
        if ($page->find('css', '#main > div > h3')) {
            return false;
        } else {
            return true;
        }
    }

    public function storeCookies(): void
    {
        $driver = $this->mink->getSession()->getDriver();
        $cookies = [];
        foreach ($driver->getCookies() as $cookie) {
            $cookies[$cookie['name']] = $cookie['value'];
        }

        ToolsHelper::storeCookies($cookies);
    }

    public function login(): void
    {
        //check first if old cookies are still valid
        if( ! $this->testStoredCookies()) {
            $this->mink->getSession()->visit('https://proxer.me/login');
            $page = $this->mink->getSession()->getPage();


            $user = $page->findField('login_username');

            if (null !== $user) {
                //keep old ids
                $userField = 'login_username';
                $passwordField = 'login_password';
                $submitField = 'login_submit';
            } else {
                //set blocked ids
                $userField = 'mod_login_username';
                $passwordField = 'mod_login_password';
                $submitField = 'mod_login_submit';

                $this->mink->getSession()->wait(2000);
                $this->mink->getSession()->visit('https://proxer.me/misc/captcha');
                $this->mink->getSession()->wait(2000);

                $this->checkCaptcha($this->mink->getSession()->getPage(), 'https://proxer.me/misc/captcha');

                $this->mink->getSession()->visit('https://proxer.me/login');
                $page = $this->mink->getSession()->getPage();
            }

            $user = $page->findField($userField);
            $password = $page->findField($passwordField);
            $user->setValue($this->username);
            $password->setValue($this->password);
            $page->findById($submitField)->click();

            sleep(3);
            $this->storeCookies();
        }
    }

    public function getNumberOfEpisodes(int $seriesId): int
    {
        $url = $this->urlBuilder->getSeries($seriesId);
        $this->mink->getSession()->visit($url);
        sleep(2);

        $page = $this->mink->getSession()->getPage();
        $this->checkCaptcha($page, $url);

        dump('check if page navigation exists..');
        $nav = $page->find('css', '#contentList > p:nth-child(1) > a.menu.active');
        if (null === $nav) {
            dump('navigation does not exits');
        } else {
            dump('navigation exists');
            dump('getting last page');
            $elements = $page->findAll('css', '#contentList > p:nth-child(1) > a');
            end($elements)->click();
            sleep(5);
        }
        $rows = $page->findAll('css', 'tbody > tr > td:nth-child(1)');
        return end($rows)->getHtml();
    }

    public function getDownloadUrl(string $url): string|null|bool
    {
        $this->mink->getSession()->visit($url);
        sleep(2);
        $page = $this->mink->getSession()->getPage();
        $this->checkCaptcha($page, $url);
        if (str_contains($page->getOuterHtml(), 'url(/images/misc/streamfehlt.png)')) {
            dump('stream missing');
            return false;
        }
        $iframe = $page->find('css', '#wContainer > tbody > tr:nth-child(3) > td:nth-child(2) > div.wStream > iframe');
        $link = $this->getSrcFromHtml($iframe->getOuterHtml());
        if ( ! str_contains($link, 'proxer.me')) {
            dump('no proxer link available');
            return null;
        }
        $this->mink->getSession()->visit($link);
        sleep(2);
        $page = $this->mink->getSession()->getPage();
        // differentiate between old and new proxer links
        // old = https://stream.proxer.me/embed-y6gb2lniche6-728x504.html?title=Shingeki%20no%20Kyojin%20Episode%201%20EngSub&ref=/watch/5840/1/engsub
        // new = https://stream-service.proxer.me/embed-fnhzvuowomtc.html?plyr-compiled
        // the player id will differentiate
        if (str_contains($link, "stream-service.proxer.me")) {
            //new links
            $source = $page->find('css', '#player');
        } else {
            //old links
            $source = $page->find('css', '#plyr');
        }
        return $this->getSrcFromHtml($source->getHtml());
    }

    public function checkEpisodeReleased(string $url): bool
    {
        $this->mink->getSession()->visit($url);
        sleep(2);
        $page = $this->mink->getSession()->getPage();
        $this->checkCaptcha($page, $url);
        if (str_contains($page->getOuterHtml(), 'url(/images/misc/streamfehlt.png)')) {
            Logger::info('Episode is not yet released '.$url);
            return false;
        } else {
            Logger::info('Episode IS now released '.$url);
            return true;
        }
    }

    public function checkCaptcha(DocumentElement $page, string $url): void
    {
        $captcha = $page->find('named', ['id', 'captcha']);
        if (null !== $captcha) {
            dump('captcha active');
            $sitekey = $this->getDataSitekey($captcha->getOuterHtml());
            $result = $this->solveCaptcha($sitekey, $url);

            $this->mink->getSession()->evaluateScript('$("#g-recaptcha-response").css("display", "block");');
            $solveField = $page->findField('g-recaptcha-response');
            $solveField->setValue($result);


            $page->findById('checkCaptcha')->click();
            $this->mink->getSession()->visit($url);

            sleep(25);
        }
    }

    public function solveCaptcha(string $sitekey, string $url)
    {
        $solver = new TwoCaptcha($this->captchaKey);
        $result = $solver->recaptcha([
            'sitekey' => $sitekey,
            'url'     => $url,
            //            'version' => 'v3',
        ]);
        dump('Captcha solved');
        $res = ($result);
        dump($res);
        dump($res->code);

        return $res->code;
    }

    public function getDataSitekey(string $html): string
    {
        preg_match_all('/data-sitekey="([^"]*)"/', $html, $result);
        return preg_replace('/\s+/', '', $result[1][0]);
    }

    public function alternativeDownloadEpisode(string $url, int $series, Episodes $episode): bool
    {
        $this->mink->getSession()->visit($url);
        $page = $this->mink->getSession()->getPage();
        $iframe = $page->find('css', '#wContainer > tbody > tr:nth-child(3) > td:nth-child(2) > div.wStream > iframe');
        $link = $this->getSrcFromHtml($iframe->getOuterHtml());

        $this->mink->getSession()->visit($link);
        $page = $this->mink->getSession()->getPage();
        $source = $page->find('css', '#plyr');

        $videoSrc = $this->getSrcFromHtml($source->getHtml());

        return $this->download($videoSrc, $series, $episode);
    }

    private function getSrcFromHtml(string $html): string
    {
        preg_match_all('/src="([^"]*)"/', $html, $result);
        return preg_replace('/\s+/', '', $result[1][0]);
    }

    public function download(string $url, int $series, Episodes $episode): bool
    {
        $episodePath = ToolsHelper::nameBuilder($series, $episode);

        $vid = file_get_contents($url);

        return Storage::disk(config('phproxer.proxer_storage'))->put(ToolsHelper::pathBuilder($series, $episodePath), $vid);
    }
}

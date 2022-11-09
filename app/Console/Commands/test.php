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
//        $files = \Storage::disk('minio')->allFiles();
//        dump($files);
        $group = env('TELEGRAM_GROUP','');
        Notification::send('', new SendTelegram('test', $group));
    }
}

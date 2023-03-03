<?php

namespace App\Console\Commands;

use App\Notifications\SendTelegram;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

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
        Notification::send('', new SendTelegram('test'));
    }
}

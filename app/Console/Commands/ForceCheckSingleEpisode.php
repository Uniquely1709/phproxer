<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;

class ForceCheckSingleEpisode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phproxer:ForceCheckSingleEpisode {episodeId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force check and download on a single episode';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $episodeId = $this->argument('episodeId');
        Artisan::queue('phproxer:checkUnpublishedEpisodes', [
            'episodeId' => $episodeId
        ]);
        Artisan::queue('phproxer:collectOpenDownloadUrls');
        Artisan::queue('phproxer:download');
        return Command::SUCCESS;
    }
}

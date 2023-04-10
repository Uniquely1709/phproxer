<?php

namespace App\Console\Commands;

use App\Jobs\DownloadEpisode;
use App\Models\Episodes;
use App\Repositories\Logger;
use Illuminate\Console\Command;

class DownloadAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phproxer:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'downloads all open episodes with a downloadurl';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $episodes = Episodes::where('Downloaded', false)
            ->whereNot('DownloadUrl')
            ->get();
        foreach ($episodes as $episode) {
            Logger::info('queueing episodeid '.$episode->id);
            DownloadEpisode::dispatch($episode);
        }
        return 0;
    }
}

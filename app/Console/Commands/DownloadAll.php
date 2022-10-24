<?php

namespace App\Console\Commands;

use App\Models\Episodes;
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
        foreach ($episodes as $episode){
            dump('queueing episodeid '.$episode->id);
            $this->call('phproxer:downloadEpisode',[
                'id'=>$episode->id,
                '--queue' => 'downloads'
            ]);
        }
        return 0;
    }
}

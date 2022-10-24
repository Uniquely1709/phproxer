<?php

namespace App\Console\Commands;

use App\Models\Series;
use Illuminate\Console\Command;

class UpdateSeries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phproxer:updateSeries';

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
        $series  = Series::where('Completed', false)->get();

        foreach ($series as $serie){
            $episodes = $serie->episodes()->get();
            if(!$episodes->contains('Downloaded', false)){
                $serie->update(['Downloaded' => true]);
                $serie->update(['Completed' => true]);
            }
        }
        return 0;
    }
}

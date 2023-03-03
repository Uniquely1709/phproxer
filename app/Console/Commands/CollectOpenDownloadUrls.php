<?php

namespace App\Console\Commands;

use App\Models\Episodes;
use App\Repositories\ProxerVideoHelper;
use App\Repositories\UrlBuilder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CollectOpenDownloadUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phproxer:collectOpenDownloadUrls';

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
        $opens = Episodes::where('Downloaded', false)
            ->where('DownloadUrl', null)
            ->where('Retries', '<=', 5)
            ->where('Published', true)
            ->get();

        $proxer = new ProxerVideoHelper();
        $urlBuilder = new UrlBuilder();
        $proxer->login();
        foreach ($opens as $open) {
            dump($open->EpisodeID);
            dump($open->serie()->first()->ProxerId);
            $episodeId = $open->EpisodeID;
            $seriesId = $open->serie()->first()->ProxerId;
            $url = $urlBuilder->getEpisodeId($seriesId, $episodeId);
            dump($url);
            $downloadUrl = $proxer->getDownloadUrl($url);
            dump($downloadUrl);
            if (null=== $downloadUrl) {
                $open->update([
                    'Retries' => DB::raw('Retries+1'),
                ]);
            } elseif ( ! $downloadUrl) {
                $open->update([
                    'Published' => false,
                ]);
            } else {
                $open->update([
                    'DownloadUrl'=>$downloadUrl,
                    'Published'=>true,
                ]);
            }
        }

        return 0;
    }
}

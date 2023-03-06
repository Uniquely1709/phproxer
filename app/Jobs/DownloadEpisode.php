<?php

namespace App\Jobs;

use App\Models\Episodes;
use App\Models\Series;
use App\Notifications\SendTelegram;
use App\Repositories\Logger;
use App\Repositories\ToolsHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class DownloadEpisode implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public Episodes $episode;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Episodes $episode)
    {
        $this->onQueue('downloads');
        $this->episode = $episode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $id = $this->episode->id;
        $series = Series::where('id', $this->episode->series_id)->first();

        $episodePath = ToolsHelper::nameBuilder($series->ProxerId, $this->episode);

        Logger::debug('Trying to download Episode '.$this->episode->EpisodeID.' from "'.$series->Title.'"');
        $vid = file_get_contents($this->episode->DownloadUrl);

        $state = Storage::disk(config('phproxer.proxer_storage'))->put(ToolsHelper::pathBuilder($series, $episodePath), $vid);

        if ( ! $state) {
            $this->episode->update(['Retries'=>$this->episode->Retries+1]);
            Logger::error('Couldnt download Episode '.$this->episode->EpisodeID.' from "'.$series->Title);
        } else {
            $this->episode->update(['Downloaded' => true]);
            Logger::info('Downloaded Episode '.$this->episode->EpisodeID.' from "'.$series->Title.'"');
            Notification::send('', new SendTelegram('Downloaded Episode '.$this->episode->EpisodeID.' from "'.$series->Title.'"'));
        }
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Episodes;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Artisan;

class ShowEpisodes extends Component
{
    use LivewireAlert;
    use WithPagination;
    public $series;

    public function render()
    {
        $entries = Episodes::orderBy('episodes.created_at', 'desc')
            ->when($this->series, function ($query, $series): void {
                $query->whereHas('serie', function ($query) use ($series): void {
                    $query->where('TitleOrg', 'like', '%'.$series.'%');
                });
            })
            ->latest('episodes.created_at')
            ->paginate(25);
        return view('livewire.show-episodes', [
            'episodes' => $entries,
        ]);
    }

    public function forceCheck(bool $downloaded, int $episodeId): void
    {
        if ($downloaded) {
            $this->alert('info', 'This Episode is already downloaded', [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
                'timerProgressBar' => true,
            ]);
        } else {
            Artisan::queue('phproxer:ForceCheckSingleEpisode', ["episodeId"=>$episodeId]);
            $this->alert('success', 'Episode ID '.$episodeId.' queued for check', [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
                'timerProgressBar' => true,
            ]);
        }
    }
}

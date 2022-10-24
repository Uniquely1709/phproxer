<?php

namespace App\Http\Livewire;

use App\Models\Episodes;
use Livewire\Component;

class ShowEpisodes extends Component
{
    public $series;

    public function render()
    {
        $entries = Episodes::orderBy('episodes.created_at', 'desc')
            ->when($this->series, function ($query, $series){
                return $query->join('series', 'episodes.series_id', '=', 'series.id')
                                ->where('series.TitleORG','like', '%'.$series.'%');
            })
            ->latest('episodes.created_at')
            ->paginate(25);
        return view('livewire.show-episodes', [
            'episodes' => $entries,
        ]);
    }
}

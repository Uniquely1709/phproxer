<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class AddSeriesForm extends Component
{
    public $seriesId;
    public $title;
    public $season;
    protected $rules = [
        'seriesId' => 'required|int',
        'title' => 'required|string',
        'season' => 'required|int'
    ];

    public function render()
    {
        return view('livewire.add-series-form');
    }

    public function submitForm(): void
    {
        $seriesAttributes = $this->validate();
        $this->resetForm();
        session()->flash('success_message', 'We received your Series, it will be indexed shortly!');

        //todo move to async queue
        Artisan::queue('phproxer:addSeries', ['id'=>$seriesAttributes['seriesId'], 'season' => $seriesAttributes['season'], 'title' => $seriesAttributes['title']]);
        Artisan::queue('phproxer:checkUnpublishedEpisodes');
        Artisan::queue('phproxer:collectOpenDownloadUrls');
        Artisan::queue('phproxer:download');
        Artisan::queue('phproxer:updateSeries');
    }

    public function clearMessageBox(): void
    {
        session('success_message', null);
    }

    private function resetForm(): void
    {
        $this->seriesId = '';
        $this->title = '';
        $this->season = '';
    }
}

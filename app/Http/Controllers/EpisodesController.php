<?php

namespace App\Http\Controllers;

use App\Models\Episodes;
use Illuminate\Http\Request;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use function Psy\debug;

class EpisodesController extends Controller
{
    use LivewireAlert;

    public function main (Request $request){
        $series = $request->get('series');
        $episodes = Episodes::with('parent')->get();
//        dd($episodes[0]);
        return view('episodes',[
                'episodes' => $episodes,
                'series' => $series,
            ]
        );
    }
}

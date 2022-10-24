<?php

namespace App\Http\Controllers;

use App\Models\Episodes;
use Illuminate\Http\Request;

class EpisodesController extends Controller
{
    public function main (Request $request){
        $series = $request->get('series');
        $episodes = Episodes::all();
        return view('episodes',[
                'episodes' => $episodes,
                'series' => $series,
            ]
        );
    }
}

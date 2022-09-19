<?php

namespace App\Http\Controllers;

use App\Models\Episodes;
use Illuminate\Http\Request;

class EpisodesController extends Controller
{
    public function main (Request $request){
        $episodes = Episodes::all();
        return view('episodes',[
                'episodes' => $episodes,
            ]
        );
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function main (Request $request){
        $series = Series::all();
        return view('series',[
                'series' => $series,
            ]
        );
    }
}

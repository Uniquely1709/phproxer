<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;

class AddSeriesController extends Controller
{
    public function main(Request $request)
    {
        $url = $request->url;
        Artisan::queue('phproxer:addSeries', ['id'=>$url]);
        Artisan::queue('phproxer:checkUnpublishedEpisodes');
        Artisan::queue('phproxer:collectOpenDownloadUrls');
        Artisan::queue('phproxer:download');
        Artisan::queue('phproxer:updateSeries');

        return redirect()->route('series');
    }
}

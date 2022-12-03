<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogsController extends Controller
{
    public function main (Request $request){
        $level = $request->get('level');
        return view('logviewer',[
                'user_name' => Auth::user()->name,
                'level' =>$level,
            ]
        );
    }
}

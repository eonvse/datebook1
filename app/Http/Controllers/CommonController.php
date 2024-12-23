<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function start_page(){
        return view ('common/start_page');
    }
    public function team_join(){
        return view('common/team_join');
    }
}

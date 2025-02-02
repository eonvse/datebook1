<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function start_page(){
        return view ('common/start_page');
    }
    public function team_join(){
        return view('common/team_join');
    }

    public function category_materials() {
        return view('common/category_materials');
    }

    public function current_team_update(Request $request){
        $team = Team::findOrFail($request->team_id);

        if (! $request->user()->switchTeam($team)) {
            abort(403);
        }

        return redirect('category', 303);

    }
}

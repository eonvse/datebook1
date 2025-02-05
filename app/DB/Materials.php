<?php

namespace App\DB;

use App\Models\Material;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Materials {

    public static function getMaterials($categoryId = -1) {
        if ($categoryId==-1)
            return Material::where('team_id', Auth::user()->currentTeam->id)
                                    ->whereNull('material_category_id')
                                    ->orderBy('order', 'ASC')
                                    ->orderBy('name', 'ASC')
                                    ;
        else
            return Material::where('team_id', Auth::user()->currentTeam->id)
                                    ->where('material_category_id',$categoryId)
                                    ->orderBy('order', 'ASC')
                                    ->orderBy('name', 'ASC')
                                    ;

    }

}

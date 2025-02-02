<?php

namespace App\DB;

use App\Models\Material;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class Teams {

    public static function getRolesEmails(string $role): array {
        $roleArr = convertPipeToArray($role);
        $usersRoleEmail = '';
        foreach ($roleArr as $roleElement){
            $roleModel = Role::where('name','=',$roleElement)->first();
            if (empty($roleModel)) continue;
            $usersRoleEmail .= implode("|",$roleModel->users()->pluck('email')->toArray());
            $usersRoleEmail.= "|";  // add pipe for next role
        }

        return array_filter(explode("|",$usersRoleEmail));
    }

    public static function getCategoriesCurrentTeam($currentTeamId) {
        return Material::query()
        ->select(
            DB::raw('COALESCE(materials.material_category_id,-1) as category_id'),
            DB::raw('COALESCE(material_categories.name,"Без категории") as category_name'),
            DB::raw('COALESCE(material_categories.description,"-") as category_description'),
            DB::raw('count(materials.id) as materials_count'))
        ->leftJoin('material_categories','material_categories.id','materials.material_category_id')
        ->where('materials.team_id', $currentTeamId)
        ->groupBy('materials.material_category_id')
        ->orderBy('material_categories.order', 'asc')
        ->orderBy('material_categories.name', 'asc')
        ->get() ?? null;
    }

}

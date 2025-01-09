<?php

namespace App\DB;

use App\Models\Role;

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

}

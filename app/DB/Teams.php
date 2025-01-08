<?php

namespace App\DB;

use App\Models\Role;

class Teams {

    public static function getEmailsRole(?string $role): array {
        if (empty($role)) return [];
        $roleModel = Role::where('name','=',$role)->first();
        if (empty($roleModel)) return [];
        $usersRoleEmail = $roleModel->users()->pluck('email')->toArray();

        return $usersRoleEmail;
    }

}

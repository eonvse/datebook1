<?php

namespace App\Traits;

trait HasRoles
{

    public function hasRole($roles): bool
    {
        if (is_string($roles) && strpos($roles, '|') !== false) {
            $roles = convertPipeToArray($roles);
        }

        if (is_string($roles)) {
            return $this->roles->contains('name', $roles);
        }

        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }

            return false;
        }

        //throw new \TypeError('Unsupported type for $roles parameter to hasRole().');
    }



}

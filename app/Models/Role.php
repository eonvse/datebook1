<?php

namespace App\Models;

use App\Events\ActivityCompleted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = ['name', 'description'];

    public $timestamps = false;

    protected $touches = ['users'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Регистрация активности по событиям модели.
    */
    protected static function booted(): void
    {
        static::created(function (Role $role) {
            ActivityCompleted::dispatch('created',$role);
        });
        static::updated(function (Role $role) {
            ActivityCompleted::dispatch('updated',$role);
        });
        static::deleting(function (Role $role) {
            ActivityCompleted::dispatch('deleted',$role);
        });
    }
}

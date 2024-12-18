<?php

namespace App\Models;

use App\Events\ActivityCompleted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $fillable = ['name', 'color', 'info','slug'];

    //protected $touches = ['users'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function materialCategories(): HasMany
    {
        return $this->hasMany(MaterialCategory::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    public function teamJoin(): HasMany
    {
        return $this->hasMany(TeamJoin::class);
    }

    public function teamJoinUser($userId)
    {
        return $this->teamJoin()->where('user_id', $userId)->first();
    }

    /**
     * Регистрация активности по событиям модели.
    */
    protected static function booted(): void
    {
        static::created(function (Team $team) {
            ActivityCompleted::dispatch('created',$team);
        });
        static::updated(function (Team $team) {
            ActivityCompleted::dispatch('updated',$team);
        });
        static::deleting(function (Team $team) {
            ActivityCompleted::dispatch('deleted',$team);
        });
    }
}

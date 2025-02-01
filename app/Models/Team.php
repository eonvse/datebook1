<?php

namespace App\Models;

use App\Events\ActivityCompleted;
use App\Events\Team\Delete as DeleteTeam;
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
    //Действующие подписки на группу
    public function subscriptions() {
        return $this->morphMany(Mailing::class,'owner')->chaperone()->where('is_active', true);
    }

    // Проверка подписки пользователя на группу
    public function isUserSubscribed($userId) : bool {
        if (empty($this->subscriptions()->where('user_id',$userId)->first())) return false;
        return $this->subscriptions()->where('user_id',$userId)->first()->is_active;
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
            DeleteTeam::dispatch($team);
        });
    }
}

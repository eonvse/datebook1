<?php

namespace App\Models;

use App\Events\ActivityCompleted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasOne;


class TeamJoin extends Model
{
    protected $fillable = ['user_id','team_id','note'];

    public function log_statuses(): MorphMany
    {
        return $this->morphMany(LogStatus::class,'owner')->chaperone();
    }

    public function last_status(): MorphOne
    {
        return $this->morphOne(LogStatus::class, 'owner')->latestOfMany();
    }

    public function team():BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    /**
     * Регистрация активности по событиям модели.
    */
    protected static function booted(): void
    {
        static::created(function (TeamJoin $teamJoin) {
            ActivityCompleted::dispatch('created',$teamJoin);
        });
        static::updated(function (TeamJoin $teamJoin) {
            ActivityCompleted::dispatch('updated',$teamJoin);
        });
        static::deleting(function (TeamJoin $teamJoin) {
            ActivityCompleted::dispatch('deleted',$teamJoin);
        });
    }


}

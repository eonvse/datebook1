<?php

namespace App\Models;

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

}

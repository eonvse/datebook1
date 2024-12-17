<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class TeamJoin extends Model
{
    protected $fillable = ['user_id','team_id','note'];

    public function log_statuses(): MorphMany
    {
        return $this->morphMany(LogStatus::class,'owner')->chaperone();
    }

    public function status(): MorphOne
{
    return $this->morphOne(LogStatus::class, 'owner')->latestOfMany();
}
}

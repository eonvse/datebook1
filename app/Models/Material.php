<?php

namespace App\Models;

use App\Events\ActivityCompleted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Material extends Model
{
    protected $fillable = ['name','slug','order','annotation','text','team_id','material_category_id','user_id'];

    public function getRouteKeyName()
    {
        return 'slug'; // Используем поле slug для маршрутов
    }

    public function team():BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class,'material_category_id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class,'owner')->chaperone();
    }

    /* регистрация активности по событиям модели. */
    protected static function booted()
    {
        static::created(function (Material $material) {
            ActivityCompleted::dispatch('created',$material);
        });
        static::updated(function (Material $material) {
            ActivityCompleted::dispatch('updated',$material);
        });
        static::deleting(function (Material $material) {
            ActivityCompleted::dispatch('deleted',$material);
        });

    }
}

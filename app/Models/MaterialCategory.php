<?php

namespace App\Models;

use App\Events\ActivityCompleted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialCategory extends Model
{
    protected $fillable = ['name','slug','order','description','team_id','user_id'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    /**
     * Регистрация активности по событиям модели.
    */
    protected static function booted(): void
    {
        static::created(function (MaterialCategory $materialCategory) {
            ActivityCompleted::dispatch('created',$materialCategory);
        });
        static::updated(function (MaterialCategory $materialCategory) {
            ActivityCompleted::dispatch('updated',$materialCategory);
        });
        static::deleting(function (MaterialCategory $materialCategory) {
            ActivityCompleted::dispatch('deleted',$materialCategory);
        });
    }

}

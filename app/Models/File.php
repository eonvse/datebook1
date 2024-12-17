<?php

namespace App\Models;

use App\Events\ActivityCompleted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    protected $fillable = ['name', 'url','owner_type', 'owner_id','user_id'];

    // Родительская модель для File
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    // Автор
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    /* регистрация активности по событиям модели. */
    protected static function booted()
    {
        static::created(function (File $file) {
            ActivityCompleted::dispatch('created',$file);
        });
        static::updated(function (File $file) {
            ActivityCompleted::dispatch('updated',$file);
        });
        static::deleting(function (File $file) {
            ActivityCompleted::dispatch('deleted',$file);
        });

    }

}

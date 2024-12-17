<?php

namespace App\Models;

use App\Events\ActivityCompleted;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['name','description','model'];

    public $timestamps = false;

    public function getModelNameAttribute() {
        return str_replace('App\\Models\\','',$this->model);
    }

    /**
     * Регистрация активности по событиям модели.
    */
    protected static function booted(): void
    {
        static::created(function (Status $status) {
            ActivityCompleted::dispatch('created',$status);
        });
        static::updated(function (Status $status) {
            ActivityCompleted::dispatch('updated',$status);
        });
        static::deleting(function (Status $status) {
            ActivityCompleted::dispatch('deleted',$status);
        });
    }

}

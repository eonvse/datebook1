<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LogStatus extends Model
{
    protected $fillable = ['owner_type', 'owner_id', 'status_id','user_id'];

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    // Автор
    public function author()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    // Статус
    public function status()
    {
        return $this->hasOne(Status::class,'id','status_id');
    }

}

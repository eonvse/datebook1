<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mailing extends Model
{
    /**
     * Название таблицы, связанной с моделью.
     *
     * @var string
     */
    protected $table = 'mailings';

    /**
     * Поля, которые можно массово назначать.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'is_active',
        'subscribed_at',
        'owner_id',
        'owner_type',
    ];

    /**
     * Поля, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = [
        'subscribed_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Связь с моделью User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Полиморфная связь.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function owner()
    {
        return $this->morphTo();
    }

}

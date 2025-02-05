<?php

namespace App\Traits;

use App\Models\Mailing;

trait HasSubscriptions
{

    //Действующие подписки
    public function subscriptions() {
        return $this->morphMany(Mailing::class,'owner')->chaperone()->where('is_active', true);
    }

    // Проверка подписки пользователя на группу
    public function isUserSubscribed($userId) : bool {
        if (empty($this->subscriptions()->where('user_id',$userId)->first())) return false;
        return $this->subscriptions()->where('user_id',$userId)->first()->is_active;
    }

    protected static function bootHasSubscriptions()
    {
        static::deleting(function ($model) {
            // Удаляем все связанные подписки
            $model->subscriptions->each(function($subscription) {
                $subscription->delete();
            });
        });
    }



}

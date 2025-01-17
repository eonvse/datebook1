<?php

namespace App\Listeners\Team;

use App\Events\Team\UserExit;
use App\Models\TeamJoin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendUserExit
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserExit $event): void
    {
        //проверить количество групп
        $userTeamsIds = $event->user->teams()->pluck('teams.id')->toArray();
        if (count($userTeamsIds) <= 1) exit();

        //проверить группу по умолчанию. Если она = покидаемой, то сменить группу по умолчанию
        $userNewTeamsIds = array_filter($userTeamsIds, fn($value) => $value != $event->team->id );
        if ($event->team->id == $event->user->current_team_id) {
            $event->user->current_team_id = array_shift($userNewTeamsIds);
            $event->user->save();
        }

        //удалить пользователя из группы
        $event->user->teams()->detach($event->team);

        //удалить заявки на вступление в группу по пользователю и лог статусов
        TeamJoin::where('user_id','=',$event->user->id)->where('team_id','=',$event->team->id)->first()->delete();


        //лог активности ???

        //отправить уведомление о выходе пользователя ???
    }
}

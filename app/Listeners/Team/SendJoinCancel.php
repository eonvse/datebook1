<?php

namespace App\Listeners\Team;

use App\Events\Team\JoinCancel;
use App\Mail\Teams\JoinCancelled;
use App\Models\User;
use App\Models\LogStatus;
use App\Models\Status;
use App\Models\Team;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendJoinCancel
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
    public function handle(JoinCancel $event): void
    {
        $team_join = $event->teamJoin;

        //запись в журнал статуса отмены запроса на вступление в группу
        $statusCanceled =
            Status::where('name','=','cancelled')->where('model','=',$team_join::class)->get()->first() ??
                Status::create(['name'=>'cancelled','model'=>$team_join::class, 'description'=>'Заявка отклонена']);

        LogStatus::create([
            'owner_type' => $team_join::class,
            'owner_id' => $team_join->id,
            'status_id' => $statusCanceled->id,
            'user_id' => $event->author->id
        ]);

        //отправка уведомления пользователю об отмене заявки
        $joinUser = User::find($team_join->user_id);
        $joinTeam = Team::find($team_join->team_id);
        Mail::to($joinUser->email)->send(new JoinCancelled($joinTeam,$joinUser->name));

    }
}

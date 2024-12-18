<?php

namespace App\Listeners\Team;

use App\Events\Team\JoinApprove;
use App\Models\LogStatus;
use App\Models\Status;
use App\Models\User;
use App\Models\Team;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendJoinApprove
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
    public function handle(JoinApprove $event): void
    {
        $team_join = $event->teamJoin;

        //включение пользователя в запрошенную группу
        $approvedTeam = Team::find($team_join->team_id);
        $approvedUser = User::find($team_join->user_id);
        $approvedUser->teams()->attach($approvedTeam);

        //запись в журнал статуса успешного подтверждения запроса на вступление в группу
        $statusApproved =
            Status::where('name','=','approved')->where('model','=',$team_join::class)->get()->first() ??
                Status::create(['name'=>'approved','model'=>$team_join::class]);

        LogStatus::create([
            'owner_type' => $team_join::class,
            'owner_id' => $team_join->id,
            'status_id' => $statusApproved->id,
            'user_id' => $event->author->id
        ]);

    }
}

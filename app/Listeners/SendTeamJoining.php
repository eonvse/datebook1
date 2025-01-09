<?php

namespace App\Listeners;

use App\DB\Teams as TeamsDB;
use App\Events\TeamJoining;
use App\Mail\Teams\JoinCreated;
use App\Models\LogStatus;
use App\Models\Status;
use App\Models\TeamJoin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;


class SendTeamJoining
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
    public function handle(TeamJoining $event): void
    {
        $data = [];
        $data['team_id'] = $event->team->id;
        $data['user_id'] = $event->user->id;
        $data['note'] = $event->note;

        $team_join = TeamJoin::create($data);

        $statusNew =
            Status::where('name','=','new')->where('model','=',$team_join::class)->get()->first() ??
                Status::create(['name'=>'new','model'=>$team_join::class, 'description'=>'Новая заявка']);

        LogStatus::create([
            'owner_type' => $team_join::class,
            'owner_id' => $team_join->id,
            'status_id' => $statusNew->id,
            'user_id' => $event->user->id
        ]);

        //Выбор email пользователей с необходимыми ролями и отправка им сообщения
        foreach (TeamsDB::getRolesEmails('Root|Teams Admin') as $email) {
            Mail::to($email)->send(new JoinCreated($event->team,$event->user,$event->note));
        }
    }
}

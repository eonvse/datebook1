<?php

namespace App\Listeners;

use App\Events\ActivityCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\LogActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class SendActivityComleted
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
    public function handle(ActivityCompleted $event): void
    {
        $data = [];
        $data['operation'] = $event->operation;
        $data['model'] = $event->model::class;
        $data['item_id'] = $event->model->id;
        $data['item_name'] = $event->model->name;
        $data['user_id'] = Auth::check() ? Auth::user()->id : null;
        $data['user_name'] = Auth::check() ? Auth::user()->name : 'sys';
        $data['url'] = Request::fullUrl();
        $data['method'] = Request::method();
        $data['ip'] = Request::ip();
        $data['agent'] = Request::header('user-agent');

        LogActivity::create($data);
    }
}

<?php

use App\Models\TeamJoin;
use function Livewire\Volt\{state, mount};

state ('teamJoin');

mount(function(){
    $this->teamJoin = TeamJoin::all();
});
//

?>

<div>
    @foreach ($teamJoin as $join)
        <div>
           {{ $join->id}} {{ $join->user->name }} {{ $join->team->name }} {{ $join->note }} {{ $join->last_status->status->description }}
        </div>
    @endforeach
</div>
</div>

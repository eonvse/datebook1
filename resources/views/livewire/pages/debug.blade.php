<?php

use App\Models\TeamJoin;
use Laravel\Jetstream\InteractsWithBanner;

use function Livewire\Volt\{state, mount,uses};

uses(InteractsWithBanner::class);

state([
    'notifications' => false,
]);

state ('teamJoin');

mount(function(){
    $this->teamJoin = TeamJoin::all();
});

$notificationDispatch = function() {
    //$this->dispatch('banner-message', style:'danger', message: 'Заявка отправлена');
    $this->dangerBanner('zsdfgsdfgsdfg');
    //$this->redirectRoute('dashboard');
//
};
?>

<div>
    @foreach ($teamJoin as $join)
        <div>
           {{ $join->id}} {{ $join->user->name }} {{ $join->team->name }} {{ $join->note }} {{ $join->last_status->status->description }}
        </div>
    @endforeach
    <x-button.create wire:click='notificationDispatch'> Banner </x-button.create>
    <x-marker>
        marker default info
    </x-marker>
    <x-marker type="primary">
        marker type primary
    </x-marker>
    <x-marker type="secondary">
        marker type secondary
    </x-marker>
    <x-marker type="danger">
        marker type danger
    </x-marker>
    <x-marker type="gray">
        marker type gray
    </x-marker>
</div>
</div>

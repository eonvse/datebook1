<?php

use App\Models\TeamJoin;
use App\DB\Teams as TeamsDB;
use Laravel\Jetstream\InteractsWithBanner;

use function Livewire\Volt\{state, mount,uses};

uses(InteractsWithBanner::class);

state([
    'notifications' => false,
    'testRole' => '',
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
    <div>
        <x-input.text wire:model.live="testRole" />
        {{ auth()->user()->hasRole($testRole) ? '+' : '-' }}
        @foreach (TeamsDB::getEmailsRole($testRole) as $email)
            {{ $email }}
        @endforeach
    </div>
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
    <x-marker type="warning">
        marker type warning
    </x-marker>
    <x-marker type="danger">
        marker type danger
    </x-marker>
    <x-marker type="gray">
        marker type gray
    </x-marker>
    <x-marker type="success">
        marker type success
    </x-marker>

</div>


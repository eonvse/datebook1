<?php

use App\Models\Team;
use App\Events\TeamJoining;
use Laravel\Jetstream\InteractsWithBanner;
use function Livewire\Volt\{state,mount,uses};

uses(InteractsWithBanner::class);

state([
    'joinDialog' => false,
    'currentTeam' => null,
    'currentNote' => '',
]);

state('teams');

mount(function(){

    $userTeams = Auth::user()->teams->pluck('id');
    $this->teams = Team::whereNotIn('id',$userTeams)->orderBy('name','asc')->get();
});

$showModalJoin = function (Team $team) {
    $this->joinDialog = true;
    $this->currentTeam = $team;
};

$closeModalJoin = function (){
    $this->joinDialog = false;
    $this->currentTeam = null;
    $this->currentNote = '';
};

$sendJoin = function() {
    TeamJoining::dispatch(Auth::user(), $this->currentTeam, $this->currentNote);
    $this->banner('Заявка отправлена');
    $this->closeModalJoin();
}


//

?>

<div>
    @foreach ($teams as $team)
        <div class="flex space-2 items-center border-b">
            <div class="px-5">
                @if (empty($team->teamJoinUser(auth()->id())))
                <x-button.create wire:click="showModalJoin({{ $team }})">{{ __('Join') }}</x-button.create>
                @else
                <div class="text-xs">
                <x-marker.primary>{{ $team->teamJoinUser(auth()->id())->last_status->status->description ?? $team->teamJoinUser(auth()->id())->last_status->status->name }}</x-marker.primary>
                </div>
                @endif
            </div>
            <div class="my-1 w-6 min-h-6 rounded m-1" style="background-color: {{ $team->color ?? '' }}"></div>
            <div class="my-1 p-1 sm:w-1/6"><span>{{ $team->name }}</span></div>
            <div class="my-1 p-1 text-wrap flex-grow"><span>{{ $team->info }}</span></div>
        </div>
    @endforeach
    <div>Вы состоите в следующих группах</div>
    @foreach (auth()->user()->teams as $teamUser)
        <div class="flex space-2 items-center border-b">
            <div class="my-1 w-6 min-h-6 rounded m-1" style="background-color: {{ $teamUser->color ?? '' }}"></div>
            <div>{{ $teamUser->name }}</div>
            <div>{{ $teamUser->description }}</div>
        </div>
    @endforeach
    <div>
    <x-modal-wire.dialog wire:model="joinDialog" maxWidth="md">
        <x-slot name="title">
            <span class="grow">{{ __('Joining the team') }}: {{ $currentTeam->name ?? '' }}</span>
            <x-button.icon-cancel @click="show = false" wire:click="closeModalJoin" /></x-slot>
            <x-slot name="content">
                <div class="flex-col space-y-2">
                    <div class="font-bold text-xl flex">
                </div>
                <div>Комментарий
                    <x-input.textarea wire:model="currentNote" />
                </div>
                <div>
                    <x-button.create wire:click="sendJoin">Отправить заявку</x-button.create>
                    <x-button.secondary @click="show = false" wire:click="closeModalJoin">{{ __('Cancel') }}</x-button.secondary>
                </div>
            </div>
        </x-slot>
    </x-modal-wire.dialog>
    </div>
</div>

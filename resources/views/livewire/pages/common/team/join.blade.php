<?php

use App\Models\Team;
use App\Events\TeamJoining;
use App\Events\Team\UserExit;
use Laravel\Jetstream\InteractsWithBanner;
use function Livewire\Volt\{state,mount,uses};

uses(InteractsWithBanner::class);

state([
    'joinDialog' => false,
    'exitDialog' => false,
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
};

$showModalExit = function (Team $team) {
    $this->exitDialog = true;
    $this->currentTeam = $team;
};

$closeModalExit = function (){
    $this->exitDialog = false;
    $this->currentTeam = null;
};

$sendExit = function() {
    UserExit::dispatch(Auth::user(), $this->currentTeam);
    $this->banner('Вы вышли из группы '.$this->currentTeam->name);
    return redirect(request()->header('Referer'));
    //$this->closeModalExit();
}


//

?>

<div>
    <div class="font-semibold text-lg mt-2">Заявки на вступление:</div>
    @foreach ($teams as $team)
        <div class="grid grid-cols-3 md:grid-cols-6 items-center border-b">
            <div class="px-5 justify-center">
                @if (empty($team->teamJoinUser(auth()->id())))
                <div class="flex">
                    <x-button.create wire:click="showModalJoin({{ $team }})">{{ __('Join') }}</x-button.create>
                </div>
                @else
                <div class="flex">
                    @php
                        $markerSlot = $team->teamJoinUser(auth()->id())->last_status->status->description ?? $team->teamJoinUser(auth()->id())->last_status->status->name;
                        $markerType = match ($team->teamJoinUser(auth()->id())->last_status->status->name) {
                            'cancelled' => 'danger',
                            default => 'info',
                        }
                    @endphp
                    <div class="flex-none"><x-marker :type="$markerType">{{ $markerSlot }}</x-marker></div>
                </div>
                @endif
            </div>
            <div class="flex items-center my-1 p-1 md:col-span-2">
                <div class="flex-none my-1 w-6 min-h-6 rounded m-1" style="background-color: {{ $team->color ?? '' }}"></div>
                <div>{{ $team->name }}</div>
            </div>
            <div class="my-1 p-1 text-wrap flex md:col-span-3"><span>{{ $team->info }}</span></div>
        </div>
    @endforeach
    <div class="font-semibold text-lg mt-5">Вы состоите в следующих группах</div>
    @php
        $countTeams = auth()->user()->teams->count();
    @endphp
    @foreach (auth()->user()->teams as $teamUser)
        <div class="md:flex space-x-2 items-center border-b p-1">
            <div class="flex items-center">
                <div class="my-1 w-6 min-h-6 rounded m-1" style="background-color: {{ $teamUser->color ?? '' }}"></div>
                <div>{{ $teamUser->name }}</div>
            </div>
            <div class="text-neutral-500">
                {{ $teamUser->info ?? '' }}
                <span class="text-sm text-gray-400">(Материалов: {{ $teamUser->materials()->count() }})</span>
            </div>
            @if ($countTeams>1)
            <div class="grow text-right"><x-button.warning wire:click="showModalExit({{ $teamUser }})">Покинуть группу</x-button.warning></div>
            @endif
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

    <x-modal-wire.dialog wire:model="exitDialog" maxWidth="md" type="warn">
        <x-slot name="title">
            <span class="grow">{{ __('Team exit') }} {{ $currentTeam->name ?? '' }}</span>
            <x-button.icon-cancel wire:click="closeModalExit" class="text-gray-700 hover:text-white dark:hover:text-white" /></x-slot>
        <x-slot name="content">
            <div class="flex-col space-y-2">
                <x-input.label class="text-lg font-medium">Вы действительно хотите покинуть группу {{ $currentTeam->name ?? '' }}?
                    <div class="text-black dark:text-white flex items-center">
                        <div class="w-4 mx-1 {{ $delRecord->base ?? '' }} dark:{{ $delRecord->dark ?? '' }}">&nbsp;</div>
                        <div>{{ $delRecord->name ?? '' }}</div>
                    </div>
                    <div>{!! $delRecord->content ?? '' !!}</div>
                    <div class="text-red-600 dark:text-red-200 shadow p-1">{{ __('Team Exit Message') }}</div>
                </x-input.label>
                <x-button.secondary wire:click="closeModalExit">{{ __('Cancel') }}</x-button.secondary>
                <x-button.danger wire:click="sendExit()">{{ __('Exit')}}</x-button.danger>
            </div>
        </x-slot>
    </x-modal-wire.dialog>

    </div>
</div>

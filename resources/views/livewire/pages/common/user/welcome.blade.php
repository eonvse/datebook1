<?php

use function Livewire\Volt\{state};

//

?>

<div>
    <div class="flex space-x-2 items-center">
        <div class="font-semibold">{{ __('Account') }}:</div>
        <div class="text-neutral-600">{{ auth()->user()->name }}</div>
        <div class="flex ml-2 space-x-1 text-neutral-500">
            <x-mail-icon />
            <span>{{ auth()->user()->email }}</span>
        </div>
    </div>
    <div class="flex items-center sm:block pt-2">
        <div class="font-semibold">{{ __('Roles') }}</div>
        @foreach(auth()->user()->roles as $role)
        <div class="flex m-1 items-center space-x-2">
            <x-marker>{{ $role->name }}</x-marker>
            <span class="text-neutral-500 hidden sm:block">{{ $role->description }}</span>
        </div>
        @endforeach
    </div>
    <div class="flex items-center sm:block pt-2">
        <div class="font-semibold">{{ __('Teams') }}</div>
        @foreach(auth()->user()->teams as $team)
        <div class="mx-1 flex items-center sm:space-x-2">
            <div class="size-5 rounded" style="background-color: {{ $team->color ?? '' }}"></div>
            <span class="text-neutral-600 font-medium">{{ $team->name }}</span>
            <span class="text-neutral-500 hidden sm:block">{{ $team->info }}</span>
        </div>
        @endforeach
    </div>

</div>

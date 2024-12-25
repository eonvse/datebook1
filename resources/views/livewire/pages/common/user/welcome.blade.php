<?php

use function Livewire\Volt\{state};

//

?>

<div>
    <div class="flex space-x-2 items-center">
        <div class="font-semibold">{{ __('Account') }}:</div>
        <div class="text-neutral-600">{{ auth()->user()->name }}</div>
        <div class="flex ml-2 space-x-1 text-neutral-500">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
            </svg>
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

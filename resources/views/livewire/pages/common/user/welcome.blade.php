<?php

use function Livewire\Volt\{state};

//

?>

<div>
    <div class="flex items-center">
        <span>{{ __('Account') }}:</span>
        <span>{{ auth()->user()->name }}</span>
        <span class="flex m-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
            </svg>
            {{ auth()->user()->email }}
        </span>
    </div> 
    <div class="flex items-center sm:block">
        <div>{{ __('Roles') }}</div>
        @foreach(auth()->user()->roles as $role)
        <div class="flex m-1 items-center">
            <x-marker>{{ $role->name }}</x-marker>
            <span class="hidden sm:block">{{ $role->description }}</span> 
        </div>
        @endforeach
    </div>
    <div class="flex items-center sm:block">
        <div>{{ __('Teams') }}</div>
        @foreach(auth()->user()->teams as $team)
        <span class="flex items-center">
            <div class="my-1 w-6 min-h-6 rounded m-1" style="background-color: {{ $team->color ?? '' }}"></div>
            {{ $team->name }}
            <span class="hidden sm:block">{{ $team->info }}</span> 
        </span>
        @endforeach
    </div>

</div>

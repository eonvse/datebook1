<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Material show') }}
        </h2>
    </x-slot>

    <div class="text-center text-xl font-semibold p-1">{{ $material->name }}</div>
    <div class="p-3">{!! $material->text !!}</div>

</x-app-layout>

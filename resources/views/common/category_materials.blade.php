<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Materials categories') }}
        </h2>
    </x-slot>
    <div class="sm:grid md:grid-cols-6 sm:grid-cols-3">
        <div class="md:col-span-2 text-wrap">
            <livewire:pages.common.materials.category />
        </div>
        <div class="md:col-span-4 sm:col-span-2 text-wrap">
            <livewire:pages.common.materials.list />
        </div>
    </div>

</x-app-layout>

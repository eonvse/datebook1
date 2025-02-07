<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $material->category->name ?? 'Без категории' }}
            </h2>
            <div class="flex text-gray-500">
                <a href="{{ route('materials.category',['id'=>$material->category->id ?? -1]) }}">Список</a>
            </div>
        </div>
    </x-slot>

    <div class="text-center text-xl font-semibold p-1">{{ $material->name }}</div>
    <div class="p-3">{!! $material->text !!}</div>

</x-app-layout>

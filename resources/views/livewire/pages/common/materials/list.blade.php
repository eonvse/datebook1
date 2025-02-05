<?php

use App\Models\Material;
use App\Models\MaterialCategory;

use function Livewire\Volt\{state, on, mount};

state([
    'currentCategoryId' => null,
    'currentCategory'=>null,
    'materials' => array(),
]);

on(['setCategory' => function ($categoryId) {
    $this->currentCategoryId = $categoryId;
    if ($this->currentCategoryId==-1) {
        $this->materials = Material::where('team_id', auth()->user()->currentTeam->id)
                                ->whereNull('material_category_id')
                                ->orderBy('order', 'ASC')
                                ->orderBy('name', 'ASC')
                                ->get();
        $this->currentCategory = 'Без категории';
    }else{
        $this->materials = Material::where('team_id', auth()->user()->currentTeam->id)
                                ->where('material_category_id',$categoryId)
                                ->orderBy('order', 'ASC')
                                ->orderBy('name', 'ASC')
                                ->get();
        $this->currentCategory = MaterialCategory::find($categoryId);
    }
}]);
//

?>

<div class="relative w-full h-full">
    @if (is_null($currentCategory))
        <div>
            <span class="md:hidden">&#128070;</span>
            <span class="hidden md:inline-block">&#128072;</span>
            Выберите категорию</div>
    @else
    <div class="md:flex justify-between p-1">
        <h3 class="text-center text-xl font-semibold md:ml-20">{{ $currentCategory->name ?? 'Без категории' }}</h3>
        @if (!empty($currentCategory->name))
        <div class="flex space-x-1 items-center justify-end">
            <x-mail-icon />
            <x-input.switch-on-off rect=1 />
        </div>
        @endif
    </div>
    <div class="p-3 text-neutral-500 text-right">{{ $currentCategory->description ?? '' }}</div>
    @forelse ($materials as $material)
        <div class="border-b border-gray-200 dark:border-gray-700 px-4 py-2">
            <div class="">
                <div class="font-medium"><a href="{{ route('materials.show', $material->slug) }}">{{ $material->name }}</a></div>
                <div class="p-1 text-neutral-500">{{ $material->annotation }}</div>
            </div>
        </div>
    @empty
        {{ __('Not found materials') }}
    @endforelse
    @endif
    <div wire:loading>
        <x-spinner-circle />
    </div>
</div>

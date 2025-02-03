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

<div>
    @if (is_null($currentCategory))
        <div>Выберите категорию из списка слева</div>
    @else
    <h3>{{ $currentCategory->name ?? 'Без категории' }}</h3>
    <div>{{ $currentCategory->description ?? 'Категория материалов не указана' }}</div>    
    @forelse ($materials as $material)
        <div class="border-b border-gray-200 dark:border-gray-700 px-4 py-2">
            <div class="flex justify-between items-center">
                <div>{{ $material->name }}</div>
                <div>{{ $material->annotation }}</div>
            </div>
        </div>
    @empty
        {{ __('Not found materials') }}
    @endforelse
    @endif
</div>

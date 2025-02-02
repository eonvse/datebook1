<?php

use App\Models\Material;

use function Livewire\Volt\{state, on, mount};

state([
    'currentCategory' => null,
    'materials' => array(),
]);

on(['setCategory' => function ($categoryId) {
    $this->currentCategory = $categoryId;
    if ($this->currentCategory==-1) {
        $this->materials = Material::where('team_id', auth()->user()->currentTeam->id)
                                ->whereNull('material_category_id')
                                ->orderBy('order', 'ASC')
                                ->orderBy('name', 'ASC')
                                ->get();
    }else{
        $this->materials = Material::where('team_id', auth()->user()->currentTeam->id)
                                ->where('material_category_id',$categoryId)
                                ->orderBy('order', 'ASC')
                                ->orderBy('name', 'ASC')
                                ->get();
    }
}]);
//

?>

<div>
    @forelse ($materials as $material)
        1
    @empty
        {{ __('Not found materials') }}
        {{ $this->currentCategory ?? 'null' }}
    @endforelse
</div>

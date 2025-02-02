<?php

use App\DB\Teams;
use App\Models\MaterialCategory;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

use function Livewire\Volt\{state,mount};

state('categoriesCurrentTeam');
state('currentCategory');
state('currentTeamId');

mount(function(){
    $this->currentTeamId =auth()->user()->currentTeam->id;

    $this->categoriesCurrentTeam = Teams::getCategoriesCurrentTeam($this->currentTeamId);

    $this->currentCategory = $this->categoriesCurrentTeam->first()->count()>0 ? MaterialCategory::find($this->categoriesCurrentTeam->first()->material_category_id) : null;

    $sendCategoryId = $this->currentCategory->id ?? null;
    $this->setCurrentCategory($sendCategoryId);

});

$setCurrentCategory = function($categoryId) {

    $this->currentCategory = MaterialCategory::find($categoryId) ?? null;

    $this->dispatch('setCategory', categoryId: $this->currentCategory);

    $this->categoriesCurrentTeam = Teams::getCategoriesCurrentTeam($this->currentTeamId);

};

//

?>

<div class="bg-neutral-50 p-2 rounded-md">
    {{ $currentCategory->name ?? '-1' }}
    @forelse ($categoriesCurrentTeam as $category)
        <div class="cursor-pointer m-1 p-1 hover:font-semibold hover:shadow-md hover:bg-white rounded-md" wire:click="setCurrentCategory({{ $category->category_id }})">
            {{  $category->category_name }}
            <span class="text-gray-400">({{ $category->materials_count }})</span>
        </div>
    @empty
        {{ __('Not found') }}
    @endforelse
</div>

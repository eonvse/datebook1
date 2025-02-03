<?php

use App\DB\Teams;
use App\Models\MaterialCategory;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

use function Livewire\Volt\{state,mount};

state('currentTeamId');
state('categoriesCurrentTeam');
state(['currentCategoryId'=>null]);

mount(function(){
    $this->currentTeamId =auth()->user()->currentTeam->id;

    $this->categoriesCurrentTeam = Teams::getCategoriesCurrentTeam($this->currentTeamId);

});

$setCurrentCategory = function($categoryId) {

    $this->currentCategoryId = $categoryId;

    $this->dispatch('setCategory', categoryId: $this->currentCategoryId);

    $this->categoriesCurrentTeam = Teams::getCategoriesCurrentTeam($this->currentTeamId);

};

//

?>

<div class="w-full h-full">
    <div class="bg-neutral-50 p-2 rounded-md">
    @forelse ($categoriesCurrentTeam as $category)
        <div class="relative cursor-pointer m-1 p-1 {{ $category->category_id == $currentCategoryId ? 'font-semibold shadow-md bg-white' : '' }} hover:font-semibold hover:shadow-md hover:bg-white rounded-md" wire:click="setCurrentCategory({{ $category->category_id }})">
            {{  $category->category_name }}
            <span class="text-gray-400">({{ $category->materials_count }})</span>
            <div wire:loading wire:target="setCurrentCategory({{ $category->category_id }})">
                <x-spinner-circle />
            </div>
        </div>
    @empty
        {{ __('Not found') }}
    @endforelse
    </div>
</div>

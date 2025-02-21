<?php

use App\DB\Materials as MaterialsDB;
use App\Models\Material;
use App\Models\MaterialCategory;
use App\Events\Subscribe\Activate as ActivateSubscribe;
use App\Events\Subscribe\Disable as DisableSubscribe;

use Laravel\Jetstream\InteractsWithBanner;
use Livewire\WithoutUrlPagination;

use function Livewire\Volt\{state, on, mount, with, usesPagination, uses, updated};

usesPagination();
uses(WithoutUrlPagination::class);
uses(InteractsWithBanner::class);

state([
    'currentCategory'=>null,
    'search'=>'',
]);

state('currentCategoryId');
state('pagination');
state('perPage');

mount(function ($idCategory=null) {
    $this->currentCategoryId = $idCategory;
    if ($this->currentCategoryId>0) $this->currentCategory = MaterialCategory::where('team_id',auth()->user()->current_team_id)
                                                            ->where('id',$this->currentCategoryId)->first();
    elseif (is_numeric($this->currentCategoryId)) $this->currentCategory = 'Без категории';
    $this->pagination = config('app.pagination',[['id'=> '10','name' => '10']]);
    $this->perPage = config('app.perPage',10);
});

with(fn () => ['materials' => MaterialsDB::getMaterials($this->currentCategoryId)
                                ->when($this->search, function ($query) {
                                    $query->where(function($query) {
                                        $query->where('name', 'like', '%' . $this->search . '%')
                                              ->orWhere('annotation', 'like', '%' . $this->search .'%');
                                    });
                                })
                                ->paginate($this->perPage === 'all' ? Material::count() : $this->perPage)]);

on(['setCategory' => function ($categoryId) {
    $this->resetPage();
    $this->reset('search');
    $this->dispatch('search-reset');
    $this->currentCategoryId = $categoryId;
    if ($this->currentCategoryId==-1) $this->currentCategory = 'Без категории';
    else $this->currentCategory = MaterialCategory::find($categoryId);
}]);

on(['search-updated' => fn($search) => $this->updateSearch($search)]);

updated(['perPage' => fn () => $this->resetPage()]);

$subscribe = function () {
    $user = auth()->user();
    $category = $this->currentCategory;
    if (!$category->isUserSubscribed($user->id)) {
        ActivateSubscribe::dispatch($user,$category);
        $this->banner('Вы подписаны на рассылку email уведомлений по категории '.$category->name);
    }else {
        DisableSubscribe::dispatch($user,$category);
        $this->banner('Вы отписаны от рассылки email уведомлений по категории '.$category->name);
    }

};

$updateSearch = function ($search) {
    $this->search = $search;
    $this->resetPage(); // Сброс пагинации при изменении поиска
};

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
            <x-input.switch-on-off rect=1 checked="{{ $currentCategory->isUserSubscribed(auth()->user()->id) }}" wire:click="subscribe()" />
        </div>
        @endif
    </div>
    <div class="p-3 text-neutral-500 text-right">{{ $currentCategory->description ?? '' }}</div>
    <div class="py-2 md:flex items-center">
        <div>Сортировка</div>
        <div class="grow"><livewire:search-box /></div>
        <div class="md:flex-none md:w-16">
            <x-input.select :items="$pagination" none="false" wire:model.live="perPage" />
        </div>
    </div>
    {{ $materials->links() }}
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
    {{ $materials->links() }}
    @endif
    <div wire:loading>
        <x-spinner-circle />
    </div>
</div>

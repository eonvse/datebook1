<?php

namespace App\Livewire;

use Livewire\Component;

class SearchBox extends Component
{
    public $search = ''; // Переменная для хранения поискового запроса

    protected $listeners = ['search-reset' => 'resetSearch']; // слушаем событие очистки поиска из родительского компонента
    // Очистка поискового запроса
    public function resetSearch()
    {
        $this->search = '';
    }

    public function clearSearch()
    {
        if (strlen($this->search)>0) {
            $this->resetSearch();
            $this->dispatch('search-updated', search: $this->search); // Отправка события
        }
    }

    // Обновление поискового запроса
    public function updatedSearch()
    {
        $this->dispatch('search-updated', search: $this->search); // Отправка события
    }

    public function render()
    {
        return view('livewire.search-box');
    }
}

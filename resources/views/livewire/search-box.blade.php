<div>
    <div class="flex items-center">
        <!-- Поле поиска -->
        <input
            type="text"
            wire:model.live.debounce.500ms="search"
            placeholder="Поиск..."
            class="px-4 py-2 border rounded-l focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <!-- Кнопка очистки -->
        <button
            wire:click="clearSearch"
            class="px-4 py-2 bg-gray-200 border border-l-0 rounded-r hover:bg-gray-300 focus:outline-none"
        >
            &times;
        </button>
    </div>
</div>

<div>
    <div class="relative">
        <!-- Поле поиска -->
        <x-input.text
            wire:model.live.debounce.500ms="search"
            placeholder="Поиск по [названию или аннотации TODO FieldSet]..."
        />
        <!-- Кнопка очистки -->
        <x-button.icon-clear class="absolute top-1 right-1" title="{{ __('Clear field') }}" wire:click="clearSearch" />
    </div>
</div>

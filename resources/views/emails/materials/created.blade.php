<x-mail::message>
# Новый материал в категории

В категорию "{{ $categoryName }}" добавлен новый материал "{{ $materialName }}".
@if (!empty($materialAnnotation))
Аннотация: {{ $materialAnnotation }}
@endif

<x-mail::button :url="{{ $materialLink }}">
Открыть на сайте
</x-mail::button>

Отправлено автоматически,<br>
{{ config('app.name') }}
</x-mail::message>

<x-mail::message>
# {{ $teamName }} : {{ $categoryName }}

В категорию "{{ $categoryName }}" добавлен новый материал "{{ $materialName }}".
@if (!empty($materialAnnotation))
## Аннотация:
<x-mail::panel>
{{ $materialAnnotation }}
</x-mail::panel>
@endif

<x-mail::button :url="$materialLink" color="success">
Открыть на сайте
</x-mail::button>

Отправлено автоматически + поясниловка об отмене и прочем...
</x-mail::message>

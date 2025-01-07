<div>
    <div>Пользователь {{ $user->name }}({{ $user->email }}) хочет вступить в групу {{ $team->name }}: {{ $team->info }}.</div>
    @if (!empty($note))
    <div>
        Примечание к заявке: {{ $note }}
    </div>
    @endif
</div>

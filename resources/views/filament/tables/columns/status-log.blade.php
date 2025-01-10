<div class="text-xs">
    @foreach ($getRecord()->log_statuses()->orderBy('created_at','desc')->get() as $log)
        <div>{{ $log->created }}: {{ $log->author->name }} - {{ $log->status->description }}</div>
    @endforeach
</div>
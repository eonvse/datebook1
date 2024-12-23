@props(['type'])

@php
$type = $type ?? 'info';
$types = [
    'info' => 'bg-sky-200 text-black',
    'primary' => 'bg-blue-500 text-white',
    'secondary' => 'bg-yellow-200 text-black',
    'danger' => 'bg-rose-200 text-black',
    'gray' => 'bg-gray-200 text-black',
];
$css = isset($types[$type]) ? $types[$type] : $types['info'];
@endphp

<span class="{{ $css }} rounded p-1">{{ $slot }}</span>

@props(['type'])

@php
$type = $type ?? 'info';
$types = [
    'info' => 'bg-sky-50 text-sky-600 border border-sky-100',
    'primary' => 'bg-blue-50 text-blue-600 border border-blue-100',
    'danger' => 'bg-red-50 text-red-600 border border-red-100',
    'gray' => 'bg-gray-200 text-black',
    'success' => 'bg-teal-50 text-teal-600 border border-teal-100',
    'warning' => 'bg-amber-50 text-amber-600 border border border-amber-100',
];
$css = isset($types[$type]) ? $types[$type] : $types['info'];
@endphp

<span class="{{ $css }} rounded-md p-1 text-xs">{{ $slot }}</span>

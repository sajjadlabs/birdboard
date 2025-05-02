@props(['name', 'type' => 'text', 'label'])

@php
    $default = [
        'class' => 'block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6',
        'type'  => $type,
        'name'  => $name,
        'id'    => $name,
    ]
@endphp

@if ($label ?? false)
    <x-label :$name :$label/>
@endif

<x-input-field>
    <input {{ $attributes($default) }}>
</x-input-field>

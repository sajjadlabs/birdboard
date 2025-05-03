@props(['name', 'label'])

@php
    $default = [
        'class' => 'block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-muted focus:outline-none sm:text-sm/6',
        'name'  => $name,
        'id'    => $name,
        'rows' => '3'
    ]
@endphp

@if ($label ?? false)
    <x-label :$name :$label/>
@endif

<x-input-field>
    <textarea {{ $attributes($default) }}>{{ $slot }}</textarea>
</x-input-field>

<x-error :$name/>

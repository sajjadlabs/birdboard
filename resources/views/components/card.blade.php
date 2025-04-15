@props(['clickable'])

@php
$class = "p-5 bg-white rounded-md shadow-sm border border-transparent hover:border-gray-200";
@endphp

@if ($clickable ?? false)
    <a {{ $attributes(compact('class')) }}>
        {{ $slot }}
    </a>
@else
    <div {{ $attributes(compact('class')) }}>
        {{ $slot }}
    </div>
@endif

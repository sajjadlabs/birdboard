@props(['clickable'])

@php
$class = "p-5 flex flex-col bg-card rounded-md shadow-sm border border-transparent hover:border-muted";
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

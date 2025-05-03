@props(['active'])

@php
$class =    ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-default focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-default hover:border-blue-500 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes(compact('class')) }}>
    {{ $slot }}
</a>

@props(['callToAction', 'value', 'button', 'dropdown'])

@php
    $class = "flex cursor-pointer items-center justify-center px-3 py-2 border border-transparent text-sm leading-4 font-semibold rounded-md focus:outline-none transition ease-in-out duration-150";
    $class .= ($callToAction ?? false) ? ' bg-indigo-600 font-semibold px-3 py-2 text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600': ' bg-white text-gray-500 hover:text-gray-700';

    $default = compact('class');
@endphp

@if ($button ?? false)
    <button {{ $attributes($default) }}>
        {{ $value }}

        @if ($dropdown ?? false)
            <div class="ms-1">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                          clip-rule="evenodd"/>
                </svg>
            </div>
        @endif
    </button>

@else
    <a {{ $attributes($default) }} >{{ $value }}</a>
@endif




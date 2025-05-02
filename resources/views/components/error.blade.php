@props(['name', 'bag'])

@if($errors->{$bag ?? 'default'}->any())
    <span class="text-sm text-red-500">{{ $errors->{$bag ?? 'default'}->first($name) }}</span>
@endif

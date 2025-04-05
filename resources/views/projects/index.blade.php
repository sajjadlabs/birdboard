<x-layout>
    @forelse($projects as $project)
        <a href="{{ $project->path() }}">
            <h3>{{ $project->title }}</h3>
        </a>
    @empty
        <li>Hooray! Index is empty!</li>
    @endforelse
</x-layout>

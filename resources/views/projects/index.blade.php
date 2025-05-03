<x-layout>
    <x-breadcrumb>
        <span>Projects</span>

        <x-button href="{{ route('projects.create') }}" value="Create Project" :call-to-action="true"/>
    </x-breadcrumb>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($projects as $project)
            <x-card :clickable="true" href="{{ $project->path() }}">
                <x-card-heading>{{ $project->title }}</x-card-heading>
                <p class="h-content mb-auto">{{ str($project->description)->limit(50) }}</p>

                @can('manage', $project)
                    <form class="w-auto" action="{{ $project->path() }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <div class="text-right w-auto">
                            <button class="text-red-400 hover:text-red-500 transition-colors duration-300 text-sm cursor-pointer rounded-md" type="submit">Delete</button>
                        </div>
                    </form>
                @endcan
            </x-card>
        @empty
            <x-card class="col-span-full">
                <p class="row-span-full cursor-default">No projects yet</p>
            </x-card>
        @endforelse
    </div>
</x-layout>

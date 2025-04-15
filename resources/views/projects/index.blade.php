<x-layout>
    <x-breadcurmb>
        <span>Projects</span>

        <x-button href="{{ route('projects.create') }}" value="Create Project" :call-to-action="true"/>
    </x-breadcurmb>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($projects as $project)
            <x-card :clickable="true" href="{{ $project->path() }}">
                <x-card-heading>{{ str($project->title)->headline()->words(10)}}</x-card-heading>
                <p class="h-content">{{ str($project->description)->limit(50) }}</p>
            </x-card>
        @empty
            <x-card class="col-span-full">
                <p class="row-span-full cursor-default">No projects yet</p>
            </x-card>
        @endforelse
    </div>
</x-layout>

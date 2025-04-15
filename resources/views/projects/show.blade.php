<x-layout>
    <h2>{{ $project->title }}</h2>
    <p>{{ $project->description }}</p>

    @foreach($project->tasks as $task)
    <div>{{ $task->body }}</div>
    @endforeach
</x-layout>


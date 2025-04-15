<x-layout>
    <div>
        <x-breadcurmb>
            <div>
                <a href="{{ route('projects') }}">Projects</a> > {{ $project->title }}
            </div>
        </x-breadcurmb>
    </div>
    <div class="flex flex-col-reverse gap-10 md:grid md:grid-cols-3">
        <div class="col-span-1 md:col-span-2 space-y-10">
            <!--Tasks-->
            <div class="space-y-4">
                <x-section-heading>Tasks</x-section-heading>

                @foreach($project->tasks as $task)
                    <x-card>
                        <form class="-m-5 p-5" method="POST" action="{{ $task->path() }}">
                            @method('PATCH')
                            @csrf

                            <div class="flex items-center">
                            <input type="text" value="{{ $task->body }}" class="w-full focus:outline-none {{ $task->completed ? 'text-gray-400' : '' }}" name="body">

                            <input type="checkbox" name="completed" class="cursor-pointer w-4 h-4"
                                   onChange="this.form.submit()" {{ $task->completed ? 'checked' : ''}}>
                            </div>
                        </form>
                    </x-card>
                @endforeach
                <x-card>
                    <form action="{{ $project->path() . '/tasks' }}" method="POST">
                        @csrf

                        <input type="text" placeholder="Add new task" class="w-full focus:outline-none" name="body">
                    </form>
                </x-card>
            </div>

            {{--General notes--}}
            <div class="space-y-4">
                <x-section-heading>General Notes</x-section-heading>

                <x-card>
                    General notes...
                </x-card>
            </div>
        </div>

        <!--Sidebar-->
        <div>
            <x-card>
                <x-card-heading>{{ str($project->title)->words(10)}}</x-card-heading>
                <p class="h-content">{{ str($project->description)->limit(50) }}</p>
            </x-card>
        </div>

    </div>
</x-layout>


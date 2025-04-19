<x-layout>
    <div>
        <x-breadcrumb>
            <a href="{{ route('projects') }}">Projects</a> > {{ $project->title }}

            <x-button href="{{ route('projects.edit', compact('project')) }}" value="Edit Project"
                      class="ml-auto"
                      :call-to-action="true"/>
        </x-breadcrumb>
    </div>
    <div class="flex flex-col-reverse gap-10 md:grid md:grid-cols-3">
        <div class="col-span-1 md:col-span-2 space-y-10">
            <!-- Tasks -->
            <div class="space-y-4">
                <x-section-heading>Tasks</x-section-heading>

                @foreach($project->tasks as $task)
                    <x-card>
                        <form class="-m-5 p-5" method="POST" action="{{ $task->path() }}">
                            @method('PATCH')
                            @csrf

                            <div class="flex items-center">
                                <input type="text" value="{{ $task->body }}"
                                       class="w-full focus:outline-none {{ $task->completed ? 'text-gray-400' : '' }}"
                                       name="body">

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

            <!-- General Notes -->
            <div class="space-y-4">
                <x-section-heading>General Notes</x-section-heading>
                <form method="POST" action="{{ $project->path() }}">
                    @method('PATCH')
                    @csrf()

                    <x-card class="mb-4">
                        <textarea name="notes" id="notes" cols="30" rows="1"
                                  class="w-full resize-none overflow-hidden grow focus:outline-none"
                                  oninput="onGrow(this)">{{ $project->notes }}</textarea>
                    </x-card>

                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <p class="text-sm text-red-600">{{ $error }}</p>
                        @endforeach
                    @endif

                    <x-button button call-to-action type="submit"
                              class="mt-3"
                              value="Save"/>

                </form>

                <!-- Auto resize and scroll of general notes -->
                <script>
                    let notes = document.getElementById('notes');
                    let isInitialLoad = true;

                    notes.addEventListener('input', () => onGrow(notes));
                    window.addEventListener('load', () => {
                        onGrow(notes);
                        isInitialLoad = false;
                    });

                    let onGrow = (element) => {
                        element.style.height = 'auto';
                        element.style.height = element.scrollHeight + 'px';

                        if (!isInitialLoad) {
                            scrollToTextareaBottom()
                        }
                    }

                    let scrollToTextareaBottom = () => {
                        let rect = notes.getBoundingClientRect();
                        let bottomY = window.scrollY + rect.bottom;
                        let viewportBottom = window.scrollY;

                        if (bottomY > viewportBottom) {
                            window.scrollTo({
                                top: document.body.scrollHeight,
                                behavior: 'auto'
                            })
                        }
                    }
                </script>
            </div>
        </div>

        <!--Sidebar-->
        <div>
            <x-card>
                <x-card-heading>{{ str($project->title)->words(10)}}</x-card-heading>
                <p class="h-content">{{ str($project->description)->limit() }}</p>
            </x-card>
        </div>

    </div>
</x-layout>


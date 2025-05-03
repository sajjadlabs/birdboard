<x-layout>
    <div>
        <x-breadcrumb>
            <span>
                <a href="{{ route('projects') }}">Projects</a> > {{ $project->title }}
            </span>

            <div class="flex items-center ml-auto">
                @foreach($project->members as $member)
                    <img class="w-8 mr-2 rounded-full" src="{{ gravatar_url($member->email) }}"
                         alt="{{ $member->name }}'s avatar">
                @endforeach

                <img class="w-8 rounded-full" src="{{ gravatar_url($project->owner->email) }}"
                     alt="{{ $project->owner->name }}'s avatar">


                <x-button href="{{ route('projects.edit', compact('project')) }}" value="Edit Project"
                          class="ml-4"
                          :call-to-action="true"/>
            </div>
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
                                       class="w-full bg-card focus:outline-none {{ $task->completed ? 'text-muted' : '' }}"
                                       name="body"
                                       autocomplete="off">

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

                    <x-button button call-to-action type="submit"
                              class="mt-3"
                              value="Save"/>

                </form>

                <x-error name="notes"/>

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
        <div class="space-y-4">
            <x-card href="{{ $project->path() }}">
                <x-card-heading>{{ $project->title }}</x-card-heading>
                <p class="h-content mb-auto">{{ str($project->description) }}</p>

                @can('manage', $project)
                    <form class="w-auto" action="{{ $project->path() }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <div class="text-right w-auto">
                            <button
                                class="text-red-400 hover:text-red-500 transition-colors duration-300 text-sm cursor-pointer rounded-md"
                                type="submit">Delete
                            </button>
                        </div>
                    </form>
                @endcan()
            </x-card>

            <x-card>
                @foreach($project->activities as $activity)
                    <li class="{{ $loop->last ? '' : 'mb-1' }} text-sm list-none">
                        @include("projects.activity.{$activity->description}")
                        <span class="text-muted">{{ $activity->created_at->diffForHumans(null, true) }}</span>
                    </li>
                @endforeach
            </x-card>

            @can('manage', $project)
                <x-card href="{{ $project->path() }}">
                    <x-card-heading>Invite a user</x-card-heading>
                    <form class="w-auto" action="{{ $project->path() . '/invitations' }}" method="POST">
                        @csrf

                        <div class="text-left w-auto space-y-2">
                            <x-input name="email" class="bg-page" placeholder="Enter user email address" autocomplete="off"/>
                            <div>
                                <x-error name="email" bag="invitation"/>
                            </div>
                            <x-button button type="submit" call-to-action value="Invite"/>
                        </div>
                    </form>
                </x-card>
            @endcan
        </div>

    </div>
</x-layout>


<x-layout>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4 items-start">
        <div class="md:col-span-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3  gap-4">
            @forelse($projects as $project)
                <a href="{{ $project->path() }}" class="p-5 col-span-1 bg-white grid grid-rows-3 rounded-md shadow-sm border border-transparent hover:border-gray-200">
                    <h3 class="text-xl border-l-2 border-blue-500 -ml-[calc(20px+1px)] pl-5 text-bold mb-4 row-span-2">{{ str($project->title)->headline()->words(10)}}</h3>
                    <p class="h-content">{{ str($project->description)->limit(50) }}</p>
                </a>
            @empty
                <div class="p-5 col-span-1 bg-white rounded-md shadow-sm border border-transparent hover:border-gray-200 flex flex-col">Hooray! Index is empty!</div>
            @endforelse
        </div>
        <div class="col-span-1 bg-white p-5 rounded-md shadow-sm">Sidebar</div>
    </div>
</x-layout>

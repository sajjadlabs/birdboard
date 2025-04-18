@csrf
<div class="border-b border-gray-900/10 pb-5">

    <x-page-heading class="text-center sm:text-left">{{ $heading }}</x-page-heading>

    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-6">
        <div class="sm:col-span-4">
            <x-input label="Title" name="title" value="{{ $project->title ?? '' }}"/>
        </div>
        <div class="col-span-full">
            <x-textarea label="Description" name="description" value="{{ $project->description ?? '' }}"/>
        </div>

        <div class="col-span-full">
            <x-textarea label="Notes" name="notes" value="{{ $project->notes ?? '' }}"/>
        </div>
    </div>
</div>

<x-layout>
    <form method="POST" action="{{ $project->path() }}">
        @method('PATCH')
        @include('_form', ['heading' => 'Edit Project'])

        <div class="mt-5 flex justify-end gap-x-6">
            <x-button :href="$project->path()" class="flex-1 sm:flex-0" value="Cancel"/>
            <x-button :button="true" class="flex-1 sm:flex-0" value="Update" callToAction type="submit"/>
        </div>
    </form>
</x-layout>

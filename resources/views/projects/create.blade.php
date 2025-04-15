<x-layout>
    <form method="POST" action="/projects">
        @csrf
        <div class="border-b border-gray-900/10 pb-5">

            <x-page-heading class="text-center sm:text-left">Create Project</x-page-heading>

            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <x-input label="Title" name="title"/>
                </div>
                <div class="col-span-full">
                    <x-textarea label="Description" name="description"/>
                </div>
            </div>
        </div>

        <div class="mt-5 flex justify-end gap-x-6">
            <x-button href="/projects" class="flex-1 sm:flex-0" value="Cancel"/>
            <x-button :button="true" class="flex-1 sm:flex-0" value="Create" callToAction type="submit"/>
        </div>
    </form>
</x-layout>

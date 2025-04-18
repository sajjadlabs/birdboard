<x-layout>
    <form method="POST" action="/projects">
        @include('_form', ['heading' => 'Create Project'])

        <div class="mt-5 flex justify-end gap-x-6">
            <x-button href="/projects" class="flex-1 sm:flex-0" value="Cancel"/>
            <x-button :button="true" class="flex-1 sm:flex-0" value="Create" callToAction type="submit"/>
        </div>
    </form>
</x-layout>

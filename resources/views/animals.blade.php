<x-layouts.main>
    <x-container class="py-6">
        <h2 class="font-semibold text-xl text-zinc-900 leading-tight">{{ $title }}</h2>
    </x-container>
    @livewire('animal.animals-list', ['route' => $route, 'onlyAvailableForAdoption' => $onlyAvailableForAdoption])
</x-layouts.main>

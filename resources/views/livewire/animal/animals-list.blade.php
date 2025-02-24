<div>
    <div>
        <select wire:model="specie">
            <option value="">Selecione uma espécie</option>
            <option value="dog">Cachorro</option>
            <option value="cat">Gato</option>
        </select>

        <select wire:model="size">
            <option value="">Selecione um tamanho</option>
            <option value="sm">Pequeno</option>
            <option value="md">Médio</option>
            <option value="lg">Grande</option>
        </select>

        <select wire:model="gender">
            <option value="">Selecione um sexo</option>
            <option value="m">Macho</option>
            <option value="f">Fêmea</option>
        </select>

        <button wire:click="applyFilters">Filtrar</button>
        <button wire:click="clearFilters">Limpar</button>
    </div>

    <x-container>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($animals as $animal)
                <div>
                    <a href="{{ route($route, $animal->slug) }}">
                    <img 
                        src="{{ $animal->getFirstMediaUrl('animals', 'responsive') ?: asset('images/animal-placeholder.jpg') }}"    
                        alt="{{ $animal->name }}" 
                        loading="lazy" 
                        class="mb-4 rounded-md">

                        {{ $animal->name }}</a>
                    </a>
                    <p class="text-sm text-zinc-800">{{ $animal->specie->getLabel() }}</p> 
                </div>
            @endforeach
        </div>
    </x-container>
</div>

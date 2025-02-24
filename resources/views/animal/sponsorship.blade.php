<x-layouts.main>
    <x-container class="py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 mb-6 lg:mb-20 lg:gap-x-18">
            <div class="relative">
                <div class="sticky top-4">
                    <img                         
                        src="{{ $animal->getFirstMediaUrl('animals', 'responsive') ?: asset('images/animal-placeholder.jpg') }}"    
                        alt="{{ $animal->name }}"
                        loading="lazy" 
                        class="mb-4 rounded-md"/>
                </div>
            </div>

            <div>
                <h1 class="font-semibold text-xl lg:text-3xl text-zinc-900 leading-tight mb-2">{{ $animal->name }}</h1>

                @if (!empty($animal->short_description))
                    <div class="py-4">{{ $animal->short_description }}</div>
                @endif

                @livewire('animal.sponsorshipForm', ['animal' => $animal] )
            </div>
        </div>

        <div>

        <div>
                @if ($animal->activeExpenses()->exists())
                    <h2 class="font-medium mb-2">Saiba quais são as minhas despesas</h2>
                    <dl class="divide-y divide-zinc-200">
                        @foreach ($animal->activeExpenses as $expense)
                            <div class="px-2 py-4 sm:grid sm:grid-cols-2  sm:px-0">
                                <dt class="text-gray-700">{{ $expense->type->getLabel() }}

                                </dt>
                                <dd class="mt-1 font-medium text-gray-900 sm:col-span-1 sm:mt-0 grid grid-cols-2">
                                <div>
                                    {{ $expense->amount }} <span class="text-sm text-zinc-500 ms-2">/ {{ $expense->recurrence_days->getLabel() }}</span></div>
                                @if ($expense->sponsorships()->exists())
                                    <p class="text-green-600">Apadrinhada</p>
                                @else 
                                    <p class="text-red-600">Não apadrinhada</p>
                                @endif
                                </dd>
                                
                                @if (!empty($expense->description))
                                    <p class="col-span-2 text-sm text-zinc-500">
                                        {{ $expense->description }}
                                    </p>
                                @endif 

                                
                            </div>
                        @endforeach
                    </dl>
                @endif
            </div>
            
            <h2 class="font-medium mb-2">Saiba mais sobre {{ $animal->genderedName }}</h2>

            <x-description-list class="mb-8">
                    <x-description-list-item term="Espécie" value="{{ $animal->specie->getLabel() }}" />
                    <x-description-list-item term="Sexo" value="{{ $animal->gender->getLabel() ?? 'Não avaliado' }}" />
                    <x-description-list-item term="Porte" value="{{ $animal->size->getLabel() ?? 'Não avaliado' }}" />
                    <x-description-list-item term="Idade estimada" value="{{ $animal->age ?? 'Não avaliado' }}" />
                    
                    @if (!empty($animal->intake_date))
                        <x-description-list-item term="No abrigo desde" value="{{ $animal->intakeYear ?? 'Não avaliado' }}" />
                    @endif

                <x-description-list-item term="Sociável com gatos" value="{{ $animal->sociable_with_cats?->getLabel() ?? 'Não avaliado' }}" />
                <x-description-list-item term="Sociável com cães" value="{{ $animal->sociable_with_dogs?->getLabel() ?? 'Não avaliado' }}" />
                <x-description-list-item term="Sociável com crianças" value="{{ $animal->sociable_with_children?->getLabel() ?? 'Não avaliado' }}" />
                <x-description-list-item term="Castrado" value="{{ $animal->is_neutered ? 'Sim' : 'Não' }}" />
                
                @if ($animal->temperament_labels)
                    <x-description-list-item term="Temperamentos" value="{{ $animal->temperament_labels }}" />
                @endif

                @if ($animal->health_conditions_labels)
                    <x-description-list-item term="Condições de saúde" value="{{ $animal->health_conditions_labels }}" />
                @endif
                
                @if (!empty($animal->special_needs))
                    <x-description-list-item 
                        term="Necessidades especiais" 
                        value="{{ is_array($animal->special_needs) ? implode(', ', $animal->special_needs) : $animal->special_needs }}" 
                    />
                @endif
            </x-description-list>

            @if ($animal->full_description)
                <h2 class="font-medium mb-2">Um pouco da minha história</h2>

                {{ $animal->full_description }}
            @endif
        </div>
    </x-container>
</x-layouts.main>

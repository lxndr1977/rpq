<div>
    @if (!$formSubmitted)
    
        @if ($animal->availableExpenses()->exists())
            <h2 class="font-medium mb-4">Qual despesa você gostaria de apadrinhar?</h2>
                <fieldset class="space-y-4 mb-4">
                    <legend class="sr-only">Despesas</legend>
                    @foreach ($animal->availableExpenses as $expense)
                        <div>
                            <label
                                for="expense-{{ $expense->id }}"
                                class="grid grid-cols-2 cursor-pointer items-center justify-between gap-4 rounded-lg border border-gray-100 bg-white p-4 text-sm font-medium shadow-xs hover:border-gray-200 has-[:checked]:border-fuchsia-500 has-[:checked]:ring-1 has-[:checked]:ring-fuchsia-500"
                            >
                                <p class="text-gray-700">{{ $expense->type->getLabel() }}</p>
                                <p class="text-gray-900 text-end">
                                   <span class="text-lg"> {{ $expense->amount }} </span> / {{ $expense->recurrence_days->getLabel() }}
                                </p>

                                <input
                                    type="radio"
                                    name="DeliveryOption"
                                    value="{{ $expense->id }}"
                                    id="expense-{{ $expense->id }}"
                                    class="sr-only"
                                    wire:model="expenseId"
                                    wire:click="changePaymentOptions()"
                                />
                                @if (!empty($expense->description))
                                    <p class="col-span-2 text-sm text-zinc-500">
                                        {{ $expense->description }}
                                    </p>
                                @endif 
                            </label>
                            
                        </div>
                    @endforeach
                    @error('expenseId') 
                        <span class="text-sm text-red-600">{{ $message }}</span> 
                    @enderror 
                </fieldset>

            <!-- Campos do usuário -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                <input type="text" id="name" wire:model="name" class="mt-1 p-2 border w-full rounded-md">
                @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" id="email" wire:model="email" class="mt-1 p-2 border w-full rounded-md">
                @error('email') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="whatsapp" class="block text-sm font-medium text-gray-700">WhatsApp</label>
                <input type="text" id="whatsapp" wire:model="whatsapp" class="mt-1 p-2 border w-full rounded-md">
                @error('whatsapp') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Botão de submissão -->

            <a 
                class="block mb-15 rounded-md cursor-pointer bg-fuchsia-600 px-3.5 py-3 w-full text-center text-md font-semibold text-white shadow-xs hover:bg-fuchsia-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-fuchsia-600"
                wire:click="save">
                Apadrinhe {{ $animal->genderedName }}
            </a>
        @else
            <p>Despesas apadrinhadas :)</p>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 text-red-600 font-semibold">
                {{ session('error') }}
            </div>
        @endif


    @else
        <!-- Mensagem de agradecimento -->
        <div class="p-6 bg-white rounded-md shadow-lg text-center">
            <h2 class="text-xl font-semibold text-gray-700">Obrigado por seu apoio!</h2>
            <p class="text-gray-600 mt-2">Sua contribuição faz a diferença para os nossos animais.</p>

            <!-- Botão para pagamento -->
            <a 
                href="{{ $paymentLink }}" 
                target="_blank" 
                class="mt-4 inline-block rounded-md bg-green-600 px-4 py-2 text-white font-semibold shadow-md hover:bg-green-500">
                Ir para pagamento
            </a>
        </div>
    @endif
</div>

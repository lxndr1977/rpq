<?php

namespace App\Livewire\Animal;

use App\Models\User;
use Livewire\Component;
use App\Models\Animal\Expense;
use Livewire\Attributes\Validate;
use App\Models\Animal\Sponsorship;
use Illuminate\Support\Facades\DB;

class SponsorshipForm extends Component
{
    public $animal = [];

    // Campos do formulário
    #[Validate('required')]
    public $expenseId;

    #[Validate('required')]
    public $name;

    #[Validate('required|email')]
    public $email;

    #[Validate('required')]
    public $whatsapp;

    public $paymentLink = null; // Link de pagamento
    public $formSubmitted = false; // Controla a exibição do formulário

    public function render()
    {
        return view('livewire.animal.sponsorship-form');
    }

    public function changePaymentOptions()
    {
        $this->resetErrorBag();
    }

    public function save()
    {
        // Valida os campos do formulário
        $this->validate();

        try {
            DB::beginTransaction(); // Inicia a transação

            // Verifica se o usuário já existe ou cria um novo
            $user = User::firstOrCreate(
                ['email' => $this->email], 
                [
                    'name' => $this->name,
                    'whatsapp' => $this->whatsapp,
                    'password' => bcrypt('default_password'),
                ]
            );

            // Obtém a despesa selecionada
            $expense = Expense::findOrFail($this->expenseId);

            // Verifica se já existe um apadrinhamento para este usuário e despesa
            $existingSponsorship = Sponsorship::where('user_id', $user->id)
                ->where('expense_id', $this->expenseId)
                ->exists();

            if ($existingSponsorship) {
                DB::rollBack(); // Reverte qualquer alteração feita na transação
                session()->flash('error', 'Você já está apadrinhando esta despesa.');
                return;
            }

            // Cria o Sponsorship com o valor correto
            Sponsorship::create([
                'user_id' => $user->id,
                'expense_id' => $this->expenseId,
                'amount' => $expense->amount,
            ]);

            DB::commit(); // Confirma a transação, salvando os dados no banco

            // Obtém o link de pagamento
            $this->paymentLink = $expense->payment_link;

            // Oculta o formulário e exibe a mensagem de agradecimento
            $this->formSubmitted = true;

        } catch (\Exception $e) {
            DB::rollBack(); // Reverte qualquer alteração se houver erro
            session()->flash('error', 'Ocorreu um erro ao processar seu apadrinhamento. Tente novamente.');
            return;
        }
    }
}

<?php

namespace App\Observers;

use App\Models\Animal\Sponsorship;

class SponsorshipObserver
{
    public function created(Sponsorship $sponsorship): void
    {
        $this->updateExpenseTotal($sponsorship);
    }

    public function updated(Sponsorship $sponsorship): void
    {
        $this->updateExpenseTotal($sponsorship);
    }

    public function deleted(Sponsorship $sponsorship): void
    {
        $this->updateExpenseTotal($sponsorship);
    }

    public function restored(Sponsorship $sponsorship): void
    {
        $this->updateExpenseTotal($sponsorship);
    }

    public function forceDeleted(Sponsorship $sponsorship): void
    {
        $this->updateExpenseTotal($sponsorship);
    }

    protected function updateExpenseTotal(Sponsorship $sponsorship)
    {
        $expense = $sponsorship->expense; 
        $total = $expense->sponsorships()->active()->sum('amount'); 

        $expense->update(['total_sponsorship' => $total]); 
    }

}

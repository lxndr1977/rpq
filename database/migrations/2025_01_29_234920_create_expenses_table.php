<?php

use App\Enums\Animal\ExpenseTypeEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Enums\Animal\ExpenseRecurrenceEnum;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->enum('type', array_column(ExpenseTypeEnum::cases(), 'value'));
            $table->string('description')->nullable();
            $table->decimal('amount', 10, 2);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('recurrence_days', array_column(ExpenseRecurrenceEnum::cases(), 'value'))->nullable();
            $table->json('payment_links')->nullable();
            $table->foreignId('animal_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};

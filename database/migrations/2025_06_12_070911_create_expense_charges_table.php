<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expense_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('expense_item_id')->constrained();
            $table->foreignId('accounting_period_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('model');
            $table->unsignedBigInteger('amount_in_cents');
            $table->timestamp('charged_at');
            $table->unique(['team_id', 'expense_item_id', 'accounting_period_id', 'model_type', 'model_id', 'charged_at'], 'expense_charges_unique');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_charges');
    }
};

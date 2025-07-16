<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expense_charges', function (Blueprint $table) {
            $table->foreignId('deal_id')->nullable()->constrained('deals');
        });
    }

    public function down(): void
    {
        Schema::table('expense_charges', function (Blueprint $table) {
            if (Schema::hasColumn('expense_charges', 'deal_id')) {
                $table->dropForeign(['deal_id']);
                $table->dropColumn('deal_id');
            }
        });
    }
};

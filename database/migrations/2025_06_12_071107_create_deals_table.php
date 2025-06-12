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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_department_id')->constrained();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('deal_id')->nullable()->constrained();
            $table->string('name');
            $table->string('status');
            $table->unsignedBigInteger('amount_in_cents');
            $table->string('code');
            $table->string('reference')->nullable();
            $table->unsignedSmallInteger('success_rate')->default(0);
            $table->timestamp('ordered_at');
            $table->unsignedSmallInteger('duration_in_months');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->virtualAs('DATE_ADD(starts_at, INTERVAL duration_in_months MONTH)');
            $table->json('schedule');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};

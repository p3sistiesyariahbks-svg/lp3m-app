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
        Schema::create('registrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->constrained('users', 'id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignUuid('period_id')
                ->constrained('periods', 'id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignUuid('placement_id')
                ->nullable()
                ->constrained('placements', 'id')
                ->nullOnUpdate()
                ->nullOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'period_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};

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
        Schema::create('placements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('period_id')
                ->constrained('periods', 'id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignUuid('location_id')
                ->constrained('locations', 'id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignUuid('supervisor_id')
                ->nullable()
                ->constrained('users', 'id')
                ->nullOnUpdate()
                ->nullOnDelete();
            $table->unsignedInteger('quota')->default(0);
            $table->unsignedInteger('filled')->default(0);
            $table->userstampsUuid();
            $table->timestamps();

            $table->unique(['period_id', 'location_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placements');
    }
};

<?php

use App\Enums\DegreeLevel;
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
        Schema::create('study_programs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('department_id')
                ->constrained('departments', 'id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('degree', DegreeLevel::cases());
            $table->boolean('is_active')->default(true);
            $table->userstampsUuid();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_programs');
    }
};

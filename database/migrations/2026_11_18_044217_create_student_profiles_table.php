<?php

use App\Enums\Gender;
use App\Enums\LivingWith;
use App\Enums\Religion;
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
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->unique()
                ->constrained('users', 'id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignUuid('study_program_id')
                ->constrained('study_programs', 'id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('name');
            $table->string('nik')->unique();
            $table->string('nim')->unique();
            $table->enum('gender', Gender::cases());
            $table->string('birth_place');
            $table->date('birth_date');
            $table->enum('religion', Religion::cases())->nullable();
            $table->text('address');
            $table->enum('living_with', LivingWith::cases());
            $table->string('living_with_other')->nullable();
            $table->userstampsUuid();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};

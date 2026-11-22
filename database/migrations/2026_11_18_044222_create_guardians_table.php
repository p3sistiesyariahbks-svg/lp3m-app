<?php

use App\Enums\EducationLevel;
use App\Enums\GuardianRelation;
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
        Schema::create('guardians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->constrained('users', 'id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->enum('relation', GuardianRelation::cases());
            $table->string('name')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('education', EducationLevel::cases())->nullable();
            $table->string('occupation')->nullable();
            $table->text('address')->nullable();
            $table->userstampsUuid();
            $table->timestamps();

            $table->unique(['user_id', 'relation']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardians');
    }
};

<?php

use App\Enums\ProgramType;
use App\Enums\StatusType;
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
        Schema::create('periods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->enum('program', ProgramType::cases());
            $table->timestamp('program_starts_at');
            $table->timestamp('program_ends_at');
            $table->timestamp('registration_opens_at');
            $table->timestamp('registration_closes_at');
            $table->unsignedBigInteger('fee')->default(0);
            $table->enum('status', StatusType::cases())->default(StatusType::Draft);
            $table->timestamp('published_at')->nullable();
            $table->userstampsUuid();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periods');
    }
};

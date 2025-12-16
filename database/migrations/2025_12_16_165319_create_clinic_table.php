<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->cascadeOnDelete();

            $table->foreignId('clinic_id')
                ->constrained('clinics')
                ->cascadeOnDelete();

            $table->timestamp('starts_at');
            $table->timestamp('ends_at');

            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });

        DB::statement("ALTER TABLE appointments ADD CONSTRAINT chk_appointments_times CHECK (ends_at > starts_at)");
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

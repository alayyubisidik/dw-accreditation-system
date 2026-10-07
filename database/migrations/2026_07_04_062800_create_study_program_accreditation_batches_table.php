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
        Schema::create('study_program_accreditation_batches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('study_program_accreditation_agency_id')
                ->constrained('study_program_accreditation_agencies', indexName: 'sp_batch_agency_fk')
                ->cascadeOnDelete();

            $table->string('name');
            $table->year('year');
            $table->boolean('is_active')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_program_accreditation_batches');
    }
};

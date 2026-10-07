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
        Schema::create('study_program_indicator_templates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('study_program_accreditation_agency_id')
                ->constrained('study_program_accreditation_agencies', indexName: 'sp_templ_agency_fk')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('formula_type', ['percentage', 'ratio']);
            $table->string('label_1')->nullable();
            $table->string('label_2')->nullable();
            $table->decimal('target', 10, 2)->nullable();
            $table->string('gap_unit', 50)->nullable();
            $table->text('strategic_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_program_indicator_templates');
    }
};

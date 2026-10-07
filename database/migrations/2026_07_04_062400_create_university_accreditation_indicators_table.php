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
        Schema::create('university_accreditation_indicators', function (Blueprint $table) {
            $table->id();

            $table->foreignId('university_accreditation_id')
                ->constrained('university_accreditations', indexName: 'uni_indic_accred_fk')
                ->cascadeOnDelete();

            $table->foreignId('university_indicator_template_id')
                ->constrained('university_indicator_templates', indexName: 'uni_indic_templ_fk')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('formula_type', ['percentage', 'ratio']);
            $table->string('label_1')->nullable();
            $table->string('label_2')->nullable();
            $table->decimal('target', 10, 2)->nullable();
            $table->text('strategic_reason')->nullable();
            $table->decimal('value_1', 15, 2)->nullable();
            $table->decimal('value_2', 15, 2)->nullable();
            $table->decimal('result', 15, 2)->nullable();
            $table->decimal('target_absolute', 15, 2)->nullable();
            $table->decimal('gap', 15, 2)->nullable();
            $table->string('gap_unit', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('university_accreditation_indicators');
    }
};

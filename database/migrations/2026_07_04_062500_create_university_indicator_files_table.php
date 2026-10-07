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
        Schema::create('university_indicator_files', function (Blueprint $table) {
            $table->id();

            $table->foreignId('university_accreditation_indicator_id')
                ->constrained('university_accreditation_indicators', indexName: 'uni_file_indic_fk')
                ->cascadeOnDelete();

            $table->unique('university_accreditation_indicator_id', 'uni_file_indic_unique');

            $table->string('original_name');
            $table->string('file_name');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size');

            $table->foreignId('uploaded_by')
                ->constrained('users', indexName: 'uni_file_uploader_fk')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('university_indicator_files');
    }
};

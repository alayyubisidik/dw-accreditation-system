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
        Schema::create('university_accreditations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('university_accreditation_batch_id')
                ->constrained('university_accreditation_batches', indexName: 'uni_accred_batch_fk')
                ->cascadeOnDelete();

            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('university_accreditations');
    }
};

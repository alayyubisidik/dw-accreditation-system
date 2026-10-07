<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('study_program_accreditation_indicators', function (Blueprint $table) {
            $table->foreignId('study_program_indicator_template_id')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('study_program_accreditation_indicators', function (Blueprint $table) {
            $table->foreignId('study_program_indicator_template_id')
                ->nullable(false)
                ->change();
        });
    }
};

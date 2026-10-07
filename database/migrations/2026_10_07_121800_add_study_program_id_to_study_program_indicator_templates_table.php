<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tambah kolom study_program_id (nullable) ke study_program_indicator_templates.
     * NULL  = template umum (berlaku untuk semua prodi peserta).
     * terisi = template khusus prodi tersebut saja.
     * Data lama tidak berubah (otomatis NULL = umum).
     */
    public function up(): void
    {
        Schema::table('study_program_indicator_templates', function (Blueprint $table) {
            $table->foreignId('study_program_id')
                ->nullable()
                ->after('study_program_accreditation_agency_id')
                ->constrained('study_programs', indexName: 'sp_templ_study_program_fk')
                ->nullOnDelete();

            $table->index('study_program_id', 'sp_templ_study_program_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('study_program_indicator_templates', function (Blueprint $table) {
            $table->dropForeign('sp_templ_study_program_fk');
            $table->dropIndex('sp_templ_study_program_idx');
            $table->dropColumn('study_program_id');
        });
    }
};

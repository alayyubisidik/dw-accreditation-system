<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('university_accreditations', function (Blueprint $table) {
            if (! Schema::hasColumn('university_accreditations', 'university_accreditation_agency_id')) {
                $table->foreignId('university_accreditation_agency_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('university_accreditation_agencies', indexName: 'uni_accred_agency_fk')
                    ->restrictOnDelete();
            }

            if (! Schema::hasColumn('university_accreditations', 'name')) {
                $table->string('name')->nullable()->after('university_accreditation_agency_id');
            }

            if (! Schema::hasColumn('university_accreditations', 'year')) {
                $table->year('year')->nullable()->after('name');
            }

            if (! Schema::hasColumn('university_accreditations', 'description')) {
                $table->text('description')->nullable()->after('status');
            }
        });

        if (Schema::hasTable('university_accreditation_batches') && Schema::hasColumn('university_accreditations', 'university_accreditation_batch_id')) {
            DB::table('university_accreditations')
                ->orderBy('id')
                ->each(function ($accreditation) {
                    $batch = DB::table('university_accreditation_batches')
                        ->where('id', $accreditation->university_accreditation_batch_id)
                        ->first();

                    if (! $batch) {
                        return;
                    }

                    DB::table('university_accreditations')
                        ->where('id', $accreditation->id)
                        ->update([
                            'university_accreditation_agency_id' => $batch->university_accreditation_agency_id,
                            'name' => $batch->name,
                            'year' => $batch->year,
                            'description' => $batch->description,
                        ]);
                });
        }

        Schema::table('university_accreditations', function (Blueprint $table) {
            if (Schema::hasColumn('university_accreditations', 'university_accreditation_agency_id')) {
                $table->unsignedBigInteger('university_accreditation_agency_id')->nullable(false)->change();
            }

            if (Schema::hasColumn('university_accreditations', 'year')) {
                $table->year('year')->nullable(false)->change();
            }

            if (Schema::hasColumn('university_accreditations', 'university_accreditation_batch_id')) {
                $table->dropForeign('uni_accred_batch_fk');
                $table->dropColumn('university_accreditation_batch_id');
            }
        });

        Schema::dropIfExists('university_accreditation_batches');
    }

    public function down(): void
    {
        Schema::create('university_accreditation_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_accreditation_agency_id')
                ->constrained('university_accreditation_agencies', indexName: 'uni_batch_agency_fk')
                ->cascadeOnDelete();
            $table->string('name');
            $table->year('year');
            $table->boolean('is_active')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('university_accreditations', function (Blueprint $table) {
            $table->foreignId('university_accreditation_batch_id')
                ->nullable()
                ->after('id')
                ->constrained('university_accreditation_batches', indexName: 'uni_accred_batch_fk')
                ->cascadeOnDelete();
        });

        DB::table('university_accreditations')
            ->orderBy('id')
            ->each(function ($accreditation) {
                $batchId = DB::table('university_accreditation_batches')->insertGetId([
                    'university_accreditation_agency_id' => $accreditation->university_accreditation_agency_id,
                    'name' => $accreditation->name ?? 'Akreditasi Universitas '.$accreditation->year,
                    'year' => $accreditation->year,
                    'is_active' => $accreditation->status === 'in_progress',
                    'description' => $accreditation->description,
                    'created_at' => $accreditation->created_at,
                    'updated_at' => $accreditation->updated_at,
                ]);

                DB::table('university_accreditations')
                    ->where('id', $accreditation->id)
                    ->update(['university_accreditation_batch_id' => $batchId]);
            });

        Schema::table('university_accreditations', function (Blueprint $table) {
            $table->unsignedBigInteger('university_accreditation_batch_id')->nullable(false)->change();
            $table->dropForeign('uni_accred_agency_fk');
            $table->dropColumn([
                'university_accreditation_agency_id',
                'name',
                'year',
                'description',
            ]);
        });
    }
};

<?php

namespace App\Services;

use App\Models\StudyProgramAccreditation;
use App\Models\StudyProgramAccreditationBatch;
use App\Models\StudyProgramAccreditationIndicator;
use App\Models\StudyProgramIndicatorTemplate;
use Illuminate\Support\Facades\DB;

class StudyProgramBatchEnrollmentService
{
    /**
     * Daftarkan satu atau lebih prodi ke dalam batch.
     *
     * Untuk setiap prodi:
     * 1. Cegah duplikat pasangan (batch, prodi) yang sudah ada.
     * 2. Buat study_program_accreditations (status: in_progress).
     * 3. Generate indikator dari template milik agency batch tersebut
     *    DAN (study_program_id IS NULL ATAU = prodi tsb).
     *
     * Seluruh operasi dibungkus dalam satu DB transaction.
     *
     * @param  StudyProgramAccreditationBatch $batch
     * @param  array<int>                     $studyProgramIds
     * @return void
     */
    public function enroll(StudyProgramAccreditationBatch $batch, array $studyProgramIds): void
    {
        if (empty($studyProgramIds)) {
            return;
        }

        DB::transaction(function () use ($batch, $studyProgramIds) {

            // Prodi yang sudah terdaftar di batch ini — untuk mencegah duplikat
            $existingIds = StudyProgramAccreditation::query()
                ->where('study_program_accreditation_batch_id', $batch->id)
                ->whereIn('study_program_id', $studyProgramIds)
                ->pluck('study_program_id')
                ->all();

            $newStudyProgramIds = array_diff($studyProgramIds, $existingIds);

            if (empty($newStudyProgramIds)) {
                return;
            }

            $now = now();

            foreach ($newStudyProgramIds as $studyProgramId) {

                // Buat akreditasi untuk prodi ini
                $accreditation = StudyProgramAccreditation::create([
                    'study_program_accreditation_batch_id' => $batch->id,
                    'study_program_id'                     => $studyProgramId,
                    'status'                               => 'in_progress',
                ]);

                // Ambil template beserta field snapshot: milik agency batch ini + (umum ATAU khusus prodi ini)
                $templates = StudyProgramIndicatorTemplate::query()
                    ->where('study_program_accreditation_agency_id', $batch->study_program_accreditation_agency_id)
                    ->forStudyProgram($studyProgramId)
                    ->get(['id', 'title', 'description', 'formula_type', 'label_1', 'label_2', 'target', 'gap_unit', 'strategic_reason']);

                if ($templates->isNotEmpty()) {
                    DB::table('study_program_accreditation_indicators')->insert(
                        $templates->map(fn ($tpl) => [
                            'study_program_accreditation_id'      => $accreditation->id,
                            'study_program_indicator_template_id' => $tpl->id,
                            'title'                               => $tpl->title,
                            'description'                         => $tpl->description,
                            'formula_type'                        => $tpl->formula_type,
                            'label_1'                             => $tpl->label_1,
                            'label_2'                             => $tpl->label_2,
                            'target'                              => $tpl->target,
                            'gap_unit'                            => $tpl->gap_unit,
                            'strategic_reason'                    => $tpl->strategic_reason,
                            'created_at'                          => $now,
                            'updated_at'                          => $now,
                        ])->all()
                    );
                }
            }
        });
    }
}

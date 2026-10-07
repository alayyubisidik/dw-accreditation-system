<?php

namespace App\Services;

use App\Models\Accreditation;
use App\Models\StudyProgram;
use Illuminate\Support\Facades\DB;

class AccreditationCreationService
{
    public function createWithIndicators(array $attributes): Accreditation
    {
        return DB::transaction(function () use ($attributes) {
            $accreditation = Accreditation::create($attributes);

            $templateIds = StudyProgram::query()
                ->findOrFail($accreditation->study_program_id)
                ->indicatorTemplates()
                ->orderBy('indicator_templates.display_order')
                ->pluck('indicator_templates.id');

            if ($templateIds->isNotEmpty()) {
                $now = now();

                DB::table('accreditation_indicators')->insert(
                    $templateIds
                        ->map(fn($templateId) => [
                            'accreditation_id' => $accreditation->id,
                            'indicator_template_id' => $templateId,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ])
                        ->all()
                );
            }

            return $accreditation;
        });
    }
}

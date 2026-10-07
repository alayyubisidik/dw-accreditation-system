<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UniversityAccreditationIndicator extends Model
{
    protected $fillable = [
        'university_accreditation_id',
        'university_indicator_template_id',
        'title',
        'description',
        'formula_type',
        'label_1',
        'label_2',
        'target',
        'strategic_reason',
        'value_1',
        'value_2',
        'result',
        'target_absolute',
        'gap',
        'gap_unit',
    ];

    protected function casts(): array
    {
        return [
            'target'          => 'decimal:2',
            'value_1'         => 'decimal:2',
            'value_2'         => 'decimal:2',
            'result'          => 'decimal:2',
            'target_absolute' => 'decimal:2',
            'gap'             => 'decimal:2',
        ];
    }

    public function accreditation(): BelongsTo
    {
        return $this->belongsTo(UniversityAccreditation::class, 'university_accreditation_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(UniversityIndicatorTemplate::class, 'university_indicator_template_id');
    }

    public function file(): HasOne
    {
        return $this->hasOne(UniversityIndicatorFile::class);
    }
}

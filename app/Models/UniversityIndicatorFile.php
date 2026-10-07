<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UniversityIndicatorFile extends Model
{
    protected $fillable = [
        'university_accreditation_indicator_id',
        'original_name',
        'file_name',
        'file_path',
        'file_size',
        'uploaded_by',
    ];

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(UniversityAccreditationIndicator::class, 'university_accreditation_indicator_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

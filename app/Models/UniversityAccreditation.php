<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UniversityAccreditation extends Model
{
    protected $fillable = [
        'university_accreditation_batch_id',
        'status',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(UniversityAccreditationBatch::class, 'university_accreditation_batch_id');
    }

    public function indicators(): HasMany
    {
        return $this->hasMany(UniversityAccreditationIndicator::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateSchool extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'school_id',
        'degree_id',
        'major',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Filter extends Model
{
    use HasFactory;

    protected $fillable = ['recruitment_candidate_id', 'work_location', 'salary', 'result', 'note'];

    public function recruitment_candidate(): BelongsTo
    {
        return $this->belongsTo(RecruitmentCandidate::class);
    }
}

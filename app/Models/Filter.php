<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Filter extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruitment_candidate_id',
        'work_location',
        'salary',
        'reviewer_id',
        'reviewer_result',
        'reviewer_comment',
        'approver_id',
        'approver_result',
        'approver_comment',
    ];

    public function recruitment_candidate(): BelongsTo
    {
        return $this->belongsTo(RecruitmentCandidate::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

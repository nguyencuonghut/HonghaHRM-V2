<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecruitmentCandidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruitment_id',
        'candidate_id',
        'cv_file',
        'channel_id',
        'batch',
        'creator_id',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

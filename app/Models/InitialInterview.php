<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InitialInterview extends Model
{
    use HasFactory;

    protected $fillable = [
                            'recruitment_candidate_id',
                            'health_comment',
                            'health_score',
                            'attitude_comment',
                            'attitude_score',
                            'stability_comment',
                            'interviewer_id',
                            'stability_score',
                            'total_score',
                            'result',
                        ];

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

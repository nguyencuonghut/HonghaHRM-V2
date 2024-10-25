<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FirstInterviewResult extends Model
{
    use HasFactory;

    protected $fillable = ['recruitment_candidate_id', 'result', 'interviewer_id'];

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

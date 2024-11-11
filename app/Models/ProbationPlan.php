<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProbationPlan extends Model
{
    use HasFactory;

    protected $fillable = ['probation_id', 'work_title', 'work_requirement', 'work_deadline', 'instructor', 'work_result'];

    public function probation(): BelongsTo
    {
        return $this->belongsTo(Probation::class);
    }
}

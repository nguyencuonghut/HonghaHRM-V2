<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Probation extends Model
{
    use HasFactory;


    protected $fillable = [
        'employee_id',
        'recruitment_id',
        'creator_id',
        'start_date',
        'end_date',
        'plan_review_status',
        'result_of_work',
        'result_of_attitude',
        'result_manager_status',
        'result_reviewer_status',
        'result_review_time',
        'result_reviewer_id',
        'approver_id',
        'approver_result',
        'approver_comment',
        'approver_time',
    ];

    public function probation_plans(): HasMany
    {
        return $this->hasMany(ProbationPlan::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function result_reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

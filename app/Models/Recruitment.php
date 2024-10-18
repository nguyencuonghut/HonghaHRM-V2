<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Recruitment extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'quantity',
        'reason',
        'requirement',
        'salary',
        'work_time',
        'note',
        'status',
        'creator_id',
        'reviewer_id',
        'reviewer_result',
        'reviewed_time',
        'reviewer_comment',
        'approver_id',
        'approver_result',
        'approver_comment',
        'approved_time',
        'completed_time',
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): HasOne
    {
        return $this->hasOne(Plan::class);
    }

}

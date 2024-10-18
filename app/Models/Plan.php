<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruitment_id',
        'budget',
        'creator_id',
        'approver_id',
        'approver_result',
        'approver_comment',
        'status',
    ];

    public function methods(): BelongsToMany
    {
        return $this->belongsToMany(Method::class, 'plan_methods', 'plan_id', 'method_id')->withTimestamps();;
    }

    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}

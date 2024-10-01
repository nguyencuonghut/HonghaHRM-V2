<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'department_id',
        'division_id',
        'insurance_salary',
        'position_salary',
        'max_capacity_salary',
        'position_allowance',
        'recruitment_standard_file',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }
}

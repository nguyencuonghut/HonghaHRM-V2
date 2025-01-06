<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSchool extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'employee_id',
        'degree_id',
        'major',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}

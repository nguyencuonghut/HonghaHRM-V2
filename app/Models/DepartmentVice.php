<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartmentVice extends Model
{
    use HasFactory;

    protected $fillable = ['department_id', 'vice_id'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function vice(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}

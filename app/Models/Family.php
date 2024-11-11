<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Family extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'name', 'job', 'year_of_birth', 'type', 'is_living_together', 'health', 'situation'];


    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}

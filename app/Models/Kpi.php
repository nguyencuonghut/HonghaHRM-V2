<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kpi extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'year', 'month', 'score'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}

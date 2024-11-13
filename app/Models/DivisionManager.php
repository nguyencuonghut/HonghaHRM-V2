<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DivisionManager extends Model
{
    use HasFactory;

    protected $fillable = ['division_id', 'manager_id'];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}

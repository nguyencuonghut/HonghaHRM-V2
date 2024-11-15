<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Division extends Model
{
    use HasFactory;

    protected $fillable = ['department_id', 'name'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function division_manager(): HasOne
    {
        return $this->hasOne(DivisionManager::class);
    }
}

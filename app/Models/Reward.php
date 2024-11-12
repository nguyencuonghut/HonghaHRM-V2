<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'code', 'sign_date', 'content', 'note'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}

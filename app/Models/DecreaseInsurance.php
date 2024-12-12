<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DecreaseInsurance extends Model
{
    use HasFactory;

    protected $fillable = ['work_id', 'confirmed_month'];

    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class);
    }
}

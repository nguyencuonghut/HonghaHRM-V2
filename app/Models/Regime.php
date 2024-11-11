<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Regime extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'regime_type_id', 'off_start_date', 'off_end_date', 'payment_period', 'payment_amount', 'status'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function regime_type(): BelongsTo
    {
        return $this->belongsTo(RegimeType::class);
    }
}

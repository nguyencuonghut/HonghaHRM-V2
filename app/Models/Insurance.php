<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Insurance extends Model
{
    use HasFactory;


    protected $fillable = ['employee_id', 'insurance_type_id', 'start_date', 'end_date', 'pay_rate'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function insurance_type(): BelongsTo
    {
        return $this->belongsTo(InsuranceType::class);
    }
}

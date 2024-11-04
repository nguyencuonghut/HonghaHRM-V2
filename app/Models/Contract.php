<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'employee_id',
        'position_id',
        'contract_type_id',
        'file_path',
        'status',
        'start_date',
        'end_date',
        'request_terminate_date'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function contract_type(): BelongsTo
    {
        return $this->belongsTo(ContractType::class);
    }
}

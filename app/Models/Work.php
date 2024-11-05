<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Work extends Model
{
    use HasFactory;

    protected $fillable = ['contract_code', 'employee_id', 'position_id', 'status', 'start_date', 'end_date', 'on_type_id','off_type_id', 'off_reason'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function on_type(): BelongsTo
    {
        return $this->belongsTo(OnType::class);
    }

    public function off_type(): BelongsTo
    {
        return $this->belongsTo(OffType::class);
    }
}

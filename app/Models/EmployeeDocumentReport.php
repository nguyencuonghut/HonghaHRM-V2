<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeDocumentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'syll',
        'cmt',
        'sk',
        'gks',
        'shk',
        'dxv',
        'bc',
        'gxnds',
        'tk',
        'gtk',
        'ckhn',
        'hdtv',
        'hdld',
        'ttbm',
        'ttthtu',
        'dknpt',
        'ckt'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}

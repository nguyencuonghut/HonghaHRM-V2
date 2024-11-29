<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employee extends Model
{
    use HasFactory;


    protected $fillable = [
        'code',
        'name',
        'img_path',
        'private_email',
        'company_email',
        'phone',
        'relative_phone',
        'date_of_birth',
        'cccd',
        'issued_date',
        'issued_by',
        'gender',
        'address',
        'commune_id',
        'temporary_address',
        'temporary_commune_id',
        'experience',
        'marriage_status',
        'bhxh',
    ];

    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class);
    }

    public function temporary_commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class);
    }

    public function schools(): BelongsToMany
    {
        return $this->belongsToMany(School::class, 'employee_schools', 'employee_id', 'school_id')->withTimestamps();;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'relative_phone',
        'date_of_birth',
        'cccd',
        'issued_date',
        'issued_by',
        'gender',
        'address',
        'commune_id',
        'note',
        'experience',
        'creator_id',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commune(): BelongsTo
    {
        return $this->BelongsTo(Commune::class);
    }

    public function schools(): BelongsToMany
    {
        return $this->belongsToMany(School::class, 'candidate_schools', 'candidate_id' ,'school_id')->withTimestamps();;
    }

    public function recruitments(): BelongsToMany
    {
        return $this->belongsToMany(Recruitment::class, 'recruitment_candidates')->withTimestamps();;
    }
}

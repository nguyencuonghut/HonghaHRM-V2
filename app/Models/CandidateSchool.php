<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateSchool extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'school_id',
        'degree_id',
        'major',
    ];
}

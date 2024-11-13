<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Method extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'plan_methods', 'method_id', 'plan_id');
    }
}

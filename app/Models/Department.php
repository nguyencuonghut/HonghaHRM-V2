<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function divisions(): HasMany
    {
        return $this->hasMany(Division::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function department_manager(): HasOne
    {
        return $this->hasOne(DepartmentManager::class);
    }

    public function department_vice(): HasOne
    {
        return $this->hasOne(DepartmentVice::class);
    }

}

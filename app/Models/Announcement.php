<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = ['recruitment_id', 'status'];

    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class);
    }

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'announcement_channels', 'announcement_id', 'channel_id')->withTimestamps();;
    }
}

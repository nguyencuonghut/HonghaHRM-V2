<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function announcements(): BelongsToMany
    {
        return $this->belongsToMany(Announcement::class, 'announcement_channels', 'channel_id', 'announcement_id')->withTimestamps();;
    }
}

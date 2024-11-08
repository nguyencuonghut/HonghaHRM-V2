<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'doc_type_id', 'file_path'];

    public function doc_type(): BelongsTo
    {
        return $this->belongsTo(DocType::class);
    }
}

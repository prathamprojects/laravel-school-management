<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'name',
        'standard_id',
        'gender',
        'year',
        'photo_path',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function standard(): BelongsTo
    {
        return $this->belongsTo(Standard::class);
    }
}

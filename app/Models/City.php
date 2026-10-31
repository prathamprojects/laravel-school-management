<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = [
        'district_id',
        'name',
    ];

    public function district() : BelongsTo {
        return $this->belongsTo(District::class);
    }

    public function schools() : HasMany {
        return $this->hasMany(School::class);
    }
}

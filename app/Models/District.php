<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    protected $fillable = [
        'state_id',
        'name',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function cities() : HasMany {
        return $this->hasMany(City::class);
    }

    public function schools() : HasMany {
        return $this->hasMany(School::class);
    }
}

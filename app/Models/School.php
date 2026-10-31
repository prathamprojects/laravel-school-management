<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class School extends Model
{
    use HasApiTokens, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'state_id',
        'district_id',
        'city_id',
        'establishment_date',
        'contact_number',
        'login_id',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts() : array {
        return [
            'establishment_date' => 'date',
        ];
    }

    function state() : BelongsTo {
        return $this->belongsTo(State::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function students() : HasMany {
        return $this->hasMany(Student::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(SchoolPhoto::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OfficeSpace extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'thumbnail',
        'slug',
        'about',
        'address',
        'city_id',
        'is_open',
        'is_full_booked',
        'price',
        'duration',
    ];
    // for slug name in url
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value; //misal = nama space nya adalah kost bang jack
        $this->attributes['slug'] = Str::slug($value); //kemudian akan menjadi = slug nya adalah kost-bang-jack
    }

    // setup relationship table
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(OfficeSpacesPhto::class);
    }

    public function benefits(): HasMany
    {
        return $this->hasMany(OfficeSpacesBenefit::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(BookingTransaction::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficeSpacesPhto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'photo',
        'office_space_id',
    ];
    public function OfficeSpace(): BelongsTo
    {
        return $this->belongsTo(OfficeSpace::class, 'office_space_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = [
        'name',
        'serial_number',
        'description',
        'manufacturer',
        'qr_code',
        'location'
    ];

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}

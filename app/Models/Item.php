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

    public function isCurrentlyOccupied(): bool
    {
        $today = now()->format('Y-m-d');

        return $this->loans()
            ->where(function ($query) use ($today) {
                $query->where('status', Loan::STATUS_BORROWED)
                    ->orWhere(function ($q) use ($today) {
                        $q->where('status', Loan::STATUS_RESERVED)
                            ->where('start_date', '<=', $today)
                            ->where('end_date_planned', '>=', $today);
                    });
            })
            ->exists();
    }

    public function currentLoan()
    {
        $today = now()->format('Y-m-d');

        return $this->loans()
            ->where(function ($query) use ($today) {
                $query->where('status', 'borrowed')
                    ->orWhere(function ($q) use ($today) {
                        $q->where('status', 'reserved')
                            ->where('start_date', '<=', $today)
                            ->where('end_date_planned', '>=', $today);
                    });
            })
            ->whereNull('end_date')
            ->first();
    }
}

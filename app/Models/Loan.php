<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    const STATUS_RESERVED = 'reserved';
    const STATUS_BORROWED = 'borrowed';

    protected $fillable = [
        'item_id',
        'user_id',
        'location',
        'start_date',
        'end_date_planned',
        'end_date',
        'status'
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAnomalyAttribute()
    {
        $today = Carbon::now()->startOfDay();
        $endDatePlanned = Carbon::parse($this->end_date_planned);

        if ($this->status === 'reserved' && $endDatePlanned->isBefore($today)) {
            return 'Expiré';
        }

        if ($this->status === 'borrowed' && $endDatePlanned->isBefore($today) && !$this->end_date) {
            return 'En retard';
        }

        return null;
    }
}

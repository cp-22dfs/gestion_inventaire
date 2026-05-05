<?php

namespace App\Models;

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
}

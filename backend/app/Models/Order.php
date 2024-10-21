<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'id',
        'number',
        'order_key',
        'status',
        'date_created',
        'total',
        'customer_id',
        'customer_note',
        'billing',
        'shipping'
    ];

    protected $casts = [
        'billing' => 'array',
        'shipping' => 'array',
    ];

    public function lineItems(): HasMany
    {
        return $this->hasMany(LineItems::class);
    }
}

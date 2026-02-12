<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    protected $fillable = [
        'invoice_number',
        'customer_name',
        'customer_contact',
        'customer_address',
        'subtotal',
        'discount',
        'tax',
        'due',
        'paid',
        'total',
        'payment_method',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

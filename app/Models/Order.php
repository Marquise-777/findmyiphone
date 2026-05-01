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
        'discount_percent',
        'discount_amount',
        'tax',
        'due',
        'paid',
        'total',
        'payment_method',
        'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

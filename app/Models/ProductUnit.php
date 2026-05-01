<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    protected $fillable = [
        'product_id',
        'imei',
        'is_sold',
        'sold_at',
    ];

    protected static function booted()
    {
        static::created(function ($unit) {
            $unit->product()->increment('available_stock');
        });

        static::updated(function ($unit) {
            if ($unit->isDirty('is_sold')) {
                if ($unit->is_sold) {
                    $unit->product()->decrement('available_stock');
                } else {
                    $unit->product()->increment('available_stock');
                }
            }
        });

        static::deleted(function ($unit) {
            if (!$unit->is_sold) {
                $unit->product()->decrement('available_stock');
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
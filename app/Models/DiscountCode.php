<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;
    protected $dates = ['valid_from', 'valid_until'];

    protected $fillable = [
        'code',
        'valid_from',
        'valid_until',
        'min_order_value',
        'discount_percentage',
        'usage_limit',
        'usage_count',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'discount_code_id');
    }
}

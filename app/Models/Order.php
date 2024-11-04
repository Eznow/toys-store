<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'user_id', 
        'total_price', 
        'status', 
        'discount_amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id'); 
    }

    public function complaints()
{
    return $this->hasMany(Complaint::class, 'order_id');
}

public function discountCode()
{
    return $this->belongsTo(DiscountCode::class, 'discount_code_id');
}
}

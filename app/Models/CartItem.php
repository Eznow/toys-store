<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'product_id', 'quantity'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product() // Mối quan hệ với Product
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id'); // Chỉ định rõ ràng khóa ngoại và khóa chính
    }
}

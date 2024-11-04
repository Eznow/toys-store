<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;
    protected $primaryKey = 'review_id';


    protected $fillable = [
        'product_id', 'user_id', 'rating', 'review',
    ];

    // Định nghĩa quan hệ với người dùng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Định nghĩa quan hệ với sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}

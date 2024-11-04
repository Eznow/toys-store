<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'name', 'description', 'price', 'stock', 'image_url', 'category_id', 'age_group', 'gender',
    ];

    // Quan hệ ngược lại 1-nhiều: mỗi product thuộc về 1 category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id', 'product_id');
    }
}

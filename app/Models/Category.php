<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $primaryKey = 'category_id';
    protected $fillable = ['name'];
    public $timestamps = false; 

// Quan hệ 1-nhiều: 1 category có nhiều product
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');    
    }
}

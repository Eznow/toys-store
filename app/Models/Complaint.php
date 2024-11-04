<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;
    protected $primaryKey = 'complaint_id';

    protected $fillable = ['user_id', 'order_id', 'description', 'status', 'resolved_at'];

    public function user()
{
    return $this->belongsTo(User::class, 'user_id'); 
}

    public function order()
{
    return $this->belongsTo(Order::class, 'order_id');
}


    public function replies()
    {
        return $this->hasMany(ComplaintReply::class, 'complaint_id');
    }

    public function media()
{
    return $this->hasMany(ComplaintMedia::class, 'complaint_id');
}

}

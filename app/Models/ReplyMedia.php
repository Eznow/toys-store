<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplyMedia extends Model
{
    use HasFactory;
    protected $primaryKey = 'media_id';


    protected $fillable = [
        'reply_id',
        'file_path',
    ];

    public function reply()
    {
        return $this->belongsTo(ComplaintReply::class, 'reply_id');
    }
}

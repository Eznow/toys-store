<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintMedia extends Model
{
    use HasFactory;
    use HasFactory;
    protected $primaryKey = 'media_id';

    protected $fillable = ['complaint_id', 'file_path', 'file_type'];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}

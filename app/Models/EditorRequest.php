<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditorRequest extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'admin_comment',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

}

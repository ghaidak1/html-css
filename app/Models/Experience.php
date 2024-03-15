<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;
    protected $fillable=[
        'title','description','from','to','company'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

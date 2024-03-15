<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Communicate extends Model
{
    use HasFactory;
    protected $fillable=[
        'title','link'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

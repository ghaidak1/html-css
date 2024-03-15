<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Educatedegree extends Model
{
    use HasFactory;
    protected $fillable=[
        'degree','description','university','from','to'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

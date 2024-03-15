<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;
    protected $fillable=[
        'title','description','level'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

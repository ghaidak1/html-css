<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;
    protected $fillable=[
        'title','description','image'
    ];
    public function getImageUrlAttribute(){
        if($this->image){
            $basePath='storage';
            $imagePath= str_replace('public/','',$this->image);
            return url("$basePath/$imagePath");
        }
        return null;
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

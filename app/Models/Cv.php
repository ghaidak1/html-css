<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    use HasFactory;
    protected $fillable=[
        'cv_file'
    ];

    public function getCvUrlAttribute(){
        if($this->cv_file){
            $basePath='storage';
            $imagePath= str_replace('public/','',$this->cv_file);
            return url("$basePath/$imagePath");
        }
        return null;
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

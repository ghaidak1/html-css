<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'user_id',
        'image'
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
        return $this->BelongsTo(User::class);
    }
}

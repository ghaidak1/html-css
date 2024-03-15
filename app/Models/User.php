<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'f_name',
        'l_name',
        'email',
        'password',
    ];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function image()
    {
        return $this->hasOne(Image::class);
    }

    public function job()
    {
        return $this->hasOne(Job::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }
    public function communicates()
    {
        return $this->hasMany(Communicate::class);
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function about()
    {
        return $this->hasOne(About::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function cv()
    {
        return $this->hasOne(Cv::class);
    }

    public function educatedegrees()
    {
        return $this->hasMany(Educatedegree::class);
    }


}


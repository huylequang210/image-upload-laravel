<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    // public $incrementing = false;
    // protected $keyType = 'string';
    // protected $primaryKey = 'uuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($user) {
    //         $user->id = (string) Str::uuid();
    //     });
    // }

    public function imageUpload() {
        return $this->hasMany('App\Models\ImageUpload');
    }

    public function imageUploadPublic() {
        return $this->hasMany('App\Models\ImageUpload')->public();
    }

    public function comments() {
        return $this->hasMany('App\Models\Comment');
    }

    public function vote() {
        return $this->hasMany('App\Models\Vote');
    }
}

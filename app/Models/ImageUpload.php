<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageUpload extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    // set default value
    protected $attributes = [
        'title' => 'No title',
        'public_status' => 0,
        'view' => 1,
        'upvote' => 0,
    ];

    protected $casts = [
        'title' => 'string',
        'public_status' => 'integer',
        'view' => 'integer',
        'upvote' => 'integer',
        'user_id' => 'integer'
    ];

    protected $dates = ['deleted_at'];

    public function scopePublic($query) {
        return $query->where('public_status', 1);
    }

    public function scopePrivate($query) {
        return $query->where('public_status', 0);
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function comments() {
        return $this->hasMany('App\Models\Comment');
    }

    public function galleryViews() {
        return $this->hasMany('App\Models\GalleryView');
    }

    public function vote() {
        return $this->hasMany('App\Models\Vote');
    }

}

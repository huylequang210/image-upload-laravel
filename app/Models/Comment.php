<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['body', 'user_id', 'image_upload_id'];
    public $timestamps = true;

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function imageUpload() {
        return $this->belongsTo('App\Models\ImageUpload');
    }

}

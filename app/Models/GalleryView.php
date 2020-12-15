<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryView extends Model
{
    use HasFactory;

    protected $fillable = ['ip', 'user_id', 'image_upload_id', 'agent'];

    public function imageUpload() {
        return $this->belongsTo('App\Models\ImageUpload');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'upload_image_id', 'score'];

    public function scopeMyvote($query, $imgId, $userId) {
        return $query->where([
            ['upload_image_id', '=', $imgId],
            ['user_id', '=', $userId] 
        ]);
    }

    public function user() {
        $this->belongsTo('App\Models\User');
    }

    public function imageUpload() {
        $this->belongsTo('App\Models\ImageUpload');
    }
}

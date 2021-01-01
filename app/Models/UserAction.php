<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAction extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'upload', 'comment', 'vote'];
    protected $attributes = [
        'upload' => 1,
        'comment' => 1,
        'vote' => 1,
    ];

    protected $casts = [
        'upload' => 'integer',
        'comment' => 'integer',
        'vote' => 'integer'
    ];

    public function user() {
        $this->belongsTo('App\Models\User');
    }
}

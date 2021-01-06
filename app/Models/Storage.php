<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'usage_original', 'usage_thumbnail', 'limit'];

    protected $attributes = [
        'limit' => 30.0,
        'usage_original' => 0.0,
        'usage_thumbnail' => 0.0,
    ];

    protected $casts = [
        'limit' => 'float',
        'usage_original' => 'float',
        'usage_thumbnail' => 'float',
    ];

    public function user() {
        $this->belongsTo('App\Models\User');
    }
}
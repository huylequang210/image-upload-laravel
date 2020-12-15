<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GalleryView;
use Illuminate\Support\Facades\Auth;

class GalleryViewController extends Controller
{
    public function store($image_upload_id) {
        GalleryView::create([
            'ip' => request()->ip(),
            'agent' => request()->header('User-Agent'),
            'user_id' => Auth::id(),
            'image_upload_id' => $image_upload_id
        ]);
    }
}

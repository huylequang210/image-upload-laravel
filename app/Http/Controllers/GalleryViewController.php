<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GalleryView;
use Illuminate\Support\Facades\Auth;

class GalleryViewController extends Controller
{
    public function index(ImageUpload $imageUpload) {
        $img = $imageUpload;
        // update record by retrieving new record when view update
        if(request()->attributes->get('view'))
        $img = ImageUpload::find($imageUpload->id);
        // if user access to private image
        if($img->public_status == 0 && $img->user_id !== Auth::id())  {
        abort(401);
        }
        $images = ImageUpload::public()->latest()->get();
        $comments = Comment::where('image_upload_id', '=', $img->id)->get();
        $vote = Vote::where([['upload_image_id', '=', $img->id],['user_id', '=', Auth::id()]])->first();
        return view('gallery', compact(['img', 'comments', 'images', 'vote']));
    }

    public function store($image_upload_id) {
        GalleryView::create([
            'ip' => request()->ip(),
            'agent' => request()->header('User-Agent'),
            'user_id' => Auth::id(),
            'image_upload_id' => $image_upload_id
        ]);
    }
}

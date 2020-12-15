<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\ImageUpload;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function store() {   
        request()->validate([
            'body' => 'required|min:2',
            'image_upload_id' => 'required'
        ]);
        $body = request()->input('body');
        $imageUpload = ImageUpload::findOrFail(request()->input('image_upload_id'));
        Comment::create([
            'body' => $body,
            'user_id' => Auth::id(),
            'image_upload_id' => $imageUpload->id,
        ]);
        return back();
    }

    public function update(Comment $comment) {
        if(Auth::id() === $comment->user->id) {
            request()->validate([
                'body' => 'required|min:2'
            ]);
            $comment->update(request()->input());
            return back();
        }
    }

    public function destroy(Comment $comment) {
        if(Auth::id() === $comment->user->id) {
            $comment->delete();
        }
    }
}

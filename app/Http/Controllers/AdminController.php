<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ImageUpload;
use App\Models\Vote;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    public function index() {
        $users = User::all();
        return view('admin.index', compact('users'));
    }

    public function userProfile(User $user) {
        
        return view('admin.user', compact('user'));
    }

    public function destroyImage($id) {
        $image = ImageUpload::find($id);
        File::delete([
            public_path() . '/images/' . $image->original,
            public_path() . '/images/' . $image->thumbnail,
        ]);
        $image->forceDelete();
        return back();
    }

    public function destroyComment($id) {
        $comment = Comment::find($id);
        $comment->forceDelete();
        return back();
    }

    public function userAction(User $user, $action) {
        $userAction = $user->userAction()->first();
        // 1 -> 0, 0 -> 1
        $userAction->$action = 1 - $userAction->$action;
        $userAction->save();
        return back()->with('action', $action)
                    ->with('userAction', $userAction->$action === 0 ?
                                'userActionNotAllow' : 'userActionAllow')
                    ->with('id', $user->id);
    }

    public function userActionAll(User $user) {
        $userAction = $user->userAction()->first();
        $num = 1;
        // if any of actions is 1, turn all to 0
        if($userAction->upload == 1 || $userAction->comment == 1 || $userAction->vote == 1)
            $num = 0;
        $userAction->update([
            $userAction->upload = $num,
            $userAction->vote = $num,
            $userAction->comment = $num
        ]);
        return back();
    }
}

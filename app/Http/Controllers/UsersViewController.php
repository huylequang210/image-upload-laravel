<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageUpload;
use App\Models\User;
use App\Models\Storage;
use Illuminate\Support\Facades\Auth;

class UsersViewController extends Controller
{

    public function publicView() {
        $images = ImageUpload::public()->latest()->get();
        return view('welcome', compact('images'));
    }

    public function homeView(User $user) {
        $images = User::find(Auth::id())->hasMany('App\Models\ImageUpload')->get();
        $images = $images->reverse();
        return view('home', compact('images'));
    }

    public function profileView() {
        $images = ImageUpload::onlyTrashed()->where('user_id', '=', Auth::id())->get();
        $storage = Storage::find(Auth::id());
        $totalImages = Auth::user()->imageUpload->count();
        return view('user.index', compact(['images', 'storage', 'totalImages']));
    }

    public function userView(User $user) {
        $images = $user->imageUpload()->public()->get();
        $images = $images->reverse();
        return view('user.show', compact(['user','images']));
    }
}

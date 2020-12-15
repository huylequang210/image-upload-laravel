<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageUpload;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function index(User $user) {
        $images = User::find(Auth::id())->hasMany('App\Models\ImageUpload')->get();
        $images = $images->reverse();
        return view('home', compact('images'));
    }

    public function user(User $user) {
        $images = $user->with('imageUploadPublic')->get();
        $images = $images->reverse();
        dd($images);
    }
}

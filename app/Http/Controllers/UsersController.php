<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageUpload;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
class UsersController extends Controller
{

    public function destroy($id) {
        $image = ImageUpload::onlyTrashed()->find($id);
        if($image == null && $image->user_id !== (string)Auth::id()) {
            return array('error' => 'Not allow');
        }
        File::delete([
            public_path() . '/images/' . $image->original,
            public_path() . '/images/' . $image->thumbnail,
        ]);
        $image->forceDelete();
        return back()->with('open', 'showPanel');
    }

    public function restore($id) {
        $image = ImageUpload::onlyTrashed()->find($id);
        if($image == null && $image->user_id !== (string)Auth::id()) {
            return array('error' => 'Not allow');
        }
        $image->restore();
        $image->update(['public_status' => 0]);
        return back()->with('open', 'showPanel');
    }

    public function destroyAll() {
        $images = ImageUpload::onlyTrashed()->where('user_id', '=', Auth::id())->get();
        $images->each(function($image) {
            File::delete([
                public_path() . '/images/' . $image->original,
                public_path() . '/images/' . $image->thumbnail,
            ]);
            $image->forceDelete();
        });
        return back();
    }

    public function expires() {
        
    }
}

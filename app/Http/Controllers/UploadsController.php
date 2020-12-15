<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Models\ImageUpload;
use App\Models\User;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class UploadsController extends Controller
{
    private $responseImagesName = array();

    protected function state() {
        return request()->query('r') === 'home' ? 0 : 1;
    }

    public function index() {
        $images = ImageUpload::public()->latest()->get();
        return view('welcome', compact('images'));
    }
    
    public function getImage(ImageUpload $imageUpload) {
        return $imageUpload;
    }

    public function store() {
        $images = Collection::wrap(request()->file('file'));
        $images->each(function($image) {
            $basename = Str::random();
            $original = $basename . '.' . $image->getClientOriginalExtension();
            $thumbnail = $basename . '_thumb.' . $image->getClientOriginalExtension();
            // get status where user upload image from public or home
            $status = $this->state();
            // make thumbnail and save in public
            Image::make($image)->fit(250,250)->save(public_path('/images/' . $thumbnail));
            // save image to public
            $image->move(public_path('/images'), $original);
            // make record for database
            $imageCreated = ImageUpload::create([
                'original' => $original,
                'thumbnail' => $thumbnail,
                'user_id' => auth()->user()->id,
                'public_status' => $status,
            ]);
            $this->responseImagesName = $imageCreated;
        });
        return $this->responseImagesName;
    }

    public function update(ImageUpload $imageUpload) {
        $user = auth()->user();
        if($imageUpload->user_id !== $user->id) {
            return array('error' => 'Not allow');
        }
        request()->validate([
            'title' => 'max:50|min:1',
            'public_status' => 'numeric',
        ]);
        $imageUpload->update(request()->input());
        return $imageUpload;
    }
    
    public function destroy(ImageUpload $imageUpload) {
        $user = auth()->user();
        if($imageUpload->user_id !== $user->id) {
            return array('error' => 'Not allow');
        }
        // delete files
        $result = File::delete([
            public_path() . '/images/' . $imageUpload->original,
            public_path() . '/images/' . $imageUpload->thumbnail,
        ]);
        // save data image to return
        $this->responseImagesName = $imageUpload;
        // delete record
        $imageUpload->delete();
        return $this->responseImagesName;
    }
}

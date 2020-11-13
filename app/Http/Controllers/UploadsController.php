<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Models\ImageUpload;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class UploadsController extends Controller
{
    private $responseImagesName = array();

    public function index() {
        $images = ImageUpload::latest()->get();
        return view('welcome', compact('images'));
    }

    public function imageId() {
        $images = ImageUpload::latest()->get('id');
        return response()->json($images);
    }

    public function store() {
        if(!is_dir(public_path('/images'))) {
            mkdir(public_path('/images'), 0777);
        }
        $images = Collection::wrap(request()->file('file'));
        $images->each(function($image) {
            $basename = Str::random();
            $original = $basename . '.' . $image->getClientOriginalExtension();
            $thumbnail = $basename . '_thumb.' . $image->getClientOriginalExtension();
            Image::make($image)->fit(250,250)
                ->save(public_path('/images/' . $thumbnail));

            $image->move(public_path('/images'), $original);
            $imageCreated = ImageUpload::create([
                'original' => $original,
                'thumbnail' => $thumbnail
            ]);
            array_push($this->responseImagesName, $imageCreated->id, $original, $thumbnail);
        });
        return response()->json($this->responseImagesName);
    }
    
    public function destroy(ImageUpload $imageUpload) {
        // delete files
        File::delete([
            public_path($imageUpload->original),
            public_path($imageUpload->thumbnail),
        ]);
        array_push($this->responseImagesName,
             $imageUpload->id, $imageUpload->original, $imageUpload->thumbnail);
        // delete record
        $imageUpload->delete();

        return response()->json($this->responseImagesName);
    }
}

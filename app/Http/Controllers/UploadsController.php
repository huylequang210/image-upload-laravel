<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Models\ImageUpload;
use App\Models\User;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UploadsController extends Controller
{
    private $responseImagesName = array();

    protected function state() {
        return request()->query('r') === 'home' ? 0 : 1;
    }
    
    public function index(ImageUpload $imageUpload) {
        return $imageUpload;
    }

    public function store() {
        $image = request()->file('file');
        $basename = Str::random();
        $original = $basename . '.' . $image->getClientOriginalExtension();
        $thumbnail = $basename . '_thumb.' . $image->getClientOriginalExtension();
        $size = $image->getSize();
        // get status where user upload image from public or home
        $status = $this->state();
        // make thumbnail and save in public
        $thumbnail_image = Image::make($image)->fit(250,250);
        $thumbnail_image_toStorage = $thumbnail_image->stream();
        //->save(public_path('/images/' . $thumbnail));
        //$image->move(public_path('/images'), $original);
        Storage::disk('b2')->put('/images/' . $original, File::get($image)); // unencode the file
        Storage::disk('b2')->put('/images/' . $thumbnail, $thumbnail_image_toStorage);
        // make record for database
        $imageCreated = ImageUpload::create([
            'original' => $original,
            'thumbnail' => $thumbnail,
            'user_id' => auth()->user()->id,
            'public_status' => $status,
            'original_data' => $size,
            'thumbnail_data' => $thumbnail_image->filesize(),
        ]);

        return $imageCreated;
    }

    public function update(ImageUpload $imageUpload) {
        if($imageUpload->user_id !== (string)Auth::id()) {
            return response()->json(['error' => 'Not allow'], 403);
        }
        request()->validate([
            'title' => 'max:50|min:1',
            'public_status' => 'numeric',
        ]); 
        $imageUpload->update(request()->input());
        return $imageUpload;
    }
    
    public function destroy(ImageUpload $imageUpload) {
        if($imageUpload->user_id !== Auth::id()) {
            return response()->json(['error' => 'Not allow'], 403);
        }
        // save data image to return
        $this->responseImagesName = $imageUpload;
        // soft delete record
        $imageUpload->delete();
        $imageUpload->update(['expires_at' => $imageUpload->deleted_at->add(3, 'day')->add(1, 'hour')]);
        return $this->responseImagesName;
    }
}

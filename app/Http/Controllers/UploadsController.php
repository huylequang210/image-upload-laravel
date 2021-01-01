<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Models\ImageUpload;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use App\Models\Storage;

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
        $thumbnail_image = Image::make($image)->fit(250,250)->save(public_path('/images/' . $thumbnail));
        // save image to public
        $image->move(public_path('/images'), $original);
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
        if($imageUpload->user_id !== (string)Auth::id()) {
            return array('error' => 'Not allow');
        }
        // $result = File::delete([
        //     public_path() . '/images/' . $imageUpload->original,
        //     public_path() . '/images/' . $imageUpload->thumbnail,
        // ]);
        // save data image to return
        $this->responseImagesName = $imageUpload;
        // soft delete record
        $imageUpload->delete();
        $imageUpload->update(['expires_at' => $imageUpload->deleted_at->add(3, 'day')->add(1, 'hour')]);
        return $this->responseImagesName;
    }
}

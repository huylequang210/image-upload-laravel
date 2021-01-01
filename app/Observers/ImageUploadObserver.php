<?php

namespace App\Observers;

use App\Models\ImageUpload;
use App\Models\Storage;
use Illuminate\Support\Facades\Auth;
class ImageUploadObserver
{
    /**
     * Handle the image upload "created" event.
     *
     * @param  \App\Models\ImageUpload  $imageUpload
     * @return void
     */
    public function created(ImageUpload $imageUpload)
    {
        $storage = Storage::find(Auth::id());

        if($storage == null) {
            $storage = Storage::create([
                'user_id' => Auth::id(),
            ]);
        }
        if($storage) {
            $storage->update([
                'usage_original' => $storage->usage_original + $imageUpload->original_data,
                'usage_thumbnail' =>  $storage->usage_thumbnail + $imageUpload->thumbnail_data
            ]);   
        }
    }

    /**
     * Handle the image upload "updated" event.
     *
     * @param  \App\Models\ImageUpload  $imageUpload
     * @return void
     */
    public function updated(ImageUpload $imageUpload)
    {
        //
    }

    /**
     * Handle the image upload "deleted" event.
     *
     * @param  \App\Models\ImageUpload  $imageUpload
     * @return void
     */
    public function deleted(ImageUpload $imageUpload)
    {
        //
    }

    /**
     * Handle the image upload "restored" event.
     *
     * @param  \App\Models\ImageUpload  $imageUpload
     * @return void
     */
    public function restored(ImageUpload $imageUpload)
    {
        //
    }

    /**
     * Handle the image upload "force deleted" event.
     *
     * @param  \App\Models\ImageUpload  $imageUpload
     * @return void
     */
    public function forceDeleted(ImageUpload $imageUpload)
    {
        $storage = Storage::find(Auth::id());
        if($storage) {
            $storage->update([
                'usage_original' => $storage->usage_original - $imageUpload->original_data,
                'usage_thumbnail' =>  $storage->usage_thumbnail - $imageUpload->thumbnail_data
            ]);   
        }
    }
}

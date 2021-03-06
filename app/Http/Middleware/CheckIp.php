<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\GalleryView;
use App\Http\Controllers\GalleryViewController;
use App\Http\Controllers\UploadsController;
use App\Models\ImageUpload;
class CheckIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $imageId = request()->route('imageUpload')->id;
        if($imageId === null) return response()->json(['page' => 'empty'], 404 ); 
        // check ip exist in this gallery, if yes do nothing
        $checkIp = GalleryView::where([
            ['ip', '=', request()->ip()],
            ['image_upload_id', '=', $imageId]
        ])->get();
        if($checkIp->isEmpty()) {
            (new GalleryViewController)->store($imageId);
            $image = ImageUpload::find($imageId);
            $image->update(['view' => $image->view+1]);
            request()->attributes->set('view', $image->view);
        }
        return $next($request);
    }
}

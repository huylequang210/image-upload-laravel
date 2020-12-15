<?php

namespace App\Http\Middleware;

use App\Models\ImageUpload;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vote;
class CheckVote
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
        $userId =  Auth::id();

        $vote = Vote::where([
            ['upload_image_id', '=', $imageId],
            ['user_id', '=', $userId]
        ])->first();
        
        return $next($request);
    }
}

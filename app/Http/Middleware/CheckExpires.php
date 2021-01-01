<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ImageUpload;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CheckExpires
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
        // if closest expires day is 24 hours greater than now(), skip check
        if(session()->get('closest') != null && session()->get('closest') === 1) {
            return $next($request);
        } else {
            $images = ImageUpload::onlyTrashed()->where('user_id', '=', Auth::id())->get();
            $closest = 12; 
            $images->each(function($image) use($closest) {
                $now = Carbon::now();
                $expires = new Carbon($image->expires_at); 
                if($now->gt($expires)) {
                    $image->forceDelete();
                } else {
                    $check = $now->diffInHours($expires);
                    if($closest > $check) {
                        $closest = $check;
                    }
                }
            });
            // create session for next requests
            if($closest >= 12) {
                session()->put('closest', 1);
            } else session()->put('closest', 0);
        }
        return $next($request);
    }
}

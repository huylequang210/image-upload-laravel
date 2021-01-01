<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Storage;
use Illuminate\Support\Facades\Auth;

class CheckLimit
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
        $storage = Storage::find(Auth::id());
        if($storage && floatval($storage->limit) < (floatval($storage->usage_original) / 1024 / 1000)) {
            return response()->json(['limitError' => 'Storage limited'], 403);
        } else {
            return $next($request);
        }
    }
}

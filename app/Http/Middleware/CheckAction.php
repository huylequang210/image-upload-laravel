<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserAction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class CheckAction
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
        $actions = Auth::user()->userAction;
        if(request()->path() === "image" && $actions->upload === 0) {
            return response()->json(['userAction' => 'You have been banned form uploading'], 405 ); 
        }
        if(request()->path() === "comments" && $actions->comment === 0) {
            return back()->withErrors(['comments' => 'You have been banned form commenting']);
        }
        if(substr(request()->path(), 0, 6) === "upvote" && $actions->vote === 0) {
            return response()->json(['userAction' => 'You have been banned form upvoting'], 405 ); 
        }
        return $next($request);
    }
}
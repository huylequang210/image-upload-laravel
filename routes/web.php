<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\GalleryViewController;
use App\Http\Controllers\UploadsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;
use App\Models\ImageUpload;
use App\Models\Comment;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [UploadsController::class, 'index']);
Route::get('/image/{imageUpload}', [UploadsController::class, 'getImage']);
Route::middleware(['auth'])->group(function () {
  Route::get('/home', [UsersController::class, 'index'])->name('home');
  Route::post('/image', [UploadsController::class, 'store']);
  Route::delete('/image/{imageUpload}', [UploadsController::class, 'destroy']);
  Route::patch('/image/{imageUpload}', [UploadsController::class, 'update']);
  Route::post('/comments', [CommentsController::class, 'store']);
  Route::patch('/comments/{comment}', [CommentsController::class, 'update']);
  Route::delete('/comments/{comment}', [CommentsController::class, 'destroy']);
  Route::patch('/upvote/{imageUpload}/{score}', [VoteController::class, 'update']);
  Route::post('/upvote/{imageUpload}/{score}', [VoteController::class, 'store']);
  Route::delete('/upvote/{imageUpload}/{score}', [VoteController::class, 'destroy']);
  Route::get('/upvote/{imageUpload}', [VoteController::class, 'index']);
});

Route::middleware(['throttle:image'])->group(function() {
  Route::patch('/image/{imageUpload}', [UploadsController::class, 'update']);
});


Route::middleware(['checkIp:gallery', 'checkVote:gallery'])->group(function() {
  Route::get('/gallery/{imageUpload}', function(ImageUpload $imageUpload) {
    $img = $imageUpload;
    // if user access to private image
    if($img->public_status == 0 && $img->user_id !== Auth::id()) return abort(401);
    $images = ImageUpload::public()->latest()->get();
    $comments = Comment::where('image_upload_id', '=', $img->id)->get();
    $vote = Vote::where([['upload_image_id', '=', $img->id],['user_id', '=', Auth::id()]])->first();
    return view('gallery', compact(['img', 'comments', 'images', 'vote']));
  });
  Route::post('/gallery/{imageUpload}', [GalleryViewController::class, 'store']);
});

Route::get('/user/{user}', [UsersController::class, 'user']);


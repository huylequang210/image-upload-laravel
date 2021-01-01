<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\GalleryViewController;
use App\Http\Controllers\UploadsController;
use App\Http\Controllers\UsersViewController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use App\Models\ImageUpload;
use App\Models\Comment;
use App\Models\Vote;
use App\View\Components\Image;
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

Route::get('/', [UsersViewController::class, 'publicView']);

Route::get('/image/{imageUpload}', [UploadsController::class, 'index']);

Route::middleware(['auth'])->group(function () {
  Route::get('/home', [UsersViewController::class, 'homeView'])->name('home');
  Route::get('/profile', [UsersViewController::class, 'profileView'])->middleware('checkExpires')->name('user.index');

  Route::post('/image', [UploadsController::class, 'store'])->middleware(['checkAction','checkLimit']);
  Route::delete('/image/{imageUpload}', [UploadsController::class, 'destroy']);
  Route::patch('/image/{imageUpload}', [UploadsController::class, 'update'])->middleware('throttle:image');

  Route::post('/comments', [CommentsController::class, 'store'])->middleware('checkAction');
  Route::patch('/comments/{comment}', [CommentsController::class, 'update']);
  Route::delete('/comments/{comment}', [CommentsController::class, 'destroy']);
  
  Route::post('/upvote/{imageUpload}/{score}', [VoteController::class, 'store'])->middleware('checkAction');
  Route::patch('/upvote/{imageUpload}/{score}', [VoteController::class, 'update'])->middleware('throttle:upvote');
  Route::delete('/upvote/{imageUpload}/{score}', [VoteController::class, 'destroy']);
  Route::get('/upvote/{imageUpload}', [VoteController::class, 'index']);

  Route::patch('/profile/{id}', [UsersController::class, 'restore']);
  Route::delete('/profile/{id}', [UsersController::class, 'destroy']);
  Route::delete('/profile/delete/all', [UsersController::class, 'destroyAll']);
  Route::delete('/profile/expires', [UsersController::class, 'expires']);

  Route::patch('/change-password', ChangePassword::class)->name('password.change');
});



Route::patch('/image/{imageUpload}', [UploadsController::class, 'update']);

Route::middleware(['checkIp'])->group(function() {
  Route::get('/gallery/{imageUpload}', function(ImageUpload $imageUpload) {
    $img = $imageUpload;
    // update record by retrieving new record when view update
    if(request()->attributes->get('view'))
      $img = ImageUpload::find($imageUpload->id);
    // if user access to private image
    if($img->public_status == 0 && $img->user_id !== (string)Auth::id())  {
      abort(401);
    }
    $images = ImageUpload::public()->latest()->get();
    $comments = Comment::where('image_upload_id', '=', $img->id)->get();
    $vote = Vote::where([['upload_image_id', '=', $img->id],['user_id', '=', Auth::id()]])->first();
    return view('gallery', compact(['img', 'comments', 'images', 'vote']));
  });
  Route::post('/gallery/{imageUpload}', [GalleryViewController::class, 'store']);
});

Route::get('/user/{user}', [UsersViewController::class, 'userView'])->name('user.show');


Route::middleware(['auth', 'isAdmin'])->group(function() {
  Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
  Route::get('/admin/user/{user}', [AdminController::class, 'userProfile'])->name('admin.userProfile');
  Route::delete('/admin/user/image/{id}', [AdminController::class, 'destroyImage']);
  Route::delete('/admin/user/comment/{id}', [AdminController::class, 'destroyComment']);
  Route::patch('/admin/{user}/{action}', [AdminController::class, 'userAction']);
  Route::patch('/admin/all/{user}/action', [AdminController::class, 'userActionAll']);
});
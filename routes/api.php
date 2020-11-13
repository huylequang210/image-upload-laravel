<?php

use App\Http\Controllers\UploadsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/images', [UploadsController::class, 'store'])->middleware(['auth']);
Route::delete('/images/{imageUpload}', [UploadsController::class, 'destroy'])->middleware(['auth']);

Route::get('/getImagesId', [UploadsController::class, 'imageId']);
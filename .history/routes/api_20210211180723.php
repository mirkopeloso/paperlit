<?php

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

Route::post('/fileUpload',  [ApiController::class, 'fileUpload'])   ->name('api.fileUpload');//->middleware('auth:api')
Route::post('/user',        [ApiController::class, 'user'])         ->name('api.user');//->middleware('auth:api


//->middleware('auth:api')
;

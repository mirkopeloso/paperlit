<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

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

Route::post('/fileUpload',  [ApiController::class, 'fileUpload'])   ->middleware('api')         ->name('api.fileUpload');//->middleware('auth:api')
Route::get('/user/{id}',   [ApiController::class, 'user'])         ->middleware('api')         ->name('api.user');//->middleware('auth:api


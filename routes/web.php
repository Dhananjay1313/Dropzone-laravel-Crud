<?php

use App\Http\Controllers\Dropzonecontroller;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/add',[Dropzonecontroller::class,'add']);

Route::get('/list',[Dropzonecontroller::class,'list']);

Route::post('/upload',[Dropzonecontroller::class,'upload']);

Route::get('/edit',[Dropzonecontroller::class,'edit']);

Route::post('/deleteimage',[Dropzonecontroller::class,'deleteimage']);

Route::post('/deleteimg',[Dropzonecontroller::class,'deleteimg']);

Route::post('/deleteupload',[Dropzonecontroller::class,'deleteupload']);
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CalendarEventController;

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


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::get('me', 'me');
    Route::get('getAllPhotos/{id}', 'getAllPhotos');
    Route::post('logout', 'logout');
    Route::post('getPathImage', 'getPathImage');
    Route::post('setName', 'setName');
    Route::post('setPhoto/{id}', 'setPhoto');
    Route::post('setPhotoOld/{id}', 'setPhotoOld');
});

Route::controller(GalleryController::class)->group(function () {
    Route::post('uploadPicture', 'uploadPicture');
    Route::get('getPictures', 'getPictures');
    Route::put('updateValue/{photo_id}/{user_id}', 'updateValue');
    Route::get('getValues/{id}', 'getValues');
    Route::delete('removePhoto/{id}', 'removePhoto');
});

Route::controller(CalendarController::class)->group(function () {
    Route::post('getDetailsOfMonth', 'getDetailsOfMonth');
    Route::post('addDetail', 'addDetail');
    Route::put('editDetail/{id}', 'editDetail');
});

Route::controller(EventController::class)->group(function () {
    Route::get('getEvents', 'getEvents');
    Route::post('addEvent', 'addEvent');
    Route::delete('delEvent/{id}', 'delEvent');
});

Route::controller(CalendarEventController::class)->group(function () {
    Route::get('getCalendarEvents', 'getCalendarEvents');
    Route::post('addEventCalendar', 'addEventCalendar');
    Route::delete('delCalendarEvent/{id}', 'delCalendarEvent');
});

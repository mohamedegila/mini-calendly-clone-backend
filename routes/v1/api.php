<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\EventController;
use App\Service\Zoom\ZoomService;
// use Event;
use App\Events\SendMail;
use App\Http\Controllers\Api\V1\EventAtendeeController;

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

Route::post('/signin', [AuthController::class, 'signin']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/events/{user}/{event}', [EventController::class, 'userEvent']);
Route::post('/event/register', [EventAtendeeController::class, 'store']);


Route::get('/test-zoom', function(){
    $zoom = new ZoomService();
    dump( $zoom);

    $config = [
        'topic'       => "Test Eg",
        'type'        => "2",
        'start_time'  => '2023-1-22T22:00:00Z',
        'duration'    => 30,
        'password'    => ''

    ];
    $zoom->configSetter($config);

    $res =  $zoom->createZoomMeeting();

    dd($res);
});

Route::get('/test-mail', function(){
    $details = [
        'title' => 'Mail from Egila',
        'body' => 'This is for testing email using smtp',
        'link' => 'link'
    ];

    event(new SendMail([
        'egila.test@gmail.com',
        'mohammedegila@gmail.com'
    ], $details ));

    dd("Email is Sent.");
});



Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::apiResource('event', EventController::class);

});

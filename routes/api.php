<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

Route::group(['prefix' => 'v1'], function () {

    # login
    Route::post('login', 'UserController@login');

    # registration
    Route::post('register', 'UserController@register');

    # forgot password
    Route::post('forgot/password', 'UserController@forgotPassword');

    # profile update
    Route::put('profile/update', 'UserController@profileUpdate');

    # refresh user profile
    Route::get('profile/info/{id}', 'UserController@profileInfo');

    # get municipals
    Route::get('municipals', 'UserController@municipals');

    # report incident
    Route::post('report/incident', 'UserController@reportIncident');

    # get reports
    Route::get('reports', 'UserController@reports');

    # view report
    Route::get('report/view/{id}', 'UserController@viewReport');
});
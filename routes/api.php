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

Route::get('all-statistic', 'Api\StatisticController@getAll')->middleware('cors');
Route::get('last-statistic', 'Api\StatisticController@getLast')->middleware('cors');
Route::get('statistic', 'Api\StatisticController@byCountry')->middleware('cors');

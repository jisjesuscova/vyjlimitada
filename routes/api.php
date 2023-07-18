<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\ControlController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
});

Route::resource('team', TeamController::class)->except(['create', 'edit']);
Route::resource('user', UserController::class)->except(['create', 'edit']);
Route::resource('event', EventController::class)->except(['create', 'edit']);
Route::resource('ticket', TicketController::class)->except(['create', 'edit']);
Route::resource('control', ControlController::class)->except(['create', 'edit']);
Route::get('event/all/{id}', 'App\Http\Controllers\Api\EventController@index');
Route::get('control/status/{id}', 'App\Http\Controllers\Api\ControlController@status');
Route::get('ticket/check/{id}/{user_id}', 'App\Http\Controllers\Api\TicketController@check');


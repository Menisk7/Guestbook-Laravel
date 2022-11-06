<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
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



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'parties'], static function (): void {
    Route::get('', ['uses' => 'App\Http\Controllers\PartyController@index']); // GET /api/parties
    Route::post('', ['uses' => 'App\Http\Controllers\PartyController@store']); // POST /api/parties
    Route::put('{party}', ['uses' => 'App\Http\Controllers\PartyController@update']); // PUT /api/parties/:id
    Route::delete('{party}', ['uses' => 'App\Http\Controllers\PartyController@delete']); // DELETE /api/parties/:id
});

Route::group(['prefix' => 'users'], static function (): void {
    Route::get('', ['uses' => 'App\Http\Controllers\UserController@index']); // GET /api/users
});

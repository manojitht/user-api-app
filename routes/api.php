<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('users', [UserController::class, 'index']);
Route::post('register', [UserController::class, 'register']);
Route::get('users/{id}', [UserController::class, 'show']);
Route::get('users/{id}/edit', [UserController::class, 'edit']);
Route::put('users/{id}/edit', [UserController::class, 'update']);
Route::delete('users/{id}/delete', [UserController::class, 'destroy']);
Route::post('login', [UserController::class, 'login']);

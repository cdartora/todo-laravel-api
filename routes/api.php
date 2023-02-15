<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

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

Route::get('/', function () {
    return response()->json(['message' => 'hello, world. im a simple todo rest api that can manage your tasks.']);
});

// Auth Routes
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

// Tasks Routes
Route::get('/tasks', [TaskController::class, 'index'])->middleware('jwt');
Route::post('/tasks', [TaskController::class, 'store'])->middleware('jwt');
Route::get('/tasks/{id}', [TaskController::class, 'show'])->middleware('jwt');
Route::put('/tasks/{id}', [TaskController::class, 'update'])->middleware('jwt');
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->middleware('jwt');
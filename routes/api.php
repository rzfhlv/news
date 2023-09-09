<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;

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

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

Route::middleware(['auth:api'])->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
});

Route::any('{path}', function () {
    return response()->json(array(
        'error' => true,
        'message' => 'Invalid API',
        'data' => []
    ), 404);
})->where('path', '.*');

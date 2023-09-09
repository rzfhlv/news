<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NewsController;

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

    Route::prefix('/news')->group(function () {
        Route::controller(NewsController::class)->group(function () {
            Route::post('/', 'create');
            Route::get('/', 'all');
            Route::get('/{id}', 'get');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'delete');
        });
    });
});

Route::any('{path}', function () {
    return response()->json(array(
        'error' => true,
        'message' => 'Invalid API',
        'data' => []
    ), 404);
})->where('path', '.*');

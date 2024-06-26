<?php

use App\Models\Course;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\MateriController;
use App\Http\Controllers\API\CategoryController;

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



// route for category
Route::get('/categories', [CategoryController::class, 'index']);

Route::middleware('auth:sanctum')->group(function() {
    // user
    Route::get('/me', function(Request $request) {
        return $request->user();
    });

    // route for course
    Route::apiResource('courses', CourseController::class);

    // route for materi
    Route::apiResource('materis', MateriController::class);

    Route::get('kursus', [MateriController::class, 'kursus']);
    Route::get('material/{course}', [CourseController::class, 'material']);

    Route::get('courses/{id}/materis', 'CourseController@materis');

    // route for user
    Route::apiResource('users', UserController::class);
    Route::put('user/password/{id}', [UserController::class, 'updatePassword']);

});
// Route::apiResource('users', UserController::class);

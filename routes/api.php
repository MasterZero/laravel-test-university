<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\LectionsController;

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


/**
 * Classes
*/
Route::get('/classes', [ClassesController::class, 'list']);
Route::get('/class/{class_id}', [ClassesController::class, 'info']);
Route::post('/class', [ClassesController::class, 'create']);
Route::put('/class/{class_id}', [ClassesController::class, 'update']);
Route::delete('/class/{class_id}', [ClassesController::class, 'delete']);
Route::get('/class/{class_id}/lections', [ClassesController::class, 'lections_list']);
Route::put('/class/{class_id}/lections', [ClassesController::class, 'lections_update']);


/**
 * Students
*/
Route::get('/students', [StudentsController::class, 'list']);
Route::get('/student/{student_id}', [StudentsController::class, 'info']);
Route::post('/student', [StudentsController::class, 'create']);
Route::put('/student/{student_id}', [StudentsController::class, 'update']);
Route::delete('/student/{student_id}', [StudentsController::class, 'delete']);

/**
 * Lections
*/
Route::get('/lections', [LectionsController::class, 'list']);
Route::get('/lection/{lection_id}', [LectionsController::class, 'info']);
Route::post('/lection', [LectionsController::class, 'create']);
Route::put('/lection/{lection_id}', [LectionsController::class, 'update']);
Route::delete('/lection/{lection_id}', [LectionsController::class, 'delete']);

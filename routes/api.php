<?php

use App\Http\Controllers\GradeController;
use App\Http\Controllers\ScoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::controller(ScoreController::class)->group(function () {
    Route::get('/student-scores', 'getStudentsScore');
    Route::get('/student-score/{nim}', 'getStudentScoreByID');
    Route::post('/student-score', 'postStudentScore');
    Route::put('/student-score/{nim}', 'updateStudentScore');
    Route::delete('/student-score/{nim}', 'deleteStudentScore');

    Route::get('/student-scores/chart', 'gradeChart');
});

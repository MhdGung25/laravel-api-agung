<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\InformationController;

/*
|--------------------------------------------------------------------------
| API Routes - Digital System RW 18
|--------------------------------------------------------------------------
*/

// --- PUBLIC ROUTES (Tanpa Token) ---
Route::prefix('auth')->group(function () {
    Route::post('/login-check', [AuthController::class, 'loginCheck']);
    Route::post('/register-sync', [AuthController::class, 'registerFromFirebase']);
});

// --- PROTECTED ROUTES (Wajib Token Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Dashboard & Profile
    Route::get('/dashboard-data', [DashboardController::class, 'index']);
    Route::get('/user-profile', fn(Request $request) => response()->json($request->user()));
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // CRUD Laporan (Reports)
    Route::apiResource('reports', ReportController::class);
    Route::get('/my-reports', [ReportController::class, 'myReports']);
    Route::put('/reports/{id}/status', [ReportController::class, 'updateStatus']);

    // CRUD Informasi Aktif (Informations)
    Route::get('/informations', [InformationController::class, 'index']);
    Route::post('/informations', [InformationController::class, 'store']);
    Route::get('/informations/{id}', [InformationController::class, 'show']);
    Route::put('/informations/{id}', [InformationController::class, 'update']);
    Route::patch('/informations/{id}/status', [InformationController::class, 'toggleStatus']);
    Route::delete('/informations/{id}', [InformationController::class, 'destroy']);
});
<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;
use OpenApi\Generator as OpenApiGenerator;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);


    Route::apiResource('employees', EmployeeController::class);

    Route::get('attendance', [AttendanceController::class, 'index']);
    Route::post('attendance/check-in', [AttendanceController::class, 'checkIn']);
    Route::post('attendance/check-out', [AttendanceController::class, 'checkOut']);

    Route::get('reports/attendance/daily/pdf', [AttendanceReportController::class, 'dailyPdf']);
    Route::get('reports/attendance/daily/excel', [AttendanceReportController::class, 'dailyExcel']);
    Route::post('reports/attendance/daily/email', [AttendanceReportController::class, 'sendEmailReport']);
});


Route::get('openapi.json', function () {
    $openapi = OpenApiGenerator::scan([app_path()]);

    return response($openapi->toJson())->header('Content-Type', 'application/json');
});

Route::get('docs', function () {
    return view('docs.swagger');
});

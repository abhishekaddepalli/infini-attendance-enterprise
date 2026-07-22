<?php

use Illuminate\Support\Facades\Route;
use Infini\Attendance\Http\Controllers\Api\V1\{
    DashboardController,
    AttendanceController,
    EmployeeController,
    LeaveController,
    ShiftController,
    PayrollController,
    BiometricDeviceController,
    FaceRecognitionController,
    GeoLocationController,
    ReportController,
    AIAssistantController,
    SettingsController,
    NotificationController,
};

// Dashboard
Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
Route::get('/dashboard/trends', [DashboardController::class, 'trends']);
Route::get('/dashboard/live-attendance', [DashboardController::class, 'liveAttendance']);

// Attendance
Route::prefix('attendance')->group(function () {
    Route::post('/check-in', [AttendanceController::class, 'checkIn']);
    Route::post('/check-out', [AttendanceController::class, 'checkOut']);
    Route::get('/today-summary', [AttendanceController::class, 'todaySummary']);
    Route::get('/calendar', [AttendanceController::class, 'calendar']);
    Route::get('/timeline/{employee}', [AttendanceController::class, 'timeline']);
    Route::post('/corrections/request', [AttendanceController::class, 'requestCorrection']);
    Route::post('/corrections/{correction}/approve', [AttendanceController::class, 'approveCorrection']);
    Route::get('/export', [AttendanceController::class, 'export']);
});

// Face Recognition
Route::prefix('face-recognition')->group(function () {
    Route::post('/register', [FaceRecognitionController::class, 'register']);
    Route::post('/verify', [FaceRecognitionController::class, 'verify']);
    Route::post('/liveness-check', [FaceRecognitionController::class, 'livenessCheck']);
    Route::post('/anti-spoof', [FaceRecognitionController::class, 'antiSpoof']);
    Route::get('/faces/{employee}', [FaceRecognitionController::class, 'getFaces']);
    Route::delete('/faces/{employee}', [FaceRecognitionController::class, 'deleteFaces']);
});

// Biometric Devices
Route::prefix('devices')->group(function () {
    Route::get('/', [BiometricDeviceController::class, 'index']);
    Route::post('/', [BiometricDeviceController::class, 'store']);
    Route::get('/{device}', [BiometricDeviceController::class, 'show']);
    Route::put('/{device}', [BiometricDeviceController::class, 'update']);
    Route::post('/{device}/sync', [BiometricDeviceController::class, 'syncAttendance']);
    Route::post('/{device}/restart', [BiometricDeviceController::class, 'restart']);
    Route::get('/{device}/health', [BiometricDeviceController::class, 'healthCheck']);
    Route::post('/discover', [BiometricDeviceController::class, 'discover']);
    Route::get('/drivers/list', [BiometricDeviceController::class, 'getDrivers']);
});

// Employees
Route::prefix('employees')->group(function () {
    Route::get('/', [EmployeeController::class, 'index']);
    Route::post('/', [EmployeeController::class, 'store']);
    Route::get('/{employee}', [EmployeeController::class, 'show']);
    Route::put('/{employee}', [EmployeeController::class, 'update']);
    Route::delete('/{employee}', [EmployeeController::class, 'destroy']);
    Route::post('/bulk-import', [EmployeeController::class, 'bulkImport']);
    Route::get('/export/excel', [EmployeeController::class, 'export']);
});

// Leave Management
Route::prefix('leave')->group(function () {
    Route::get('/requests', [LeaveController::class, 'requests']);
    Route::post('/apply', [LeaveController::class, 'apply']);
    Route::post('/{leave}/approve', [LeaveController::class, 'approve']);
    Route::post('/{leave}/reject', [LeaveController::class, 'reject']);
    Route::get('/balance/{employee}', [LeaveController::class, 'balance']);
    Route::get('/types', [LeaveController::class, 'types']);
    Route::get('/holidays', [LeaveController::class, 'holidays']);
});

// Shifts
Route::prefix('shifts')->group(function () {
    Route::get('/', [ShiftController::class, 'index']);
    Route::post('/', [ShiftController::class, 'store']);
    Route::put('/{shift}', [ShiftController::class, 'update']);
    Route::post('/assign', [ShiftController::class, 'assign']);
    Route::get('/schedule', [ShiftController::class, 'schedule']);
});

// Payroll
Route::prefix('payroll')->group(function () {
    Route::post('/process', [PayrollController::class, 'process']);
    Route::get('/slips/{employee}', [PayrollController::class, 'salarySlips']);
    Route::get('/slip/{slip}/download', [PayrollController::class, 'downloadSlip']);
    Route::get('/summary', [PayrollController::class, 'summary']);
    Route::get('/overtime', [PayrollController::class, 'overtime']);
});

// Geo Location
Route::prefix('geo')->group(function () {
    Route::get('/geofences', [GeoLocationController::class, 'geofences']);
    Route::post('/geofences', [GeoLocationController::class, 'createGeofence']);
    Route::get('/live-tracking', [GeoLocationController::class, 'liveTracking']);
    Route::get('/location-history/{employee}', [GeoLocationController::class, 'locationHistory']);
});

// Reports
Route::prefix('reports')->group(function () {
    Route::get('/attendance', [ReportController::class, 'attendanceReport']);
    Route::get('/payroll', [ReportController::class, 'payrollReport']);
    Route::post('/custom', [ReportController::class, 'generateCustom']);
    Route::get('/export/{report}', [ReportController::class, 'export']);
});

// AI Assistant
Route::prefix('ai')->group(function () {
    Route::post('/chat', [AIAssistantController::class, 'chat']);
    Route::post('/voice', [AIAssistantController::class, 'voice']);
    Route::get('/attendance-prediction', [AIAssistantController::class, 'attendancePrediction']);
    Route::get('/executive-summary', [AIAssistantController::class, 'executiveSummary']);
    Route::post('/nlp-query', [AIAssistantController::class, 'nlpQuery']);
});

// Settings
Route::prefix('settings')->group(function () {
    Route::get('/organization', [SettingsController::class, 'organization']);
    Route::put('/organization', [SettingsController::class, 'updateOrganization']);
    Route::get('/roles', [SettingsController::class, 'roles']);
    Route::post('/roles', [SettingsController::class, 'createRole']);
    Route::get('/audit-logs', [SettingsController::class, 'auditLogs']);
    Route::put('/white-label', [SettingsController::class, 'updateWhiteLabel']);
});

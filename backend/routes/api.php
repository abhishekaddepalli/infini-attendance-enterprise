<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'system' => 'Infini Attendance Enterprise API',
        'timestamp' => now()->toIso8601String(),
    ]);
});

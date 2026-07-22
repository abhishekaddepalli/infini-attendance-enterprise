<?php

use Illuminate\Support\Facades\Route;

Route::get('/manifest', function () {
    return response()->json([
        'name' => 'Infini Attendance',
        'short_name' => 'Infini',
        'start_url' => '/',
        'display' => 'standalone'
    ]);
});

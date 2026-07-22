<?php

use Illuminate\Support\Facades\Route;

Route::get('/tenants', function () {
    return response()->json(['tenants' => []]);
});

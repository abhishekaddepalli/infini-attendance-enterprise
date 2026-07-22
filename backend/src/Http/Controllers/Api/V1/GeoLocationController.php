<?php

namespace Infini\Attendance\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GeoLocationController extends Controller
{
    public function geofences(Request $request) { return response()->json(['status' => 'success', 'data' => []]); }
    public function createGeofence(Request $request) { return response()->json(['status' => 'success', 'message' => 'Geofence created']); }
    public function liveTracking(Request $request) { return response()->json(['status' => 'success', 'data' => []]); }
    public function locationHistory(Request $request, $employee) { return response()->json(['status' => 'success', 'data' => []]); }
}

<?php

namespace Infini\Attendance\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BiometricDeviceController extends Controller
{
    public function index(Request $request) { return response()->json(['status' => 'success', 'data' => []]); }
    public function store(Request $request) { return response()->json(['status' => 'success', 'message' => 'Device added']); }
    public function show(Request $request, $device) { return response()->json(['status' => 'success', 'data' => ['id' => $device]]); }
    public function update(Request $request, $device) { return response()->json(['status' => 'success', 'message' => 'Device updated']); }
    public function syncAttendance(Request $request, $device) { return response()->json(['status' => 'success', 'message' => 'Attendance synced']); }
    public function restart(Request $request, $device) { return response()->json(['status' => 'success', 'message' => 'Device restarted']); }
    public function healthCheck(Request $request, $device) { return response()->json(['status' => 'success', 'health' => 'online']); }
    public function discover(Request $request) { return response()->json(['status' => 'success', 'devices' => []]); }
    public function getDrivers(Request $request) { return response()->json(['status' => 'success', 'drivers' => ['ZKTeco', 'eSSL', 'Matrix', 'Suprema', 'Hikvision']]); }
}

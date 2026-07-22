<?php

namespace Infini\Attendance\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ShiftController extends Controller
{
    public function index(Request $request) { return response()->json(['status' => 'success', 'data' => []]); }
    public function store(Request $request) { return response()->json(['status' => 'success', 'message' => 'Shift created']); }
    public function update(Request $request, $shift) { return response()->json(['status' => 'success', 'message' => 'Shift updated']); }
    public function assign(Request $request) { return response()->json(['status' => 'success', 'message' => 'Shift assigned']); }
    public function schedule(Request $request) { return response()->json(['status' => 'success', 'data' => []]); }
}

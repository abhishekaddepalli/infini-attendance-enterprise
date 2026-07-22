<?php

namespace Infini\Attendance\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AttendanceController extends Controller
{
    public function checkIn(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Checked in successfully',
            'timestamp' => now()->toIso8601String()
        ]);
    }

    public function checkOut(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Checked out successfully',
            'timestamp' => now()->toIso8601String()
        ]);
    }

    public function todaySummary(Request $request)
    {
        return response()->json(['status' => 'success', 'data' => []]);
    }

    public function calendar(Request $request)
    {
        return response()->json(['status' => 'success', 'data' => []]);
    }

    public function timeline(Request $request, $employee)
    {
        return response()->json(['status' => 'success', 'data' => []]);
    }

    public function requestCorrection(Request $request)
    {
        return response()->json(['status' => 'success', 'message' => 'Correction requested']);
    }

    public function approveCorrection(Request $request, $correction)
    {
        return response()->json(['status' => 'success', 'message' => 'Correction approved']);
    }

    public function export(Request $request)
    {
        return response()->json(['status' => 'success', 'download_url' => '']);
    }
}

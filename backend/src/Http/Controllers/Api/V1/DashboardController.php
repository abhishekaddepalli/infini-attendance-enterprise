<?php

namespace Infini\Attendance\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_employees' => 142,
                'present_today' => 128,
                'late_today' => 6,
                'on_leave' => 8,
                'attendance_rate' => '94.2%'
            ]
        ]);
    }

    public function trends(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'weekly' => [92, 94, 95, 93, 94, 88, 90],
                'monthly' => [91, 93, 94, 95]
            ]
        ]);
    }

    public function liveAttendance(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => []
        ]);
    }
}

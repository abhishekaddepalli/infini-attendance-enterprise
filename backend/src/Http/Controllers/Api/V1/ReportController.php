<?php

namespace Infini\Attendance\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReportController extends Controller
{
    public function attendanceReport(Request $request) { return response()->json(['status' => 'success', 'data' => []]); }
    public function payrollReport(Request $request) { return response()->json(['status' => 'success', 'data' => []]); }
    public function generateCustom(Request $request) { return response()->json(['status' => 'success', 'message' => 'Custom report generated']); }
    public function export(Request $request, $report) { return response()->json(['status' => 'success', 'download_url' => '']); }
}

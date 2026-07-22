<?php

namespace Infini\Attendance\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PayrollController extends Controller
{
    public function process(Request $request) { return response()->json(['status' => 'success', 'message' => 'Payroll processing started']); }
    public function salarySlips(Request $request, $employee) { return response()->json(['status' => 'success', 'data' => []]); }
    public function downloadSlip(Request $request, $slip) { return response()->json(['status' => 'success', 'download_url' => '']); }
    public function summary(Request $request) { return response()->json(['status' => 'success', 'data' => []]); }
    public function overtime(Request $request) { return response()->json(['status' => 'success', 'data' => []]); }
}

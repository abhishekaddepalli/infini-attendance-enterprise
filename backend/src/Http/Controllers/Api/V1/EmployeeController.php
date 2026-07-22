<?php

namespace Infini\Attendance\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(['status' => 'success', 'data' => []]);
    }

    public function store(Request $request)
    {
        return response()->json(['status' => 'success', 'message' => 'Employee created']);
    }

    public function show(Request $request, $employee)
    {
        return response()->json(['status' => 'success', 'data' => ['id' => $employee]]);
    }

    public function update(Request $request, $employee)
    {
        return response()->json(['status' => 'success', 'message' => 'Employee updated']);
    }

    public function destroy(Request $request, $employee)
    {
        return response()->json(['status' => 'success', 'message' => 'Employee deleted']);
    }

    public function bulkImport(Request $request)
    {
        return response()->json(['status' => 'success', 'message' => 'Import started']);
    }

    public function export(Request $request)
    {
        return response()->json(['status' => 'success', 'download_url' => '']);
    }
}

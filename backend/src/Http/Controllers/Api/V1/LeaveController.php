<?php

namespace Infini\Attendance\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LeaveController extends Controller
{
    public function requests(Request $request)
    {
        return response()->json(['status' => 'success', 'data' => []]);
    }

    public function apply(Request $request)
    {
        return response()->json(['status' => 'success', 'message' => 'Leave applied']);
    }

    public function approve(Request $request, $leave)
    {
        return response()->json(['status' => 'success', 'message' => 'Leave approved']);
    }

    public function reject(Request $request, $leave)
    {
        return response()->json(['status' => 'success', 'message' => 'Leave rejected']);
    }

    public function balance(Request $request, $employee)
    {
        return response()->json(['status' => 'success', 'data' => ['casual' => 12, 'sick' => 7]]);
    }

    public function types(Request $request)
    {
        return response()->json(['status' => 'success', 'data' => []]);
    }

    public function holidays(Request $request)
    {
        return response()->json(['status' => 'success', 'data' => []]);
    }
}

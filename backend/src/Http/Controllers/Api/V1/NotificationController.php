<?php

namespace Infini\Attendance\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NotificationController extends Controller
{
    public function index(Request $request) { return response()->json(['status' => 'success', 'data' => []]); }
    public function markAsRead(Request $request, $id) { return response()->json(['status' => 'success', 'message' => 'Notification marked as read']); }
}

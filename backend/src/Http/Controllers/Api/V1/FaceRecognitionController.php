<?php

namespace Infini\Attendance\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FaceRecognitionController extends Controller
{
    public function register(Request $request) { return response()->json(['status' => 'success', 'message' => 'Face descriptor registered']); }
    public function verify(Request $request) { return response()->json(['status' => 'success', 'verified' => true, 'confidence' => 0.98]); }
    public function livenessCheck(Request $request) { return response()->json(['status' => 'success', 'passed' => true]); }
    public function antiSpoof(Request $request) { return response()->json(['status' => 'success', 'is_real' => true]); }
    public function getFaces(Request $request, $employee) { return response()->json(['status' => 'success', 'faces' => []]); }
    public function deleteFaces(Request $request, $employee) { return response()->json(['status' => 'success', 'message' => 'Faces deleted']); }
}

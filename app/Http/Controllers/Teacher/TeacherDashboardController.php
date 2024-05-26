<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest;
use App\Services\LeaveService;
use Illuminate\Http\JsonResponse;

class TeacherDashboardController extends Controller
{
    public $leaveService;

    public function __construct(LeaveService $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    public function requestLeaveApplication(LeaveRequest $request)
    {
        try {
            $leave_request =  $this->leaveService->leaveRequest($request);
            return response()->json(['message' => 'Leave application submitted successfully.','data' => $leave_request],200);
        }catch(\Exception $e){
            return response()->json(['message' => 'Leave application processed to fail' ,'error' => $e->getMessage()],500);
        }
    }

    public function showLeaveRequest()
    {
        try {
            $leave_request = $this->leaveService->showLeaveRequest();
            return response()->json(['message' => 'Leave application List', 'data' => $leave_request]);
        }catch (\Exception $e){
            return response()->json(['message' => 'You did dont apply for leave', 'error' => $e->getMessage()],500);
        }
    }
}

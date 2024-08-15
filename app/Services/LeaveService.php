<?php

namespace App\Services;

use App\Models\LeaveApplication;

class LeaveService
{
    public function leaveRequest( $request)
    {
        $leave_request = new LeaveApplication();
        $leave_request->user_id = auth()->user()->id;
        $leave_request->leave_type = $request->leave_type;
        $leave_request->date_from = $request->date_from;
        $leave_request->date_to = $request->date_to;
        $leave_request->phone_number = $request->phone_number;
        $leave_request->reason = $request->reason;
        $leave_request->save();
        return $leave_request;
    }

    public function showLeaveRequest()
    {
        $user_id = auth()->user()->id;
        return LeaveApplication::where('user_id',$user_id)->paginate(12);
    }
}

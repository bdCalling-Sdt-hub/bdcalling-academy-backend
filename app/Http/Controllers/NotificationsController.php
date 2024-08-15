<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationsController extends Controller
{
    public function notifications()
    {
        // Get the authenticated user
        $user = auth()->user();

        // Get all notifications
        $notifications = $user->notifications;

        return response()->json(['stuatus'=>'success','notification'=>$notifications], 200);
    }

    public function markAsRead($id)
    {
        // Get the authenticated user
        $user = auth()->user();

        // Find the notification
        $notification = $user->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['status'=>'success',$notification]);
        }

        return response()->json(['status'=>'error','message'=>'User not found'], 401);
    }

    public function destroy($id)
    {
        // Get the authenticated user
        $user = auth()->user();

        // Find the notification
        $notification = $user->notifications()->find($id);

        if ($notification) {
            $notification->delete();
            return redirect()->back()->with('status', 'Notification deleted successfully!');
        }

        return response()->json(['status'=>'error','message'=>'User not found'], 401);
    }

    public function readNotificationById(Request $request)
    {
        $notification = DB::table('notifications')->find($request->id);
        if ($notification) {
            $notification->read_at = Carbon::now();
            DB::table('notifications')->where('id', $notification->id)->update(['read_at' => $notification->read_at]);
            return response()->json([
                'status' => 'success',
                'message' => 'Notification read successfully.',
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Notification not found',
            ], 404);
        }
    }
}

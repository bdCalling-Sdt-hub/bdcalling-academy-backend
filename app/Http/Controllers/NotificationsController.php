<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}

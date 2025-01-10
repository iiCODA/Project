<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{


    public function getNotifications(Request $request)
{
    $user = $request->user();

    $notifications = $user->notifications;

    return response()->json($notifications);
}


public function markAsRead(Request $request, $id)
{
    $user = $request->user();

    $notification = $user->notifications()->findOrFail($id);

    $notification->markAsRead();

    return response()->json(['message' => 'Notification marked as read']);
}


}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationsResource;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $notifications = auth()->user()->allNotifications;
            return response()->json(['data' => NotificationsResource::collection($notifications)]);
        }
        return view('Notifications.index');
    }
}

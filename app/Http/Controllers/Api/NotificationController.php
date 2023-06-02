<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationsResource;
use App\Models\Attachment;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class NotificationController extends Controller
{
    public function markSeen()
    {
//return request()->all();
        $notification = Notification::where('notification_id', request()->id)->first();
        $notification->notification_status = 1;
        $notification->notification_read_at = Carbon::now();
        $notification->save();

        if (request()->ajax()) {
            return response()->json(['data' => new NotificationsResource($notification)]);
        }
        if ($notification->notification_type == 'request') {
            return redirect()->route('employee-requests-edit', $notification->notifiable_id);
        }

        if ($notification->notification_type == 'contract') {
            return redirect()->route('employees-contracts-edit', $notification->notifiable_id);
        }

        if ($notification->notification_type == 'attachment') {
            $attachment = Attachment::where('attachment_id', $notification->notifiable_id)->first();
//            $newUrl = asset('Files/' . $attachment->attachment_file_url);
//            session()->flash('newurl', $newUrl);
//            return redirect()->back();
            return redirect()->route('employees.edit', $attachment->transaction_id);
        }

        if ($notification->notification_type == 'waybill-car') {
            return redirect()->route('Waybill.edit_car', $notification->notifiable_id);
        }

    }


    public function markAllAsRead()
    {
        $notifications = auth()->user()->notifications;
        foreach ($notifications as $notification) {
            $notification->notification_status = 1;
            $notification->save();
        }

        return response()->json(['data' => 'success']);
    }
}

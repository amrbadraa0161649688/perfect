<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'notification_id' => $this->notification_id,
            'notification_data' => $this->notification_data,
            'notification_date' => Carbon::parse($this->created_date)->format('d-m-Y'),
            'notification_status' => $this->notification_status == 1 ? 'مقروءه' : 'غير مقروءه',
            'notification_type' => $this->notification_type,
            'notification_status_f' => $this->notification_status
        ];
    }
}

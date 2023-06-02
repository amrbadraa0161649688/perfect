<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $primaryKey = 'notification_id';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $fillable = ([

        'company_group_id',
        'company_id',
        'notification_type',
        'notification_app_type',
        'notification_user_id',
        'notification_email_send',
        'notification_data',
        'notification_status',
        'notification_read_at',
        'created_date',
        'updated_date',
        'notifiable_id'

    ]);


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $primaryKey = 'attachment_id';

    protected $dates = ['expire_date'];

    protected $fillable = ['attachment_name', 'attachment_type', 'issue_date', 'expire_date',
        'issue_date_hijri', 'expire_date_hijri', 'copy_no', 'attachment_file_url', 'attachment_data',
        'app_menu_id', 'transaction_id', 'created_user', 'updated_user'];


    public function applicationMenu()
    {
        return $this->belongsTo('App\Models\ApplicationsMenu', 'app_menu_id');
    }


    public function userCreated()
    {
        return $this->belongsTo('App\Models\User', 'created_user');
    }

    public function attachmentType()
    {
        return $this->belongsTo(SystemCode::class, 'attachment_type');
    }

    public function attachmentType_2()
    {
        return $this->belongsTo('App\Models\SystemCode', 'attachment_type');
    }

    public function getAttachmentFileAttribute()
    {
        return asset('Files/' . $this->attachment_file_url);
    }

    public function getIdAttribute()
    {
        return $this->attachment_id;
    }


}

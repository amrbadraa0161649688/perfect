<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $primaryKey = 'permission_id';

    protected $fillable = ['company_group_id', 'company_id', 'job_id', 'app_menu_id', 'permission_name', 'permission_name_ar',
        'permission_name_en', 'permission_gurad_name', 'permission_view', 'permission_add', 'permission_update', 'permission_delete',
        'permission_print', 'permission_approve', 'permission_status', 'updated_user', 'created_user'];

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function applicationMenu()
    {
        return $this->belongsTo('App\Models\ApplicationsMenu', 'app_menu_id');
    }
}

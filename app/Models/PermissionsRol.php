<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionsRol extends Model
{
    use HasFactory;

    protected $primaryKey=['rols_id'];

    protected $fillable=[

        'company_group_id',
        'company_id',
        'rols_name_ar',
        'rols_name_en',
        'rols_gurad_name',
        'created_at',
        'updated_at',
        'created_user',
        'updated_user',
        'rols_status',
        'app_id',

    ];


}

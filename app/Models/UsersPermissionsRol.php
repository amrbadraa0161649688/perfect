<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersPermissionsRol extends Model
{
    use HasFactory;

    protected $table='users_permissions_rols';

    protected $primaryKey='user_permission_rol_id';

    protected $fillable=[

        'user_id',
        'rols_id',
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
        'user_id_created',
        'user_id_updated',

    ];
}

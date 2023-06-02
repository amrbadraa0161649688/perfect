<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;

class RequestStatus extends Model
{
    use PermissionSeederTrait;
    protected $table = 'request_status';
    protected $guarded = [];
}

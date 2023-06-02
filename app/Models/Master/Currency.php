<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;


class Currency extends Model implements PermissionSeederContract
{
    use PermissionSeederTrait,HasFilter;
    protected $table = 'currencies';
    protected $guarded = [];
    


}

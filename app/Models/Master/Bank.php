<?php

namespace App\Models\Master;


use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;

class Bank extends Model implements PermissionSeederContract
{
    use PermissionSeederTrait;

    protected $table = 'banks';
    protected $guarded = [];

}

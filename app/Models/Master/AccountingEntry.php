<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;

class AccountingEntry extends Model implements PermissionSeederContract
{
    use PermissionSeederTrait;
    protected $table = 'accounting_entries';
    protected $guarded = [];

    public function getPermissionDisplayName(){
        return "Accounting Entry";
    }
}

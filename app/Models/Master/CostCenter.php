<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;

use App\Models\Master\Account;


class CostCenter extends Model implements PermissionSeederContract
{
    use PermissionSeederTrait,HasFilter;
    protected $table = 'cost_centers';
    protected $fillable = ['name','code'];

    //
    public function accounts(){
        return $this->belongsToMany(Account::class,'cost_center_accounts','cost_center_id','account_id');
    }

    public static function map($item){
        return [
            'name' => $item->name ?? '',
            'code' => $item->code ?? '',
            'accounts'=>$item->accounts->map(function($account){
                return [
                    'id'=>$account->id,
                    'name'=>$account->name,
                    'code'=>$account->code,
                    'code_name'=>$account->getAccountCodeName()
                ];
            })
        ];
    }



}

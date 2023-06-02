<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFilter;

use App\Models\Master\MainType;
use App\Models\Master\CostCenter;


class Account extends Model
{
    use HasFilter;
    protected $table = 'accounts';
    protected $guarded = [];




    /***
     * nature values (c,d) c=>refer to credit &&  d=> refer to debit
     * appearance values (i,b) i=>refer to income list &&  b=> refer to budget
     *
    */



    public function mainType(){

        return $this->belongsTo(Account::class,'main_type_id','id');
    }

    public function parent(){
        return $this->belongsTo(Account::class,'parent_id','id');
    }
    public function childsAcounts(){
        return $this->hasMany(Account::class,'parent_id','id');
    }

    public function childs($id){
        return Account::where('parent_id',$id)->get();
    }


    public function costCenters(){
        return $this->belongsToMany(CostCenter::class,'cost_center_accounts','account_id','cost_center_id');
    }

    public static function allMainAccounts()
    {
        return self::whereNull('parent_id')->get();
    }

    public function getAccountCodeName(){
        return $this->code . " ( $this->name ) ";// "$this->name ($this->code) ";
    }

}

<?php

namespace App\Models;
use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreItem extends Model
{
    
    use HasFactory,CompanyTrait; 
    protected $table = 'store_item';
    protected $primaryKey = 'item_id';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';


    public function unit(){

        return $this->belongsTo('App\Models\SystemCode', 'item_unit');

    }

    public function branch(){

        return $this->belongsTo('App\Models\Branch', 'branch_id');

    }

    public function itemCategory(){

        return $this->belongsTo('App\Models\SystemCode', 'item_category');

    }

    public function getItemName(){
    	if(app()->getLocale() == 'ar')
            return $this->item_name_a;
        return $this->item_name_e;
	}

    public function alterItem1($branch)
    {
        return $this->belongsTo('App\Models\StoreItem', 'item_code_1','item_code')->where('branch_id','=',$branch)->first();
    }

    public function alterItem2($branch)
    {
        return $this->belongsTo('App\Models\StoreItem', 'item_code_2','item_code')->where('branch_id','=',$branch)->first();
    }
    



    

}

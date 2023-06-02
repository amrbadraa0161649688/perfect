<?php

namespace App\Models;

use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetails extends Model
{
    use HasFactory, CompanyTrait;
    protected $table = 'store_dt';
    protected $primaryKey = 'store_dt_id';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';


    public function purchase()
    {

        return $this->belongsTo('App\Models\Purchase', 'store_hd_id');

    }

    public function item()
    {

        return $this->belongsTo('App\Models\StoreItem', 'store_vou_item_id');

    }

    public function discType()
    {

        return $this->belongsTo('App\Models\SystemCode', 'store_vou_disc_type', 'system_code_id');

    }


    public function storeVouType()
    {

        return $this->belongsTo('App\Models\SystemCode', 'store_vou_type');

    }

    public function items()
    {
        return $this->hasMany('App\Models\StoreDtItem', 'store_dt_id');
    }


}

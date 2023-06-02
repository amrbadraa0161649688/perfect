<?php

namespace App\Models;

use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockingDetails extends Model
{
    use HasFactory,CompanyTrait;
    protected $table = 'store_stocking_dt';
    protected $primaryKey = 'store_stocking_dt_id';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    const store_vou_date = 'store_vou_date';

    protected $guarded = [];

    public function stocking(){
        return $this->belongsTo('App\Models\Stocking', 'store_stocking_hd_id');
    }

    public function item(){
        return $this->belongsTo('App\Models\StoreItem', 'store_vou_item_id');
    }



}

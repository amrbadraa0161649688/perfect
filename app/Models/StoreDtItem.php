<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreDtItem extends Model
{
    use HasFactory;

    protected $table = 'store_dt_item';
    protected $primaryKey = 'store_dt_item_id';

    public $timestamps = false;

    protected $guarded = [];

    public function storeDt()
    {
        return $this->belongsTo('App\Models\PurchaseDetails', 'store_dt_id');
    }

    public function storeItem()
    {
        return $this->belongsTo('App\Models\StoreItem', 'item_id_dt');
    }
}

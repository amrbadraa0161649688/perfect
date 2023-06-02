<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceListDt extends Model
{
    use HasFactory;

    protected $primaryKey = 'price_list_dt_id';

    protected $table = 'price_list_dt';

    protected $fillable = ['price_list_id', 'company_group_id', 'company_id', 'item_id','price_factor',
        'customer_id', 'max_fees', 'min_fees', 'cost_fees', 'distance_time', 'distance_fees',
        'distance', 'loc_from', 'loc_to', 'created_user', 'updated_user'];


    public function priceListHd()
    {
        return $this->belongsTo('App\Models\PriceListHd', 'price_list_id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\SystemCode', 'item_id');
    }

}

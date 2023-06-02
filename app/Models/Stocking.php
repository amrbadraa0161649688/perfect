<?php

namespace App\Models;

use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Stocking extends Model
{
    use HasFactory,CompanyTrait;
    protected $table = 'store_stocking_hd';
    protected $primaryKey = 'store_stocking_hd_id';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    const store_vou_date = 'store_vou_date';

    protected $guarded = [];

    public function details()
    {
        return $this->hasMany('App\Models\StockingDetails', 'store_stocking_hd_id')->where('isdeleted', '=', 0);
    }

    public function storeCategory()
    {

        return $this->belongsTo('App\Models\SystemCode', 'store_category_type');

    }

    public function Branch()
    {

        return $this->belongsTo('App\Models\Branch', 'branch_id');

    }

    public function getVouDate() {
        return (new Carbon($this->store_vou_date))->format('Y-m-d');
    }


}

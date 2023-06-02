<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarRentPriceAdd extends Model
{
    use HasFactory;

    protected $table = 'car_rent_price_add';
    protected $primaryKey = 'rent_list_add_id';

    protected $fillable = [
        'rent_list_id', 'company_group_id', 'company_id', 'customer_id', 'rent_add_id',
        'rent_add_price', 'add_qty_value'
    ];


    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SystemCode::class, 'rent_add_id');
    }

    public function priceList(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CarPriceListHd::class, 'rent_list_id');
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

}

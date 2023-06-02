<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDt extends Model
{
    use HasFactory;

    protected $table = 'invoice_details';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'created_date';

    protected $primaryKey = 'invoice_details_id';

//    protected $dates = ['invoice_to_date', 'invoice_from_date'];

    protected $fillable = ['invoice_id', 'invoice_item_id', 'company_group_id', 'company_id',
        'branch_id', 'invoice_item_unit', 'invoice_item_notes', 'invoice_item_quantity', 'invoice_item_price',
        'invoice_item_amount', 'invoice_item_vat_rate', 'invoice_item_vat_amount',
        'invoice_discount_type', 'invoice_discount_amount', 'invoice_discount_total',
        'invoice_total_amount', 'invoice_reference_no', 'invoice_from_date', 'invoice_to_date',
        'created_user', 'updated_user', 'item_account_id'];


//    protected $casts = [
//        'invoice_reference_no' => 'integer',
//    ];

    public function invoiceHd()
    {
        return $this->belongsTo('App\Models\InvoiceHd', 'invoice_id');
    }

    public function waybill()
    {
        return $this->belongsTo('App\Models\WaybillHd', 'invoice_reference_no');
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Account', 'item_account_id');
    }

    public function invoiceItemSetting()
    {
        return $this->belongsTo('App\Models\SystemCode', 'invoice_item_id');
    }

    

    public function invoiceItemUnit()
    {
        return $this->belongsTo('App\Models\SystemCode', 'invoice_item_unit');
    }


    public function userCreated()
    {
        return $this->belongsTo('App\Models\User', 'created_user');
    }

    public function userUpdated()
    {
        return $this->belongsTo('App\Models\User', 'updated_user');
    }
}

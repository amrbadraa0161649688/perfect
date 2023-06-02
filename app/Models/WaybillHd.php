<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kyslik\ColumnSortable\Sortable;
use App\Http\Controllers\Naql\NaqlWayAPIController;

class WaybillHd extends Model
{
    use HasFactory;
    use Sortable;

    protected $table = 'waybill_hd';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $appends = ['carsCount'];

    protected $primaryKey = 'waybill_id';
    protected $dates = ['waybill_delivery_date', 'waybill_load_date', 'waybill_unload_date',
        'waybill_delivery_expected'];

    public $sortable = ['waybill_ticket_no', 'created_date', 'customer_id', 'waybill_code'];


    protected $guarded = [];

    public function waybillReturn()
    {
        return $this->belongsTo('App\Models\WaybillHd', 'waybill_return_no');
    }

    public function statusM()
    {
        return $this->belongsToMany('App\Models\SystemCode', 'waybill_status', 'waybill_id',
            'status_id')->withPivot('created_at')->orderBy('status_date');
    }

    public function trip_details()
    {
        return $this->hasMany('App\Models\TripDt', 'waybill_id');
    }

    public function waybillActive()
    {
        // return $this->hasOne('App\Models\WaybillHd', 'waybill_id')
        //     ->where('emp_contract_is_active', 1);
        return $this->belongsTo('App\Models\SystemCode', 'waybill_status', 'system_code_id')->where('system_code', '41004');
    }

    public function waybillActives()
    {
        // return $this->hasOne('App\Models\WaybillHd', 'waybill_id')
        //     ->where('emp_contract_is_active', 1);
        return $this->belongsTo('App\Models\SystemCode', 'waybill_status', 'system_code_id')->whereIn('system_code', ['41004', '41007', '41006', '41008']);
    }

    public function getDueAmountAttribute()
    {
        return $this->waybill_total_amount - $this->waybill_paid_amount;
    }

    public function trip()
    {
        return $this->belongsTo('App\Models\TripHd', 'waybill_trip_id');
    }

    public function detailsTrip()
    {
        return $this->belongsTo('App\Models\TripHd', 'trip_hd_id', 'waybill_trip_id');
    }

    public function getCustomerContractIdAttribute()
    {
        $contract_id = PriceListHd::where('customer_id', request()->customer_id)
            ->where('price_list_code', $this->customer_contract)->first()->price_list_id;

        return $contract_id;
    }

    public function invoiceDts()
    {
        return $this->hasMany('App\Models\InvoiceDt', 'invoice_reference_no');
    }

    public function purchaseInvoice()
    {
        return $this->belongsTo('App\Models\InvoiceHd', 'purchase_invoice_id');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Models\InvoiceHd', 'waybill_invoice_id');
    }


    public function journalHd()
    {
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id')
            ->where('journal_category_id', 34);
    }

    public function truck()
    {
        return $this->belongsTo('App\Models\Trucks', 'waybill_truck_id');
    }

    public function driver()
    {
        return $this->belongsTo('App\Models\Employee', 'waybill_driver_id');
    }

    public function getWaybillDeliveryExpectedAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d\TH:i');
    }

    public function getWaybillDeliveryDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d\TH:i');
    }


    public function getWaybillUnloadDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d\TH:i');
    }

    public function getWaybillLoadDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d\TH:i');
    }

//    public function setWaybillDeliveryExpectedAttribute($value)
//    {
//        $this->attributes['waybill_delivery_expected'] = Carbon::createFromFormat('Y-m-d\TH:i', $value)->format('Y-m-d H:i');
//    }
//
//    public function setWaybillDeliveryDateAttribute($value)
//    {
//        $this->attributes['waybill_delivery_date'] = Carbon::createFromFormat('Y-m-d\TH:i', $value)->format('Y-m-d H:i');
//    }
//
//    public function setWaybillUnloadDateAttribute($value)
//    {
//        $this->attributes['waybill_unload_date'] = Carbon::createFromFormat('Y-m-d\TH:i', $value)->format('Y-m-d H:i');
//    }

//    public function setWaybillLoadDateAttribute($value)
//    {
//        $this->attributes['waybill_load_date'] = Carbon::createFromFormat('Y-m-d\TH:i', $value)->format('Y-m-d H:i');
//    }


    public function status()
    {
        return $this->belongsTo('App\Models\SystemCode', 'waybill_status');
    }


    public function statusTrip()
    {
        return $this->belongsTo('App\Models\SystemCode', 'waybill_trip_status');
    }


    public function payment()
    {
        return $this->belongsTo('App\Models\SystemCode', 'waybill_payment_method');
    }

    public function paymentmethod()
    {
        return $this->belongsTo('App\Models\SystemCodeCode', 'waybill_payment_method');
    }

    public function companyGroup()
    {
        return $this->belongsTo('App\Models\CompanyGroup', 'company_group_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'supllier_id');
    }

    public function userCreated()
    {
        return $this->belongsTo('App\Models\User', 'created_user');
    }

    public function userUpdated()
    {
        return $this->belongsTo('App\Models\User', 'updated_user');
    }


    public function details()
    {
        return $this->hasOne('App\Models\WaybillDt', 'waybill_hd_id');
    }

    public function waybill()
    {
        return $this->belongsTo('App\Models\WaybillHd', 'waybill_id');
    }

    public function waybillw()
    {
        return $this->hasOne('App\Models\WaybillHd', 'waybill_id');
    }

    public function Wdetails()
    {
        return $this->hasOne('App\Models\WaybillDt', 'waybill_hd_id');
    }

    public function invoiceno()
    {
        return $this->hasOne('App\Models\InvoiceHd', 'invoice_id', 'waybill_invoice_id');
    }


    public function detailsDiesel()
    {
        return $this->hasMany('App\Models\WaybillDt', 'waybill_hd_id');
    }

    public function detailsCar()
    {
        return $this->hasOne('App\Models\WaybillDt', 'waybill_hd_id');
    }

    public function locfrom()
    {
        return $this->belongsTo('App\Models\SystemCode', 'waybill_loc_from');
    }

    public function locTo()
    {
        return $this->belongsTo('App\Models\SystemCode', 'waybill_loc_to');
    }


    public function LocTransit()
    {
        return $this->belongsTo('App\Models\SystemCode', 'waybill_transit_loc_1');
    }

    public function LocPaid()
    {
        return $this->belongsTo('App\Models\SystemCode', 'waybill_loc_paid');
    }


    public function report_url_waybill()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '88001');
    }

    public function report_url_waybill_co()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '88002');
    }

    public function report_url_waybill_exit()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '88005');
    }


    public function report_url_cargo_smal_dt()

    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '910002');
    }

    public function report_url_cargo_print()

    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '910009');
    }

    public function report_url_cargo_print_rent()

    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '910005');
    }

    public function getStrinAsDate($att)
    {
        return (new Carbon($this->attributes[$att]))->format('Y-m-d');
    }

    public function getWaybillSenderMobileAttribute($value)
    {
        return '0' . $value;
    }

    public function waybillCarDts()
    {
        return $this->hasMany('App\Models\waybillDtCar', 'waybill_hd_id');
    }

    public function getCarsCountAttribute()
    {
        $dt = WaybillDt::where('waybill_hd_id', $this->waybill_id)->first();
        return isset($dt) ? $dt->waybill_qut_received_customer : 0;
    }

    public function location()
    {
        return $this->belongsTo(Locations::class, 'user_location_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'transaction_id')->where('attachment_name', 'waybill-car')->where('attachment_type', 88);
    }


    public function getInvoiceUrlAttribute()
    {

        if ($this->waybill_status != 41001 && $this->invoice) {
            $invoice = $this->invoice;
            $url = config('app.telerik_server') . '?rpt=' .
                $invoice->report_url->report_url . '&id=' .
                $invoice->invoice_id . '&lang=ar&skinName=bootstrap';
            return $url;

            $waybill = WaybillHd::where('waybill_id', request()->id)->first();
            $print_waybill = new NaqlWayAPIController();
            $data = $print_waybill->printWaybill($waybill);
            if ($data['statusCode'] == 200) {
                $file_name = 'waybill' . $waybill->waybill_code . '.pdf';
                file_put_contents('Waybills/' . $file_name, $data);
                return asset('Waybills/' . $file_name);
            }
        }
    }

    public function getPetroInsertDataAttribute()
    {
        return
            [
                'plate' => $this->truck->truck_plate_en,
                'trip_number' => $this->waybill_code,
                'max_trip_consumption_rial' => $this->waybill_fees_total,
                'start_date' => $this->getStrinAsDate('waybill_load_date'),
                'end_date' => $this->getStrinAsDate('waybill_unload_date')
            ];
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\CompanyTrait;

class MaintenanceCardDetails extends Model
{
    use HasFactory;
    use HasFactory, CompanyTrait;

    protected $table = 'maintenance_cards_dt';
    protected $primaryKey = 'mntns_cards_dt_id';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $guarded = [];

    public function bond()
    {
        return $this->belongsTo('App\Models\Bond', 'bond_id');
    }

    public function card()
    {

        return $this->belongsTo('App\Models\MaintenanceCard', 'mntns_cards_id');

    }

    public function maintenanceType()
    {
        if (in_array($this->mntns_cards_item_type, [535])) {
            return $this->belongsTo('App\Models\MaintenanceType', 'mntns_cards_item_id');
        } else {
            return;
        }

    }

    public function discType()
    {

        if (in_array($this->mntns_cards_item_type, [535, 537])) {
            return $this->belongsTo('App\Models\SystemCode', 'mntns_cards_disc_type', 'system_code_id');
        } else {
            return;
        }

    }

    public function workshop()
    {

        if (in_array($this->mntns_cards_item_type, [536])) {
            return $this->belongsTo('App\Models\Customer', 'mntns_cards_item_id', 'customer_id');
        } else {
            return;
        }

    }

    public function partItem()
    {
        if (in_array($this->mntns_cards_item_type, [537])) {
            return $this->belongsTo('App\Models\StoreItem', 'mntns_cards_item_id');
        } else {
            return;
        }

    }

    public function tech()
    {
        return $this->hasMany('App\Models\MaintenanceTechnicians', 'mntns_cards_dt_id');
    }

    public function item()
    {

        return $this->belongsTo('App\Models\StoreItem', 'mntns_cards_item_id');

    }

    public function itemMaintenanceType()
    {

        return $this->belongsTo('App\Models\MaintenanceType', 'mntns_cards_item_id');

    }

    public function invoiceDt()
    {
        return $this->hasOne('App\Models\InvoiceDt', 'invoice_reference_no', 'mntns_cards_dt_id');
    }


}

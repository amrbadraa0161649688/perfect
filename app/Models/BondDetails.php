<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BondDetails extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'bonds_details';

    protected $primaryKey = 'bond_id_dt';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    public function invoice()
    {
        return $this->belongsTo('App\Models\InvoiceHd', 'transaction_id');
    }

    public function bond()
    {
        return $this->belongsTo('App\Models\Bond', 'bond_id');
    }

    public function bondDocType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'bond_doc_type');
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Account', 'bond_acc_id');
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'bond_emp_id');
    }


    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'bond_branch_id');
    }

    public function car()
    {
        return $this->belongsTo('App\Models\Trucks', 'bond_car_id');
    }
}

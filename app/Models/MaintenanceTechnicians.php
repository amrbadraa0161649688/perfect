<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\CompanyTrait;

class MaintenanceTechnicians extends Model
{
    use HasFactory;
    use HasFactory,CompanyTrait; 
    protected $table = 'maintenance_technicians';
    protected $primaryKey = 'mntns_tech_id'; 
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    public function card(){

        return $this->belongsTo('App\Models\MaintenanceCard', 'mntns_cards_id');

    }

    public function emp(){

        return $this->belongsTo('App\Models\Employee', 'mntns_tech_emp_id');

    }

    public function cardDetails(){

        return $this->belongsTo('App\Models\MaintenanceCardDetails', 'mntns_cards_dt_id');

    }

}

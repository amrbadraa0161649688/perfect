<?php

namespace App\Models;
use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceType extends Model
{
    use HasFactory,CompanyTrait; 
    

    protected $table = 'maintenance_types';
    protected $primaryKey = 'mntns_type_id';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    
    public function typeCat(){

        return $this->belongsTo('App\Models\SystemCode', 'mntns_type_category','system_code_id');

    }

    public function card(){

        return $this->belongsTo('App\Models\SystemCode', 'mntns_card_type','system_code_id');

    }

    public function getMaintenanceTypeName(){
    	if(app()->getLocale() == 'ar')
            return $this->mntns_type_name_ar;
        return $this->mntns_type_name_en;
	}
}



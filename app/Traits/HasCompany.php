<?php

namespace App\Traits;

use App\Models\Master\Company;
use Illuminate\Support\Facades\Auth;

trait HasCompany{

    public function company(){
        return $this->belongsTo(Company::class,'company_id','id');
    }

    public function scopeForUserCompany($query){
        $user = Auth::user();
        $companies = $user->getCompanies();
        return $query->whereIn('company_id',$companies->pluck('id')->all());
    }

}

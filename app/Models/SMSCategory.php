<?php

namespace App\Models;
use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSCategory extends Model
{
    use HasFactory,CompanyTrait;

    protected $table = 'sms_category';
    protected $primaryKey = 'sms_category_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function provider(){
        return $this->belongsTo('App\Models\SMSProviders', 'sms_provider_id');
    }

}

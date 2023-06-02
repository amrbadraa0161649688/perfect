<?php

namespace App\Models;
use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSQueue extends Model
{
    use HasFactory,CompanyTrait; 
    protected $table = 'sms_queue';
    protected $primaryKey = 'sms_queue_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public function provider(){
        return $this->belongsTo('App\Models\SMSProviders', 'sms_provider_id');
    }

    public function category(){
        return $this->belongsTo('App\Models\SMSCategory', 'sms_category_id');
    }
}

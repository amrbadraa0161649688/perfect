<?php

namespace App\Models;
use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSProviders extends Model
{
    use HasFactory,CompanyTrait; 
    protected $table = 'sms_provider';
    protected $primaryKey = 'sms_provider_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}

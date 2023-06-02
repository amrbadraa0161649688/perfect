<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDetailsStr extends Model
{
    use HasFactory;

    protected $primaryKey = 'str_id';
    const CREATED_AT = 'created_date';
    protected $table = 'companies_details_str';

    protected $fillable=['str_id','company_id','str_type','str_code','created_user'];
}

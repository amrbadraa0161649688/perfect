<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyApp extends Model
{
    use HasFactory;

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $fillable = ['company_group_id', 'company_id', 'app_id',
        'co_app_is_active'];

    protected $table = 'companies_app';

    protected $primaryKey='company_app_id';

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function companyGroup()
    {
        return $this->belongsTo('App\Models\CompanyGroup', 'company_group_id');
    }

    public function application()
    {
        return $this->belongsTo('App\Models\Application', 'app_id');
    }
}

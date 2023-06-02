<?php

namespace App\Models;
use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Reports extends Model
{
    
    use HasFactory,CompanyTrait; 
    use Sortable;

    protected $table = 'companies_menu_report';
    protected $primaryKey = 'report_id';
    public $sortable =['report_code'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
 protected $fillable = [
        'report_code','app_menu_id', 'company_id','report_url'

    ];

    
	



    

}

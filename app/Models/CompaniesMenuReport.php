<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompaniesMenuReport extends Model
{
    use HasFactory;

    protected $table = 'companies_menu_report';

    protected $primaryKey = 'report_id';

    protected $guarded = [];
}

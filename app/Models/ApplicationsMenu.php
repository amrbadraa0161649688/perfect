<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ApplicationsMenu extends Model
{
    use HasFactory;
    use Sortable;
    
    public $timestamps = false;

    protected $primaryKey = 'app_menu_id';

    protected $table = 'applications_menu';

    public $sortable =['app_id','app_menu_order'];
    protected $fillable = ['app_menu_id', 'app_id', 'app_menu_code', 'app_menu_order', 'app_menu_name_ar',
        'app_menu_name_en', 'app_menu_url', 'app_menu_icon', 'app_menu_color', 'app_menu_is_active'];
}

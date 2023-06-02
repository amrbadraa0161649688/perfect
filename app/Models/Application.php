<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'app_id';

    protected $fillable = ['app_id', 'app_name_ar', 'app_name_en', 'app_code',
        'app_icon', 'app_status'];

    public function applicationMenu()
    {
        return $this->hasMany('App\Models\ApplicationsMenu', 'app_id');
    }

    public function applicationMenuActive()
    {
        return $this->hasMany('App\Models\ApplicationsMenu', 'app_id')
            ->where('app_menu_is_active', 1)
            ->orderBy('app_menu_order','asc');
    }

    public function applicationMenuVoucher()
    {
        return $this->hasMany('App\Models\ApplicationsMenu', 'app_id')
            ->where('app_menu_is_active', 1)->where('app_menu_code', 'voucher');
    }
}

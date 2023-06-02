<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetsM extends Model
{
    use HasFactory;


    protected $table = 'assets';
    protected $primaryKey = 'asset_id';
//    const CREATED_AT = false;

    public $timestamps = false;

    protected $guarded = [];

    public function manifacturerCompany()
    {
        return $this->belongsTo('App\Models\SystemCode', 'asset_manufacture');
    }

    public function assetOwner()
    {
        return $this->belongsTo('App\Models\Customer', 'asset_owner');
    }

    public function setTrailerPhotoAttribute()
    {
        $attachment = Attachment::where('transaction_id', $this->asset_id)->where('attachment_type', 2)
            ->where('app_menu_id', 48)->first();

        return isset($attachment) ? $attachment->attachment_file_url : '';
    }

    public function truck()
    {
        return $this->hasOne('App\Models\Trucks', 'trucker_id');
    }
}

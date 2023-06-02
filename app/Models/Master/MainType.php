<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

use App\Models\Master\Account;
use App\Traits\HasFilter;

class MainType extends Model
{

    use HasFilter;

    protected $table = 'main_types';
    protected $fillable = ['name_en','name_ar'];

    public function accounts()
    {
       return $this->hasMany(Account::class,'main_type_id','id');
    }
}

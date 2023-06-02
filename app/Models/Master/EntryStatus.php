<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class EntryStatus extends Model
{
    protected $table = 'entry_status';
    protected $fillable = ['name_en','name_ar'];
    
}

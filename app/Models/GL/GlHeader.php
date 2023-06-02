<?php

namespace App\Models\GL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlHeader extends Model
{
    use HasFactory;

    protected $table = 'gl_header';

    protected $primaryKey = 'gl_header_id';

    protected $dates = ['from_date', 'to_date'];

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $fillable = ['company_group_id', 'company_id', 'created_user',
        'acc_level', 'acc_id', 'created_date', 'updated_date', 'from_date', 'to_date'];

    public function glDetails()
    {
        return $this->hasMany('App\Models\GL\GlDetail', 'gl_header_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationHd extends Model
{
    use HasFactory;

    protected $table = 'evaluation_type_hd';
    protected $primaryKey = 'evaluation_id';

    protected $guarded = [];

    public function evaluationDts()
    {
        return $this->hasMany('App\Models\EvaluationDt', 'evaluation_id');
    }
}

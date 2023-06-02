<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationDt extends Model
{
    use HasFactory;

    protected $table = 'evaluation_type_dt';
    protected $primaryKey = 'evaluation_dt_id';

    protected $guarded = [];

    public function evaluationHd()
    {
        return $this->belongsTo('App\Models\EvaluationHd', 'evaluation_id');
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationFileDt extends Model
{
    use HasFactory;


    protected $table = 'evaluation_file_dt';
    protected $primaryKey = 'evaluation_file_dt_id';

    protected $guarded = [];

    public function evaluationTypeHd()
    {
        return $this->belongsTo('App\Models\EvaluationHd', 'evaluation_id');
    }

    public function evaluationTypeDt()
    {
        return $this->belongsTo('App\Models\EvaluationDt', 'evaluation_dt_id');
    }

    public function getDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('d-m-Y');
    }

    public function getTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('H:i');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_user');
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationFileHd extends Model
{
    use HasFactory;


    protected $table = 'evaluation_file_hd';
    protected $primaryKey = 'evaluation_file_id';


    protected $guarded = [];

    public function getDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('d-m-Y');
    }

    public function getTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('H:i');
    }

    public function evaluationFileDts()
    {
        return $this->belongsTo('App\Models\EvaluationFileDt', 'evaluation_file_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }

    public function getEvaluationResultTotalAttribute()
    {
        $evaluation = EvaluationFileDt::where('evaluation_file_id', $this->evaluation_file_id);
        $evaluation_total = $evaluation->count();
        $evaluation_1 = $evaluation->where('evaluation_result', 1)->count();
        $ration = ($evaluation_1 / $evaluation_total) * 5;
        return $ration;
    }
}

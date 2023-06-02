<?php

namespace App\Http\Controllers\Api;

use Alkoumi\LaravelHijriDate\Hijri;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use Carbon\Carbon;
use Hijrian;


class CompanyController extends Controller
{
    public function getBranches()
    {
        $company = Company::where('company_id', request()->company_id)->first();
        $branches = Branch::where('company_id', request()->company_id)->get();
        return response()->json(['data' => $branches, 'jobs' => $company->jobs]);
    }

    public function getDate()
    {
        $date =  request()->date;
        $new_date = Hijri::Date('Y-m-j', $date);  // With optional Timestamp It will return Hijri Date of [$date] => Results "الأحد ، 12 جمادى الأول ، 1442"
        return response()->json(['data' => $new_date]);
    }

    public function getGeorgianDate(){
        Hijrian::gregory(request()->higri_date);
    }


    public function getDifferenceDate()
    {
        $from_date = request()->start_date;
        $to_date = Carbon::now();


        $answer_in_days = $to_date->diff($from_date);

        return response()->json(['day' => $answer_in_days->d, 'year' => $answer_in_days->y, 'month' => $answer_in_days->m]);
    }

}

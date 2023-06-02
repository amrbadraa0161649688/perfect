<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanyGroup;
use Illuminate\Http\Request;

class CompanyGroupController extends Controller
{
    public function getCompanies()
    {
        $company_group = CompanyGroup::where('company_group_id',request()->id)->first();
        return response()->json(['data' => $company_group->companies]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Company;
use App\Models\CompanyGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApplicationsController extends Controller
{
    public function getApplications()
    {
        $company = Company::find(request()->id);
        return response()->json(['data' => $company->apps]);
    }


}

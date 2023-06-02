<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountResource;
use App\Models\Account;
use App\Models\AccountCompany;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\SystemCode;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountTreeController extends Controller
{
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $company_group = CompanyGroup::where('company_group_id', $company->company_group_id)->first();
        $accounts = $company_group->accountsMain;
        $companies = $company_group->companies;

        if (request()->company_id) {
            $company = Company::where('company_id', request()->company_id)
                ->where('company_group_id', $company->company_group_id)->first();
            if (isset($company)) {
                $accounts = $company->accountsMain;
            } else {
                return back()->with(['error' => 'لا يوجد شركه بهذا الكود']);
            }

        }
        $level = 5;
        return view('Accounts.accountTree', compact('accounts','company_group', 'companies', 'level'));
    }

}

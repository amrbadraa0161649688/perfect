@extends('Layouts.master')
@section('content')

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">


                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">

                            <form class="card" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="md-3">
                                                <div class="row  align-items-center">
                                                    <div class="col-md-4">

                                                        @if(session('company_group'))


                                                            <input type="text" class="form-control" readonly value="{{app()->getLocale() == 'ar' ?
                                                        session('company_group')['company_group_ar'] : session('company_group')['company_group_en']}}">
                                                        @else
                                                            <input type="text" class="form-control" readonly value="{{
                                                            app()->getLocale() == 'ar' ? auth()->user()->companyGroup->company_group_ar
                                                            : auth()->user()->companyGroup->company_group_en}}">
                                                        @endif
                                                    </div>

                                                    <div class="col-md-4 my-1">

                                                        <select class="form-control" class="" name="company_id"
                                                                onchange="this.form.submit()">
                                                            <option value="">@lang('home.choose')</option>
                                                            @foreach($companies as $company)

                                                                <option value="{{$company->company_id}}"
                                                                        @if(request()->company_id == $company->company_id)selected @endif>

                                                                    {{app()->getLocale()== 'ar' ? $company->company_name_ar : $company->company_name_en}}

                                                                </option>

                                                            @endforeach
                                                        </select>

                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <a href="{{ route('monthly-salaries.create') }}" class="btn btn-primary w-100">@lang('home.add_monthly_salaries')</a>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>

                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('home.sub_company')</th>
                                        <th>@lang('home.account_period')</th>
                                        <th>@lang('home.emp_count')</th>
                                        <th>@lang('home.total_salaries')</th>
                                        <th>@lang('home.status')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($account_periods as $k=>$account_period)
                                        <tr>
                                            <td>{{$k+1}}</td>
                                            <td>{{app()->getLocale() == 'ar' ? $account_period->company->company_name_ar
                                            : $account_period->company->company_name_en}}</td>
                                            <td>{{app()->getLocale() == 'ar' ? $account_period->acc_period_name_ar
                                            : $account_period->acc_period_name_en}}</td>
                                            <td>{{$account_period->emp_payroll_employee_no}}</td>
                                            <td>{{$account_period->emp_payroll_net_amout}}</td>
                                            <td>{{$account_period->emp_payroll_status}}</td>
                                            <td>
                                                <a href="{{ route('monthly-salaries.show',$account_period->acc_period_id) }}"><i
                                                            class="fa fa-eye text-danger"></i></a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
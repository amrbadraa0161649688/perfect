@extends('Layouts.master')
@section('style')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>
@endsection
@section('content')


    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">


                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">

                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-4">

                                    <label>@lang('home.acc_period')</label>
                                    <input type="text" class="form-control" value="{{app()->getLocale()=='ar' ?
                                     $account_period->acc_period_name_ar : $account_period->acc_period_name_en}}"
                                           readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('home.created_date')</label>
                                    <input type="text" class="form-control"
                                           value="{{$monthly_salaries[0]->created_date}}" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('home.user')</label>
                                    <input type="text" class="form-control"
                                           value="{{app()->getLocale()=='ar' ? auth()->user()->user_name_ar : auth()->user()->user_name_en}}"
                                           readonly>
                                </div>
                            </div>

                            <form action="" class="row">
                                <div class="col-md-4">

                                    <label for="recipient-name"
                                           class="col-form-label "> @lang('home.branch') </label>

                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="branch_id[]">
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->branch_id }}"
                                                    @if(request()->branch_id) @foreach(request()->branch_id as $branch_id)
                                                    @if($branch->branch_id == $branch_id) selected
                                                    @endif @endforeach @endif>
                                                @if(app()->getLocale()=='ar')
                                                    {{ $branch->branch_name_ar }}
                                                @else
                                                    {{  $branch->branch_name_en }}
                                                @endif
                                            </option>
                                        @endforeach

                                    </select>

                                </div>
                                <div class="col-1">
                                    <button class="btn btn-primary" type="submit"
                                            style="margin-top: 35px">@lang('home.filter')</button>
                                </div>
                                <div class="col-md-2">
                                @foreach($report_url_salary as $report_url_salarys)
                                    <a
                                    
                                    href="{{config('app.telerik_server')}}?rpt={{$report_url_salarys->report_url}}&monthly_peroll_id={{$monthly_salaries[0]->period_id}}&lang=ar&skinName=bootstrap"
                                    title="{{trans('print')}}" class="btn btn-primary"  style="margin-top: 35px" id="showReport" target="_blank">
                                    {{trans('طباعه تقرير رواتب الموظفين')}}
                                     </a>
                                     @endforeach
                                </div>

                                <div class="col-md-2">
                                @foreach($report_url_salary_emp as $report_url_salary_emps)
                                    <a
                                    
                                    href="{{config('app.telerik_server')}}?rpt={{$report_url_salary_emps->report_url}}&monthly_peroll_id={{$monthly_salaries[0]->period_id}}&lang=ar&skinName=bootstrap"
                                    title="{{trans('print')}}" class="btn btn-primary"  style="margin-top: 35px" id="showReport" target="_blank">
                                    {{trans('طباعه تقرير راتب موظف ')}}
                                     </a>
                                     @endforeach
                                </div>

                                <div class="col-md-2">

                                @foreach($report_url_salary_h as $report_url_salary_hs)
                                    <a
                                    
                                    href="{{config('app.telerik_server')}}?rpt={{$report_url_salary_hs->report_url}}&monthly_peroll_id={{$monthly_salaries[0]->period_id}}&lang=ar&skinName=bootstrap"
                                    title="{{trans('print')}}" class="btn btn-primary"  style="margin-top: 35px" id="showReport" target="_blank">
                                    {{trans('طباعه تقرير حمايه الاجور')}}
                                     </a>
                                     @endforeach
                                 

                                </div>

                            </form>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0"
                                       id="salaries_table">
                                    <thead>
                                    <tr>
                                        <th colspan="4">@lang('home.data')</th>
                                        <th colspan="9">@lang('home.benefits')</th>
                                        <th colspan="5">@lang('home.deductions')</th>
                                        <th colspan="1">@lang('home.net_salary')</th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('home.code')</th>
                                        <th>@lang('home.name')</th>
                                        <th>@lang('home.direct_date')</th>
                                        {{--benefits.--}}
                                        <th>@lang('home.basic')</th>
                                        <th>@lang('home.housing_allowance')</th>
                                        <th>@lang('home.transfer_allowance')</th>
                                        <th>@lang('home.food_allowance')</th>
                                        <th>@lang('home.nature_allowance')</th>
                                        <th>@lang('home.periodic_bonus')</th>
                                        <th>@lang('home.other_allowances')</th>
                                        <th>@lang('home.additions')</th>
                                        <th>@lang('home.total_benefits')</th>
                                        {{--deductions.--}}
                                        <th>@lang('home.social_insurance')</th>
                                        <th>@lang('home.discounts')</th>
                                        <th>@lang('home.loans')</th>
                                        <th>@lang('home.borrow')</th>
                                        <th>@lang('home.total_deductions')</th>
                                        {{--net salary--}}
                                        <th>@lang('home.net_salary')</th>


                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($monthly_salaries as $monthly_salary)
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>{{$monthly_salary->emp_code}}</td>
                                            <td>{{app()->getLocale() == 'ar' ? $monthly_salary->emp_name_full_ar
                                            : $monthly_salary->emp_name_full_en}}</td>
                                            <td>{{$monthly_salary->emp_direct_date}}</td>

                                            <td>{{$monthly_salary->emp_main_salary}}</td>
                                            <td>{{$monthly_salary->emp_housing_salary}}</td>
                                            <td>{{$monthly_salary->emp_transportation_salary}}</td>
                                            <td>{{$monthly_salary->emp_food_salary}}</td>
                                            <td>{{$monthly_salary->emp_nature_work_salary}}</td>
                                            <td>{{$monthly_salary->emp_allowance_salary}}</td>
                                            <td>{{$monthly_salary->emp_others_salary}}</td>
                                            <td>{{$monthly_salary->emp_add_monthly_salary}}</td>
                                            <td>{{$monthly_salary->emp_due_salary}}</td>

                                            <td>{{$monthly_salary->emp_insurance_salary}}</td>
                                            <td>{{$monthly_salary->emp_deducts_monthly_salary}}</td>
                                            <td>{{$monthly_salary->emp_loans_salary}}</td>
                                            <td>{{$monthly_salary->emp_deducts_salary}}</td>
                                            <td>{{$monthly_salary->emp_deducts_total}}</td>

                                            <td>{{$monthly_salary->emp_net_salary}}</td>
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

@section('scripts')

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

@endsection


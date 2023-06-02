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

                            <form class="card" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="md-3">
                                                <div class="row">


                                                    <div class="col-md-4">

                                                        <label for="recipient-name"
                                                               class="col-form-label "> @lang('home.sub_company') </label>
                                                        <select class="form-control" name="company_id"
                                                                @change="getBranches()" v-model="company_id">
                                                            <option value="">@lang('home.choose')</option>
                                                            @foreach($companies as $company)

                                                                <option value="{{$company->company_id}}"
                                                                        @if(request()->company_id == $company->company_id) selected @endif>

                                                                    {{app()->getLocale()== 'ar' ? $company->company_name_ar : $company->company_name_en}}

                                                                </option>

                                                            @endforeach
                                                        </select>

                                                    </div>

                                                    <div class="col-md-4">

                                                    <label for="recipient-name"
                                                               class="col-form-label "> @lang('home.account_period') </label>
                                                        <select class="form-control" required
                                                                name="acc_period_id" v-model="acc_period_id"
                                                                @change="setAccPeriodId($event.target.value)">
                                                            <option value="" selected>@lang('home.choose')</option>
                                                            <option v-for="account_period in accounts_period"
                                                                    :value="account_period.acc_period_id">
                                                                @if(app()->getLocale()=='ar')
                                                                    @{{ account_period.acc_period_name_ar }}
                                                                @else
                                                                    @{{ account_period.acc_period_name_en }}
                                                                @endif
                                                            </option>
                                                        </select>

                                                    </div>


                                                    <div class="col-md-4">
                                                        <button type="submit"
                                                                class="btn btn-primary text-center mt-4 mr-2 ml-2"
                                                                name="" id="">
                                                            <i class="fa fa-search"></i> @lang('home.show_period')
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>

                        </div>


                        @if(request()->company_id && request()->acc_period_id || request()->branch_id)

                            <form action="">
                                <div class="col-md-4">

                                    <label for="recipient-name"
                                           class="col-form-label "> @lang('home.branch') </label>
                                    <input type="hidden" name="company_id" :value="company_id">
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="branch_id[]">

                                        <option v-for="branch in branches"
                                                :value="branch.branch_id">
                                            @if(app()->getLocale()=='ar')
                                                @{{ branch.branch_name_ar }}
                                            @else
                                                @{{  branch.branch_name_en }}
                                            @endif
                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-4">
                                    <button class="btn btn-primary" type="submit">@lang('home.filter')</button>
                                </div>
                            </form>

                            <div class="card-body">
                                <div class="table-responsive">
                                    
                                    <form action="{{ route('monthly-salaries.store') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="acc_period_id"
                                               value="{{ request()->acc_period_id }}">
                                        <input type="hidden" name="company_id"
                                               value="{{ request()->company_id }}">
                                        <input type="hidden">
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
                                            @foreach($employees as $k=>$employee)
                                                <input type="hidden" name="emp_id[]" value="{{ $employee->emp_id }}">
                                                <tr>
                                                    <th scope="row">{{ $k+1 }}</th>
                                                    <td>{{$employee->emp_code}}</td>
                                                    <td>{{app()->getLocale() == 'ar' ?
                                                $employee->emp_name_full_ar
                                                :$employee->emp_name_full_en}}</td>
                                                    <td>{{$employee->emp_direct_date}}</td>
                                                    <td>

                                                        {{$employee->basicSalary}}
                                                        <input name="emp_main_salary[]" type="hidden"
                                                               value="{{$employee->basicSalary}}">

                                                    </td>
                                                    <td>

                                                        {{$employee->housingSalary}}
                                                        <input name="emp_housing_salary[]" type="hidden"
                                                               value="{{$employee->housingSalary}}">

                                                    </td>
                                                    <td>
                                                        {{$employee->transportSalary}}
                                                        <input name="emp_transportation_salary[]" type="hidden"
                                                               value="{{$employee->transportSalary}}">

                                                    </td>
                                                    <td>

                                                        {{$employee->foodSalary}}
                                                        <input name="emp_food_salary[]" type="hidden"
                                                               value="{{$employee->foodSalary}}">

                                                    </td>
                                                    <td>

                                                        {{$employee->naturalSalary}}
                                                        <input name="emp_nature_work_salary[]" type="hidden"
                                                               value="{{$employee->naturalSalary}}">

                                                    </td>
                                                    <td>

                                                        {{$employee->allowanceSalary}}
                                                        <input name="emp_allowance_salary[]" type="hidden"
                                                               value="{{$employee->allowanceSalary}}">

                                                    </td>
                                                    <td>

                                                        {{$employee->otherSalary}}
                                                        <input name="emp_others_salary[]" type="hidden"
                                                               value="{{$employee->otherSalary}}">

                                                    </td>
                                                    <td>

                                                        {{$employee->employeeSalaryAdds}}
                                                        <input name="emp_add_monthly_salary[]" type="hidden"
                                                               value="{{$employee->employeeSalaryAdds}}">
                                                    </td>
                                                    <td>
                                                        {{$employee->basicSalary + $employee->housingSalary
                                                        + $employee->transportSalary
                                                        + $employee->foodSalary
                                                        +  $employee->naturalSalary
                                                        + $employee->allowanceSalary
                                                        + $employee->otherSalary
                                                        +$employee->employeeSalaryAdds
                                                      }}
                                                        <input name="emp_due_salary[]" type="hidden"
                                                               value="{{$employee->basicSalary + $employee->housingSalary
                                                    + $employee->transportSalary
                                                    + $employee->foodSalary
                                                    +  $employee->naturalSalary
                                                    + $employee->allowanceSalary
                                                    + $employee->otherSalary
                                                    +$employee->employeeSalaryAdds
                                                  }}">
                                                    </td>

                                                    {{--  الاستقطاعات  --}}

                                                    <td>
                                                        {{$employee->insuranceSalary}}
                                                        <input name="emp_insurance_salary[]" type="hidden"
                                                               value="{{$employee->insuranceSalary}}">

                                                    </td>
                                                    <td>
                                                        {{$employee->EmployeeSalarySubs}}
                                                        <input name="emp_deducts_monthly_salary[]" type="hidden"
                                                               value="{{$employee->EmployeeSalarySubs}}">
                                                    </td>

                                                    <td>
                                                        {{$employee->loansSalary}}
                                                        <input name="emp_loans_salary[]" type="hidden"
                                                               value="{{$employee->loansSalary}}">
                                                    </td>

                                                    <td>
                                                        {{$employee->deductsSalary}}
                                                        <input name="emp_deducts_salary[]" type="hidden"
                                                               value="{{$employee->deductsSalary}}">
                                                    </td>

                                                    <td>
                                                        {{  $employee->insuranceSalary
                                                            + $employee->loansSalary
                                                            + $employee->deductsSalary
                                                            + $employee->EmployeeSalarySubs
                                                        }}
                                                        <input name="emp_deducts_total[]" type="hidden"
                                                               value="{{  $employee->insuranceSalary
                                                        + $employee->loansSalary
                                                        + $employee->deductsSalary
                                                        + $employee->EmployeeSalarySubs
                                                    }}">
                                                    </td>


                                                    {{--  صافي الراتب  --}}
                                                    <td>
                                                        {{$employee->basicSalary +$employee->housingSalary
                                                         + $employee->transportSalary
                                                         + $employee->foodSalary
                                                         + $employee->naturalSalary
                                                         + $employee->allowanceSalary
                                                         + $employee->otherSalary
                                                         +  $employee->employeeSalaryAdds

                                                       - ($employee->insuranceSalary
                                                        + $employee->loansSalary
                                                        + $employee->deductsSalary
                                                        +$employee->EmployeeSalarySubs)
                                                        }}
                                                        <input name="emp_net_salary[]" type="hidden"
                                                               value="{{$employee->basicSalary +$employee->housingSalary
                                                     + $employee->transportSalary
                                                     + $employee->foodSalary
                                                     + $employee->naturalSalary
                                                     + $employee->allowanceSalary
                                                     + $employee->otherSalary
                                                     +  $employee->employeeSalaryAdds

                                                   - ($employee->insuranceSalary
                                                    + $employee->loansSalary
                                                    + $employee->deductsSalary
                                                    +$employee->EmployeeSalarySubs)
                                                    }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                        <button type="submit" class="btn btn-primary">
                                            @lang('home.save')
                                        </button>

                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection


@section('scripts')

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>

    <script>
        new Vue({
            el: '#app',
            data: {
                branches: localStorage.getItem('branches') ? JSON.parse(localStorage.getItem('branches')) : {},
                company_id: localStorage.getItem('company_id') ? JSON.parse(localStorage.getItem('company_id')) : '',
                accounts_period: localStorage.getItem('accounts_period') ? JSON.parse(localStorage.getItem('accounts_period')) : {},
                branch_id: localStorage.getItem('branch_id') ? JSON.parse(localStorage.getItem('branch_id')) : '',
                acc_period_id: localStorage.getItem('acc_period_id') ? JSON.parse(localStorage.getItem('acc_period_id')) : ''
            },
            methods: {
                setBranchId(id) {
                    localStorage.setItem('branch_id', id)
                },
                setAccPeriodId(id) {
                    localStorage.setItem('acc_period_id', id)
                },
                getBranches() {
                    localStorage.setItem('company_id', this.company_id)
                    $.ajax({
                        type: 'GET',
                        data: {company_id: this.company_id},
                        url: '{{ route("api.company.branches") }}'
                    }).then(response => {
                        this.branches = response.data
                        localStorage.setItem('branches', JSON.stringify(this.branches))
                    })

                    $.ajax({
                        type: 'GET',
                        data: {company_id: this.company_id},
                        url: '{{ route("api.company.accountPeriod") }}'
                    }).then(response => {
                        this.accounts_period = response.data
                        localStorage.setItem('accounts_period', JSON.stringify(this.accounts_period))
                    })

                },
            }
        });
    </script>
@endsection


















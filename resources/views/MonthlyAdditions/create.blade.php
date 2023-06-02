@extends('Layouts.master')
@section('content')
    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">


                            <h4 class="text-muted mb-4 text-center">@lang('invoice.emp_adds')</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('monthly-additions.store') }}" method="post">
                                @csrf
                                <div class="row">

                                    <div class="col-md-3">

                                        <label class="form-label">@lang('home.companies')</label>
                                        <select class="form-control" v-model="company_id" name="company_id"
                                                @change="getAccountPeriodsOfCompany()" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($companies as $company)
                                                <option value="{{ $company->company_id }}">
                                                    @if(app()->getLocale()=='ar')
                                                        {{ $company->company_name_ar }}
                                                    @else
                                                        {{ $company->company_name_en }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">

                                        <label class="form-label">@lang('home.account_periods')</label>
                                        <select class="form-control" required v-model="acc_period_id"
                                                name="acc_period_id">
                                            <option value="">@lang('home.choose')</option>

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

                                    <div class="col-md-3">

                                        <label class="form-label">@lang('home.created_date')</label>
                                        <input id="date" type="text" class="form-control" readonly>

                                    </div>

                                    <div class="col-md-3">

                                        <label class="form-label">@lang('home.user')</label>
                                        <input type="text" calss="form-control" readonly class="form-control"
                                               value="@if(app()->getLocale()=='ar') {{ auth()->user()->user_name_ar }}
                                               @else {{ auth()->user()->user_name_en }} @endif">

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="mt-3">
                                        <div class="line color-red">

                                        </div>
                                        <div class="line color-red">

                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">

                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th font bold>@lang('home.employee')</th>
                                                            <th>@lang('home.branch')</th>
                                                            <th>@lang('home.addition_type')</th>
                                                            <th>@lang('home.minutes_hours_days')</th>
                                                            <th>@lang('home.salary')</th>
                                                            <th>@lang('home.factor')</th>
                                                            <th>@lang('home.value')</th>
                                                            <th>@lang('home.data')</th>
                                                            <th></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        <tr v-for="(monthly_Addition,index) in monthly_additions">
                                                            <th>@{{index+1}}</th>
                                                            <td width='250px'>
                                                                <select class="form-control" name="emp_id[]" required
                                                                        v-model="monthly_additions[index]['emp_id']"
                                                                        @change="getEmployeeBranch(monthly_additions[index]['emp_id'],index)">
                                                                    <option v-for="employee in employees"
                                                                            :value="employee.emp_id"> @{{
                                                                        employee.emp_name_full_ar }}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            <td width='200px'>
                                                                <input type="text" class="form-control" required
                                                                       v-model="monthly_additions[index]['branch'].branch_name_ar"
                                                                       style="width:170px" readonly>
                                                            </td>
                                                            <td width='200px'>
                                                                <select class="form-control" required
                                                                        style="width:170px"
                                                                        @change="validInputs(monthly_additions[index]['emp_variables_type_id'],index)"
                                                                        v-model="monthly_additions[index]['emp_variables_type_id']"
                                                                        name="emp_variables_type[]">

                                                                    <option v-for="employee_variable in employees_variables"
                                                                            :value="employee_variable.emp_variables_type_id">
                                                                        @{{
                                                                        employee_variable.system_code_type.system_code_name_ar
                                                                        }}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            <td width='130px'>
                                                                <input type="number" style="width:50px"
                                                                       name="emp_variables_days[]"
                                                                       @keyup="getEmployeeVariableFactor(monthly_additions[index]['emp_variables_type_id'],index)"
                                                                       v-model="monthly_additions[index]['days']"
                                                                       :readonly="monthly_additions[index]['days_valid']">

                                                                <input type="number" min="0" style="width:50px"
                                                                       name="emp_variables_hours[]"
                                                                       @keyup="getEmployeeVariableFactor(monthly_additions[index]['emp_variables_type_id'],index)"
                                                                       v-model="monthly_additions[index]['hours']"
                                                                       :readonly="monthly_additions[index]['hours_valid']">

                                                                <input type="number" hidden min="0" style="width:0px"
                                                                       name="emp_variables_minutes[]"
                                                                       @keyup="getEmployeeVariableFactor(monthly_additions[index]['emp_variables_type_id'],index)"
                                                                       v-model="monthly_additions[index]['minutes']"
                                                                       :readonly="monthly_additions[index]['minutes_valid']">
                                                            </td>

                                                            <td>
                                                                <input type="text" name="emp_variables_salary[]"
                                                                       :value="monthly_additions[index]['salary']"
                                                                       style="width:70px"
                                                                       class="form-control" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="number"
                                                                       :value="monthly_additions[index]['factor']"
                                                                       style="width:70px"
                                                                       name="emp_variables_factor[]"
                                                                       class="form-control" readonly>
                                                            </td>
                                                            <td>

                                                                <input type="text" min="0"
                                                                       style="width:70px" required step="0.01"
                                                                       value="0.00"
                                                                       v-model="monthly_additions[index]['value']"
                                                                       name="emp_variables_credit[]"
                                                                       class="form-control"
                                                                       readonly v-if="value_valid">

                                                                <input type="text" min="0"
                                                                       style="width:70px" required step="0.01"
                                                                       value="0.00"
                                                                       v-model="monthly_additions[index]['value']"
                                                                       name="emp_variables_credit[]"
                                                                       @change="getEmployeeVariableFactor(monthly_additions[index]['emp_variables_type_id'],index)"
                                                                       class="form-control" v-else>
                                                            </td>
                                                            <td>
                                                                <textarea class="form-control" style="width:100px"

                                                                          name="emp_variables_notes[]"></textarea>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-primary"
                                                                        @click="addRow()">
                                                                    <i class="fa fa-plus"></i></button>
                                                                <button type="button" class="btn btn-primary"
                                                                        @click="subRow(index)" v-if="index>0">
                                                                    <i class="fa fa-minus"></i></button>
                                                            </td>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <button class="btn btn-primary mt-2" type="submit" id="submit">
                                            @lang('home.save')</button>


                                        <div class="spinner-border" role="status" style="display: none">
                                            <span class="sr-only">Loading...</span>
                                        </div>

                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function displayRow(el) {
            $(el).closest('tr').next().removeClass('d-none');
        }

        function RemoveRow(el) {
            $(el).closest('tr').addClass('d-none');
        }


        $(document).ready(function () {
            $('form').submit(function () {

                $('#submit').css('display', 'none')
                $('.spinner-border').css('display', 'block')

            })

            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (day < 10 ? '0' : '') + day + '/' +
                (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
            ;
            console.log(output)
            $('#date').val(output)

        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {

                acc_period_id: '',
                accounts_period: {},
                company_id: '',
                employees_variables: {},
                employees: {},

                monthly_additions: [{
                    'emp_id': '', 'branch': {}, 'emp_variables_type_id': '',
                    'salary': 0.00, 'factor': '', 'value': 0.00, 'days': 0, 'minutes': 0, 'hours': 0,
                    'days_valid': true, 'minutes_valid': true, 'hours_valid': true
                }],
                code: '',
                value_valid: true
            },
            methods: {
                validInputs(id, index) {
                    this.monthly_additions[index]['factor'] = 0
                    this.monthly_additions[index]['salary'] = 0
                    this.monthly_additions[index]['value'] = 0
                    $.ajax({
                        type: 'GET',
                        data: {id: id},
                        url: '{{ route("api.employee-variable") }}'
                    }).then(response => {
                        this.code = response.data
                        // اضافي ايام اجمالي و اضافي ايام اساسي
                        if (this.code == 27 || this.code == 28  || this.code == 3050 || this.code == 3051) {
                            this.monthly_additions[index]['days_valid'] = false

                            this.monthly_additions[index]['minutes_valid'] = true
                            this.monthly_additions[index]['minutes'] = 0
                            this.monthly_additions[index]['hours_valid'] = true
                            this.monthly_additions[index]['hours'] = 0
                            this.value_valid = true
                        }
                        //اضافي ساعات اساسي و اضافي ساعات اجمالي
                        if (this.code == 29 || this.code == 68 || this.code == 3052 || this.code == 3053 ) {
                            this.monthly_additions[index]['minutes_valid'] = false
                            this.monthly_additions[index]['hours_valid'] = false
                            this.monthly_additions[index]['days_valid'] = true
                            this.monthly_additions[index]['days'] = 0
                            this.value_valid = true
                        }
                        //حوافز شهريه
                        if (this.code == 69 || this.code == 3054) {
                            this.monthly_additions[index]['minutes_valid'] = true
                            this.monthly_additions[index]['hours_valid'] = true
                            this.monthly_additions[index]['days_valid'] = true
                            this.value_valid = false
                        }

                    })
                },

                addRow() {
                    this.monthly_additions.push({
                        'emp_id': '', 'branch': {}, 'emp_variables_type_id': '',
                        'salary': 0.00, 'factor': 0, 'value': 0.00, 'days': 0, 'minutes': 0, 'hours': 0,
                        'days_valid': true, 'minutes_valid': true, 'hours_valid': true
                    })
                },
                subRow(index) {
                    this.monthly_additions.splice(index, 1)
                },
                getAccountPeriodsOfCompany() {
                    $.ajax({
                        type: 'GET',
                        data: {company_id: this.company_id},
                        url: '{{ route("api.company.accountPeriod") }}'
                    }).then(response => {
                        this.accounts_period = response.data
                        this.employees = response.employees
                        this.employees_variables = response.employees_variables
                    })
                },
                getEmployeeBranch(emp_id, index) {
                    $.ajax({
                        type: 'GET',
                        data: {emp_id: emp_id},
                        url: '{{ route("api.employee.branch") }}'
                    }).then(response => {
                        this.monthly_additions[index]['branch'] = response.branch
                        // this.monthly_additions[index]['salary'] = response.salary
                    })
                },
                getEmployeeVariableFactor(emp_variables_type_id, index) {
                    if (this.monthly_additions[index]['emp_id']) {
                        $.ajax({
                            type: 'GET',
                            data: {
                                emp_id: this.monthly_additions[index]['emp_id'],
                                emp_variables_type_id: emp_variables_type_id,
                                salary: this.monthly_additions[index]['salary'],
                                days: this.monthly_additions[index]['days'],
                                minutes: this.monthly_additions[index]['minutes'],
                                hours: this.monthly_additions[index]['hours'],
                                value: this.monthly_additions[index]['value']
                            },
                            url: '{{ route("api.employee.variableFactor") }}'
                        }).then(response => {
                            this.monthly_additions[index]['factor'] = response.data
                            this.monthly_additions[index]['salary'] = response.salary
                            this.monthly_additions[index]['value'] = response.value
                        })
                    }
                },
            },
        });
    </script>
@endsection

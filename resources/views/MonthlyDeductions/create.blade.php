@extends('Layouts.master')
@section('content')
    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">  @lang('home.monthly_deductions') </h3>
                           
                        </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('monthly-deductions.store') }}" method="post">
                                @csrf
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="form-group">
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
                                        </div>
                                        <div class="col-md-3">
                                        <div class="form-group">
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
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.created_date')</label>
                                            <input id="date" type="text" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.user')</label>
                                            <input type="text" calss="form-control" readonly class="form-control"
                                                   value="@if(app()->getLocale()=='ar') {{ auth()->user()->user_name_ar }}
                                                   @else {{ auth()->user()->user_name_en }} @endif">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0">
                                                        <thead>
                                                        <tr>

                                                            <th>#</th>
                                                            <th font bold>@lang('home.employee')</th>
                                                            <th>@lang('home.branch')</th>
                                                            <th>@lang('home.deductions_type')</th>
                                                            <th>@lang('home.minutes_hours_days')</th>
                                                            <th>@lang('home.salary')</th>
                                                            <th>@lang('home.factor')</th>
                                                            <th>@lang('home.value')</th>
                                                            <th>@lang('home.data')</th>
                                                            <th></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="(monthly_deduction,index) in monthly_deductions">
                                                            <th>@{{ index+1 }}</th>
                                                            <td>
                                                                <select class="form-control" name="emp_id[]" required
                                                                        v-model="monthly_deductions[index]['emp_id']"
                                                                        @change="getEmployeeBranch(monthly_deductions[index]['emp_id'],index)">
                                                                    <option v-for="employee in employees"
                                                                            :value="employee.emp_id"> @{{
                                                                        employee.emp_name_full_ar }}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       v-model="monthly_deductions[index]['branch'].branch_name_ar"
                                                                       readonly>
                                                            </td>
                                                            <td>
                                                                <select class="form-control" required
                                                                        @change="validInputs(monthly_deductions[index]['emp_variables_type_id'],index)"
                                                                        v-model="monthly_deductions[index]['emp_variables_type_id']"
                                                                        name="emp_variables_type[]">
                                                                    <option v-for="employee_variable in employees_variables"
                                                                            :value="employee_variable.emp_variables_type_id">
                                                                        @{{
                                                                        employee_variable.system_code_type.system_code_name_ar
                                                                        }}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" min="0" style="width:60px"
                                                                       name="emp_variables_days[]"
                                                                       class="form-control  no-arabic numbers-only"
                                                                       @keyup="getEmployeeVariableFactor(monthly_deductions[index]['emp_variables_type_id'],index)"
                                                                       v-model="monthly_deductions[index]['days']"
                                                                       :readonly="monthly_deductions[index]['days_valid']">
                                                                <input type="number" min="0" style="width:60px"
                                                                       name="emp_variables_hours[]"
                                                                       class="form-control  no-arabic numbers-only"
                                                                       @keyup="getEmployeeVariableFactor(monthly_deductions[index]['emp_variables_type_id'],index)"
                                                                       v-model="monthly_deductions[index]['hours']"
                                                                       :readonly="monthly_deductions[index]['hours_valid']">
                                                                <input type="number" min="0" style="width:60px"
                                                                       class="form-control  no-arabic numbers-only"
                                                                       @keyup="getEmployeeVariableFactor(monthly_deductions[index]['emp_variables_type_id'],index)"
                                                                       v-model="monthly_deductions[index]['minutes']"
                                                                       name="emp_variables_minutes[]"
                                                                       :readonly="monthly_deductions[index]['minutes_valid']">
                                                            </td>
                                                            <td>
                                                                <input type="number" style="width:90px"
                                                                       name="emp_variables_salary[]"
                                                                       :value="monthly_deductions[index]['salary']"
                                                                       class="form-control  no-arabic numbers-only"
                                                                       readonly>
                                                            </td>
                                                            <td>
                                                                <input type="number" min="0" style="width:90px"
                                                                       name="emp_variables_factor[]"
                                                                       :value="monthly_deductions[index]['factor']"
                                                                       class="form-control  no-arabic numbers-only"
                                                                       readonly>
                                                            </td>
                                                            <td>
                                                                <input type="number" min="0" style="width:90px"
                                                                       v-model="monthly_deductions[index]['value']"
                                                                       name="emp_variables_depit[]"
                                                                       class="form-control  no-arabic numbers-only"
                                                                       readonly v-if="value_valid">

                                                                <input type="number" min="0" style="width:90px"
                                                                       v-model="monthly_deductions[index]['value']"
                                                                       name="emp_variables_depit[]"
                                                                       @change="getEmployeeVariableFactor(monthly_deductions[index]['emp_variables_type_id'],index)"
                                                                       class="form-control  no-arabic numbers-only"
                                                                       v-else>
                                                            </td>
                                                            <td>
                                                                <textarea class="form-control"
                                                                          name="emp_variables_notes[]"></textarea>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-primary"
                                                                        @click="addRow()">
                                                                    <i class="fa fa-plus"></i></button>
                                                                <button type="button" class="btn btn-danger"
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
                                        <button class="btn btn-primary mt-2" type="submit"
                                                id="submit">
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
                employees: {},
                acc_period_id: '',
                accounts_period: {},
                company_id: '',
                employees_variables: {},
                monthly_deductions: [{
                    'emp_id': '', 'branch': {}, 'emp_variables_type_id': '',
                    'salary': 0, 'factor': 0, 'value': 0, 'days': 0, 'minutes': 0, 'hours': 0,
                    'days_valid': true, 'minutes_valid': true, 'hours_valid': true
                }],
                code: '',
                value_valid: true

            },
            methods: {
                validInputs(id, index) {
                    this.monthly_deductions[index]['factor'] = 0
                    this.monthly_deductions[index]['salary'] = 0
                    this.monthly_deductions[index]['value'] = 0
                    $.ajax({
                        type: 'GET',
                        data: {id: id},
                        url: '{{ route("api.employee-variable") }}'
                    }).then(response => {
                        this.code = response.data
                        // خصم ايام اجمالي
                        console.log(this.code)
                        if (this.code == "30"  || this.code == 2701 || this.code == 2055) {
                            this.monthly_deductions[index]['days_valid'] = false
                            this.monthly_deductions[index]['minutes_valid'] = true
                            this.monthly_deductions[index]['minutes'] = 0
                            this.monthly_deductions[index]['hours_valid'] = true
                            this.monthly_deductions[index]['hours'] = 0
                            this.value_valid = true
                        }
                        if (this.code == 3055) {
                            this.monthly_deductions[index]['days_valid'] = false
                            this.monthly_deductions[index]['minutes_valid'] = true
                            this.monthly_deductions[index]['minutes'] = 0
                            this.monthly_deductions[index]['hours_valid'] = true
                            this.monthly_deductions[index]['hours'] = 0
                            this.value_valid = true
                        }
                        //خصم ساعات اساسي
                        if (this.code == 31) {
                            this.monthly_deductions[index]['minutes_valid'] = false
                            this.monthly_deductions[index]['hours_valid'] = false
                            this.monthly_deductions[index]['days_valid'] = true
                            this.monthly_deductions[index]['days'] = 0
                            this.value_valid = true
                            
                        }
                        if (this.code == 3056  || this.code == 2056) {
                            this.monthly_deductions[index]['minutes_valid'] = false
                            this.monthly_deductions[index]['hours_valid'] = false
                            this.monthly_deductions[index]['days_valid'] = true
                            this.monthly_deductions[index]['days'] = 0
                            this.value_valid = true
                            
                        }

                        //خصم عدم مباشره
                        if (this.code == 32 || this.code == 2801 || this.code == 2057) {
                            this.monthly_deductions[index]['minutes_valid'] = true
                            this.monthly_deductions[index]['hours_valid'] = true
                            this.monthly_deductions[index]['days_valid'] = true
                            this.value_valid = false
                        }
                        //خصم  مباشره
                        if (this.code == 2445 || this.code == 8240 || this.code == 8241 || this.code == 8242 ) {
                            this.monthly_deductions[index]['minutes_valid'] = true
                            this.monthly_deductions[index]['hours_valid'] = true
                            this.monthly_deductions[index]['days_valid'] = true
                            this.value_valid = false
                        }
                        //خصم  مباشره
                        if (this.code == 2446) {
                            this.monthly_deductions[index]['minutes_valid'] = true
                            this.monthly_deductions[index]['hours_valid'] = true
                            this.monthly_deductions[index]['days_valid'] = true
                            this.value_valid = false
                        }
                        if (this.code == 3057) {
                            this.monthly_deductions[index]['minutes_valid'] = true
                            this.monthly_deductions[index]['hours_valid'] = true
                            this.monthly_deductions[index]['days_valid'] = true
                            this.value_valid = false
                        }
                        if (this.code == 8254) {
                            this.monthly_deductions[index]['minutes_valid'] = true
                            this.monthly_deductions[index]['hours_valid'] = true
                            this.monthly_deductions[index]['days_valid'] = true
                            this.value_valid = false
                        }

                    })
                },
                addRow() {
                    this.monthly_deductions.push({
                        'emp_id': '', 'branch': {}, 'emp_variables_type_id': '',
                        'salary': 0, 'factor': 0, 'value': 0, 'days': 0, 'minutes': 0, 'hours': 0,
                        'days_valid': true, 'minutes_valid': true, 'hours_valid': true
                    })
                },
                subRow(index) {
                    this.monthly_deductions.splice(index, 1)
                },
                getAccountPeriodsOfCompany() {
                    $.ajax({
                        type: 'GET',
                        data: {company_id: this.company_id},
                        url: '{{ route("api.deduction-company") }}'
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
                        url: '{{ route("api.deduction-employee.branch") }}'
                    }).then(response => {
                        this.monthly_deductions[index]['branch'] = response.branch
                        // this.monthly_deductions[index]['salary'] = response.salary
                    })
                },
                getEmployeeVariableFactor(emp_variables_type_id, index) {
                    if (this.monthly_deductions[index]['emp_id']) {
                        $.ajax({
                            type: 'GET',
                            data: {
                                emp_id: this.monthly_deductions[index]['emp_id'],
                                emp_variables_type_id: emp_variables_type_id,
                                salary: this.monthly_deductions[index]['salary'],
                                days: this.monthly_deductions[index]['days'],
                                minutes: this.monthly_deductions[index]['minutes'],
                                hours: this.monthly_deductions[index]['hours'],
                                value: this.monthly_deductions[index]['value'],
                            },
                            url: '{{ route("api.deduction-employee.variableFactor") }}'
                        }).then(response => {
                            this.monthly_deductions[index]['factor'] = response.data
                            this.monthly_deductions[index]['salary'] = response.salary
                            this.monthly_deductions[index]['value'] = response.value
                        })
                    }
                },
            },
        });
    </script>
@endsection


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
                            <form action="{{ route('monthly-additions.update',$monthly_additions->emp_variables_id) }}"
                                  method="post">
                                @csrf
                                @method('put')
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.companies')</label>

                                            <select class="form-control" name="company_id"
                                                    @change="getAccountPeriodsOfCompany()" required>

                                                @foreach($companies as $company)
                                                    <option value="{{ $company->company_id }}"
                                                            @if($monthly_additions->company_id == $company->company_id) selected @endif>
                                                        @if(app()->getLocale()=='ar')
                                                            {{ $company->company_name_ar }}
                                                        @else
                                                            {{ $company->company_name_en }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.account_periods')</label>
                                            <select class="form-control" required
                                                    name="acc_period_id">
                                                @foreach($account_periods as $account_periods)

                                                    <option value="{{ $account_periods->acc_period_id }}"
                                                            @if($monthly_additions->acc_period_id == $account_periods->acc_period_id) selected @endif>
                                                        @if(app()->getLocale()=='ar')
                                                            {{ $account_periods->acc_period_name_ar }}
                                                        @else
                                                            {{ $account_periods->acc_period_name_en }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.created_date')</label>
                                            <input id="date" type="text" class="form-control" readonly>
                                        </div>
                                    </div>


                                    <div class="col-md-4">
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
                                                        <tr id="add_clone" class="clone"
                                                            v-for="(monthly_addition,index) in old_addmonthd">
                                                            <input type="hidden" name="old_emp_variables_id_dt[]"
                                                                   :value="old_addmonthd[index]['emp_variables_id_dt']">


                                                            <td>@{{ index+1 }}</td>

                                                            <td>

                                                                <input type="hidden" name="old_emp_id_id[]"
                                                                       :value="old_addmonthd[index]['emp_id']">
                                                                <select class="form-control" name="old_emp_id[]"
                                                                        required
                                                                        @change="getEmployeeBranch(old_addmonthd[index]['emp_id'],index)"
                                                                        v-model="old_addmonthd[index]['emp_id']">
                                                                    @foreach($employees as $employee)
                                                                        <option value="{{$employee->emp_id }}">
                                                                            @if(app()->getLocale() == 'ar')
                                                                                {{ $employee->emp_name_full_ar }}
                                                                            @else
                                                                                {{ $employee->emp_name_full_en }}
                                                                            @endif

                                                                        </option>
                                                                    @endforeach

                                                                </select>
                                                            </td>


                                                            <td width='200px'>
                                                                <input type="text" class="form-control" required
                                                                       v-model="old_addmonthd[index]['branch'].branch_name_ar"
                                                                       style="width:170px" readonly>
                                                            </td>


                                                            <td>

                                                                <select class="form-control"
                                                                        name="old_emp_variables_type[]" required
                                                                        @change="validInputs(old_addmonthd[index]['emp_variables_type_id'],index)"
                                                                        v-model="old_addmonthd[index]['emp_variables_type_id']">
                                                                    @foreach($employees_variables as $employees_variable)
                                                                        <option value="{{$employees_variable->systemCodeType->system_code_id }}">
                                                                            @if(app()->getLocale() == 'ar')
                                                                                {{ $employees_variable->systemCodeType->system_code_name_ar}}
                                                                            @else
                                                                                {{ $employees_variable->systemCodeType->system_code_name_en }}
                                                                            @endif

                                                                        </option>
                                                                    @endforeach

                                                                </select>

                                                            </td>
                                                            <td>
                                                                <input type="number" min="0" style="width:60px"
                                                                       name="old_emp_variables_days[]" step=".01"
                                                                       class="form-control  no-arabic numbers-only"
                                                                       @keyup="getEmployeeVariableFactor(old_addmonthd[index]['emp_variables_type_id'],index)"
                                                                       v-model="old_addmonthd[index]['emp_variables_days']"
                                                                       :readonly="old_addmonthd[index]['days_valid']">

                                                                <input type="number" min="0" style="width:60px"
                                                                       step=".01"
                                                                       name="old_emp_variables_hours[]"
                                                                       class="form-control  no-arabic numbers-only"
                                                                       @keyup="getEmployeeVariableFactor(old_addmonthd[index]['emp_variables_type_id'],index)"
                                                                       v-model="old_addmonthd[index]['emp_variables_hours']"
                                                                       :readonly="old_addmonthd[index]['hours_valid']">

                                                                <input type="number" min="0" style="width:60px"
                                                                       step=".01"
                                                                       @keyup="getEmployeeVariableFactor(old_addmonthd[index]['emp_variables_type_id'],index)"
                                                                       v-model="old_addmonthd[index]['emp_variables_minutes']"
                                                                       name="old_emp_variables_minutes[]"
                                                                       class="form-control  no-arabic numbers-only"
                                                                       :readonly="old_addmonthd[index]['emp_variables_minutes_valid']">
                                                            </td>
                                                            <td>
                                                                <input type="number" style="width:90px" step=".01"
                                                                       name="old_emp_variables_salary[]"
                                                                       v-model="old_addmonthd[index]['emp_variables_salary']"
                                                                       class="form-control  no-arabic numbers-only"
                                                                       readonly>
                                                            </td>
                                                            <td>
                                                                <input type="number" min="0" style="width:90px"
                                                                       name="old_emp_variables_factor[]" step=".01"
                                                                       v-model="old_addmonthd[index]['emp_variables_factor']"
                                                                       class="form-control  no-arabic numbers-only"
                                                                       readonly>
                                                            </td>
                                                            <td>
                                                                <input type="number" min="0" style="width:90px"
                                                                       step=".01"
                                                                       v-model="old_addmonthd[index]['emp_variables_credit']"
                                                                       class="form-control  no-arabic numbers-only"
                                                                       {{--v-if="value_valid"--}}
                                                                       name="old_emp_variables_credit[]">


                                                            </td>
                                                            <td>
                                                                <textarea type="text" class="form-control"
                                                                          v-model="old_addmonthd[index]['emp_variables_notes']"
                                                                          name="old_emp_variables_notes[]"></textarea>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-primary"
                                                                        @click="addRow()">
                                                                    <i class="fa fa-plus"></i></button>
                                                                <button type="button" class="btn btn-primary"
                                                                        @click="subRow([index])" v-if="index>0">

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
                Month_add:{!! $id !!},
                old_addmonthd: [],
                employees: {},
                acc_period_id: '',
                accounts_period: {},
                company_id: '',
                employees_variables: {},
                // monthly_additions: [{
                //     'emp_id': '', 'branch': {}, 'emp_variables_type_id': '',
                //     'salary': '', 'factor': '', 'value': '', 'days': 0, 'minutes': 0, 'hours': 0,
                //     'days_valid': true, 'minutes_valid': true, 'hours_valid': true,
                // }],
                code: '',
                value_valid: true
            },
            mounted() {
                this.getPriceList()
            },
            methods: {
                validInputs(id, index) {
                    this.old_addmonthd[index]['emp_variables_factor'] = 0
                    this.old_addmonthd[index]['emp_variables_salary'] = 0
                    this.old_addmonthd[index]['emp_variables_credit'] = 0
                    $.ajax({
                        type: 'GET',
                        data: {id: id},
                        url: '{{ route("api.employee-variable2") }}'
                    }).then(response => {
                        console.log(response)
                        this.code = response.data
                        // اضافي ايام اجمالي و اضافي ايام اساسي
                        if (this.code == 27 || this.code == 28 || this.code == 3050 || this.code == 3051) {
                            this.old_addmonthd[index]['days_valid'] = false

                            this.old_addmonthd[index]['emp_variables_minutes_valid'] = true
                            this.old_addmonthd[index]['emp_variables_minutes'] = 0
                            this.old_addmonthd[index]['hours_valid'] = true
                            this.old_addmonthd[index]['emp_variables_hours'] = 0
                            // this.value_valid = true
                        }
                        //اضافي ساعات اساسي و اضافي ساعات اجمالي
                        if (this.code == 29 || this.code == 68 || this.code == 3052 || this.code == 3053) {
                            this.old_addmonthd[index]['emp_variables_minutes'] = 0
                            this.old_addmonthd[index]['emp_variables_minutes_valid'] = true
                            this.old_addmonthd[index]['hours_valid'] = false
                            this.old_addmonthd[index]['days_valid'] = true
                            this.old_addmonthd[index]['emp_variables_days'] = 0
                            // this.value_valid = true
                        }
                        //حوافز شهريه
                        if (this.code == 69 || this.code == 3054) {
                            this.old_addmonthd[index]['emp_variables_minutes_valid'] = true
                            this.old_addmonthd[index]['hours_valid'] = true
                            this.old_addmonthd[index]['days_valid'] = true
                            // this.value_valid = false
                        }

                    })
                },
                getPriceList() {
                    $.ajax({
                        type: 'GET',
                        data: {emp_variables_id: this.emp_variables_id},
                        url: ''
                    }).then(response => {
                        this.Month_add = response.data
                        this.old_addmonthd = response.monthly_additions_dts
                    })
                },
                addRow() {
                    this.old_addmonthd.push({
                        'emp_variables_id': '',
                        'emp_variables_id_dt': 0,
                        'emp_variables_type_id': '',
                        'emp_id': '',
                        // 'emp_variables_type': '',
                        'emp_variables_hours': 0,
                        'hours_valid': true,
                        'emp_variables_minutes': 0,
                        'emp_variables_minutes_valid': true,
                        'emp_variables_days': 0,
                        'days_valid': true,
                        'emp_variables_factor': 0,
                        'emp_variables_debit': 0,
                        'emp_variables_credit': 0,
                        'emp_variables_notes': '',
                        'emp_variables_main_type': '',
                        'acc_period_id': '',
                        'branch': {}
                    })

                },
                subRow(index) {

                    if (this.old_addmonthd[index]['emp_variables_id_dt'] != 0) {
                        this.deleteEmployeeVariableDetails(index)
                    } else {
                        this.old_addmonthd.splice(index, 1)
                    }

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
                        this.old_addmonthd[index]['branch'] = response.branch
                        // this.monthly_additions[index]['salary'] = response.salary
                    })
                },
                getEmployeeVariableFactor(emp_variables_type_id, index) {
                    if (this.old_addmonthd[index]['emp_id']) {
                        $.ajax({
                            type: 'GET',
                            data: {
                                emp_id: this.old_addmonthd[index]['emp_id'],
                                emp_variables_type_code: emp_variables_type_id,
                                salary: this.old_addmonthd[index]['emp_variables_salary'],
                                days: this.old_addmonthd[index]['emp_variables_days'],
                                minutes: this.old_addmonthd[index]['emp_variables_minutes'],
                                hours: this.old_addmonthd[index]['emp_variables_hours'],
                                value: this.old_addmonthd[index]['emp_variables_credit']
                            },
                            url: '{{ route("api.employee.variableFactor") }}'
                        }).then(response => {
                            this.old_addmonthd[index]['emp_variables_factor'] = response.data
                            this.old_addmonthd[index]['emp_variables_salary'] = response.salary
                            this.old_addmonthd[index]['emp_variables_credit'] = response.value
                        })
                    }
                },
                //
                deleteEmployeeVariableDetails(index) {
                    $.ajax({
                        type: 'DELETE',
                        data: {id: this.old_addmonthd[index]['emp_variables_id_dt']},
                        url: '{{ route("api.addition-detail.delete") }}'
                    }).then(response => {
                        console.log(response)
                        this.old_addmonthd.splice(index, 1)
                        // this.monthly_additions[index]['salary'] = response.salary
                    })
                }
            },
        });
    </script>
@endsection

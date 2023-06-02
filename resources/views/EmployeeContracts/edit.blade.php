@extends('Layouts.master')
@section('content')


    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <form action="{{route('employees-contracts-update' , $contract->emp_contract_id)}}" method="post">
                @csrf
                @method('put')

                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">@lang('home.edit_contract')</h3>
                            </div>
                            <div class="card-body demo-card">
                                <div class="row clearfix">
                                    {{--readonly--}}

                                    <div class="col-lg-4 col-md-12">
                                        <label>@lang('home.emp_name')</label>
                                        <div class="form-group">
                                            <input class="form-control"
                                                   value="@if(app()->getLocale() == 'ar')
                                                   {{$contract->employee->emp_name_full_ar}}
                                                   @else
                                                   {{$contract->employee->emp_name_full_ar}}
                                                   @endif " readonly>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-12">
                                        <label>@lang('home.sub_company')</label>
                                        <div class="form-group">
                                            <input type="text" readonly class="form-control"
                                                   value="@if(app()->getLocale()=='ar')
                                                   {{ $contract->company->company_name_ar }}
                                                   @else
                                                   {{ $contract->company->company_name_en }}
                                                   @endif">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-12">
                                        <label>@lang('home.branch')</label>
                                        <div class="form-group">
                                            <input type="text" readonly class="form-control"
                                                   value="@if(app()->getLocale()=='ar')
                                                   {{ $contract->branch->branch_name_ar }} @else
                                                   {{ $contract->branch->branch_name_en }} @endif">
                                        </div>
                                    </div>

                                    {{--readonly--}}

                                    <div class="col-lg-4 col-md-12">
                                        <label>@lang('home.contract_start_date')</label>
                                        <div class="form-group ">
                                            <input type="date" class="form-control"
                                                   name="emp_contract_start_date" id="emp_contract_start_date"
                                                   value="{{$contract->emp_contract_start_date}}">
                                        </div>
                                    </div>


                                    <div class="col-lg-4 col-md-12">
                                        <label>@lang('home.contract_end_date')</label>
                                        <div class="form-group ">
                                            <input type="date" class="form-control"
                                                   name="emp_contract_end_date"
                                                   id="emp_contract_end_date"
                                                   value="{{$contract->emp_contract_end_date}}">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-12">
                                        <label>@lang('home.contract_work_hours')</label>
                                        <div class="form-group multiselect_div">
                                            <input type="number" class="form-control"
                                                   name="emp_contract_work_hours" id="emp_contract_work_hours"
                                                   value="{{$contract->emp_contract_work_hours}}">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-12">
                                        <label> @lang('home.job') </label>
                                        <div class="form-group multiselect_div">
                                            <select class="form-select form-control"
                                                    name="emp_contract_job_id" id="emp_contract_job_id"
                                                    placeholder="@lang('home.job')" required>

                                                <option value="">choose</option>
                                                @foreach($contract->company->jobs as $job)

                                                    <option value="{{$job->job_id}}"
                                                            @if($job->job_id == $contract->emp_contract_job_id ) selected @endif>
                                                        @if(app()->getLocale() == 'ar')
                                                            {{  $job->job_name_ar }}
                                                        @else
                                                            {{  $job->job_name_en }}
                                                        @endif
                                                    </option>

                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-12">
                                        <label> @lang('home.manager') </label>
                                        <div class="form-group">
                                            <select class="form-select form-control"
                                                    name="emp_contract_manager_id" id="emp_contract_manager_id"
                                                    placeholder="@lang('home.manager')" required>

                                                <option value="">choose</option>
                                                @foreach($employees as $employee)

                                                    <option value="{{$employee->emp_id}}"
                                                            @if($employee->emp_id == $contract->emp_contract_manager_id ) selected @endif>
                                                        @if(app()->getLocale() == 'ar')
                                                            {{  $employee->emp_name_full_ar }}
                                                        @else
                                                            {{  $employee->emp_name_full_en }}
                                                        @endif
                                                    </option>

                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-12">
                                        <label>@lang('home.contract_notes')</label>
                                        <div class="form-group multiselect_div">
                                        <textarea class="form-control "
                                                  name="emp_contract_notes" id="emp_contract_notes">
                                            {{$contract->emp_contract_notes}}
                                        </textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-12">
                                        <label>@lang('home.emp_contract_is_active')</label>
                                        <div class="form-group multiselect_div">

                                            <input type="checkbox" class="text-center"
                                                   @if($contract->emp_contract_is_active) checked @endif
                                                   name="emp_contract_is_active" id="emp_contract_is_active">
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--salary--}}

                <div class="card">
                    <div class="card-body" id="cont-contract{{ $contract->emp_contract_id }}">
                        {{-- contract salary details --}}
                        <div class="row clearfix">
                            <div class="table-responsive">

                                <table class="table table-vcenter text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>@lang('home.salary_details')</th>
                                        <th>@lang('home.credit')</th>
                                        <th>@lang('home.debit')</th>
                                        <th>@lang('home.from')</th>
                                        <th>@lang('home.to')</th>
                                        <th>@lang('home.created_user')</th>
                                        <th></th>
                                    </tr>
                                    </thead>

                                    <tbody>


                                    <tr v-for="(salary,index) in salaries">

                                        <td>@{{ index+1 }}</td>
                                        <input type="hidden" name="emp_id_salary_old[]" :value="salary.emp_id_salary">
                                        <td>
                                        @foreach($employees as $employee)
                                            @php $salary_details=\App\Models\SystemCode::where('sys_category_id',25)->where('company_group_id', $employee->company_group_id)->get();
                                            @endphp
                                            @endforeach   
                                            <select class="form-control"
                                                    name="emp_salary_item_id_old[]" v-model="salary.emp_salary_item_id">
                                                @foreach($salary_details as $salary_detail)
                                                    <option value="{{$salary_detail->system_code}}">
                                                        {{ $salary_detail->system_code_name_ar }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <input type="number" class="form-control" min="0"
                                                   :value="salary.emp_salary_credit"  step="0.01" value="0.00"
                                                   name="emp_salary_credit_old[]"></td>

                                        <td><input type="number" class="form-control" min="0"
                                                   :value="salary.emp_salary_debit"  step="0.01" value="0.00"
                                                   name="emp_salary_debit_old[]"></td>

                                        <td><input type="date" class="form-control"
                                                   value="{{ $contract->emp_contract_start_date }}"
                                                   readonly></td>

                                        <td><input type="date" class="form-control"
                                                   value="{{ $contract->emp_contract_end_date }}"
                                                   readonly></td>
                                        <td>
                                            <input class="form-control" type="text" :value="salary.user.user_name_ar"
                                                   readonly>
                                        </td>
                                        <td>

                                            <button type="button" @click="deleteSalaryDetail(salary)"
                                                    class="btn btn-danger">
                                                <i class="fa fa-trash"></i></button>

                                        </td>
                                    </tr>


                                    <tr v-for="(element,index) in emp_contract_salary">

                                        <td>@{{ index+1 }}</td>
                                        <td>
                                        @foreach($employees as $employee)
                                            @php $salary_details=\App\Models\SystemCode::where('sys_category_id',25)->where('company_group_id', $employee->company_group_id)->get();
                                            @endphp
                                            @endforeach  
                                            <select class="form-control"
                                                    v-model="emp_contract_salary[index]['emp_salary_item_id']"
                                                    name="emp_salary_item_id[]">
                                                @foreach($salary_details as $salary_detail)
                                                    <option value="{{$salary_detail->system_code}}">
                                                        {{ $salary_detail->system_code_name_ar }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" class="form-control" min="0"  step="0.01" value="0.00"
                                                   v-model="emp_contract_salary[index]['emp_salary_credit']"
                                                   name="emp_salary_credit[]"></td>
                                        <td><input type="number" class="form-control" min="0"  step="0.01" value="0.00"
                                                   v-model="emp_contract_salary[index]['emp_salary_debit']"
                                                   name="emp_salary_debit[]"></td>
                                        <td><input type="date" class="form-control"
                                                   value="{{ $contract->emp_contract_start_date }}"
                                                   readonly></td>
                                        <td><input type="date" class="form-control"
                                                   value="{{ $contract->emp_contract_end_date }}"
                                                   readonly></td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm mr-1 ml-1"
                                                    @click="addRow(this)">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-success btn-sm mr-1 ml-1"
                                                    @click="supRow(index)" v-if="index>0">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>


                                    {{-- credit depit total --}}
                                    <tr>
                                        <td colspan="2"><span
                                                    style="font-size:20px;font-weight: bold">@lang('home.credit')
                                                : @{{ totalCredit }}</span>

                                                        </td>
                                        <td colspan="2"><span
                                                    style="font-size:20px;font-weight: bold">@lang('home.debit')
                                                : @{{ totalDepit }}</span></td>

                                        <td colspan="2"><span
                                                    style="font-size:20px;font-weight: bold">@lang('home.salary_total')
                                                : @{{ total }}</span></td>
                                    </tr>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="card">
                    <div class="card-footer">
                        <button class="btn btn-primary " type="submit">@lang('home.save')</button>
                    </div>
                </div>
            </form>

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
    </script>

    <script>
        $(document).ready(function () {

            $('#emp_contract_start_date').change(function () {
                $('#emp_contract_start_date').removeClass('is-invalid')
                $('#edit_contract').removeAttr('disabled', 'disabled')
            });

            $('#emp_contract_end_date').change(function () {
                $('#emp_contract_end_date').removeClass('is-invalid')
                $('#edit_contract').removeAttr('disabled', 'disabled')
            });

            $('#emp_contract_work_hours').keyup(function () {
                if ($('#emp_contract_work_hours').val().length < 1) {
                    $('#emp_contract_work_hours').addClass('is-invalid')
                    $('#edit_contract').attr('disabled', 'disabled')
                } else {
                    $('#emp_contract_work_hours').removeClass('is-invalid')
                    $('#edit_contract').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_contract_notes').keyup(function () {
                if ($('#emp_contract_notes').val().length < 20) {
                    $('#emp_contract_notes').addClass('is-invalid')
                    $('#edit_contract').attr('disabled', 'disabled')
                } else {
                    $('#emp_contract_notes').removeClass('is-invalid')
                    $('#edit_contract').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_contract_manager_id').change(function () {
                if (!$('#emp_contract_manager_id').val()) {
                    $('#emp_contract_manager_id').addClass('is-invalid')
                    $('#edit_contract').attr('disabled', 'disabled')
                } else {
                    $('#emp_contract_manager_id').removeClass('is-invalid')
                    $('#edit_contract').removeAttr('disabled', 'disabled')
                }
            });

        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                salaries: {},
                contract_id:{!! $contract->emp_contract_id !!},
                emp_contract_salary: [{
                    'emp_salary_item_id': '',
                    'emp_salary_credit': 0.00,
                    'emp_salary_debit': 0.00
                }],
                contract_depit_salary: {!! $contract->depitSalary !!},
                contract_credit_salary: {!! $contract->creditSalary !!},
                contract_total_salary: {!! $contract->totalSalary !!}
            },
            mounted() {
                this.getContractSalaries()
            },
            methods: {
                getContractSalaries() {
                    $.ajax({
                        type: 'GET',
                        data: {contract_id: this.contract_id},
                        url: '{{ route("api.contract.salaries") }}'
                    }).then(response => {
                        this.salaries = response.data
                    })
                },
                deleteSalaryDetail(salary) {
                    $.ajax({
                        type: 'DELETE',
                        data: {id: salary.emp_id_salary},
                        url: '{{ route("api.contract.salary-delete") }}'
                    }).then(response => {
                        this.salaries.splice(this.salaries.indexOf(salary), 1)
                    })
                },

                addRow() {
                    this.emp_contract_salary.push({
                        'emp_salary_item_id': '',
                        'emp_salary_credit': 0.00,
                        'emp_salary_debit': 0.00
                    })

                },
                supRow(index) {
                    this.emp_contract_salary.splice(index, 1)

                }


            },
            computed: {
                totalCredit: function () {
                    var sum_credit = this.contract_credit_salary;

                        Object.entries(this.emp_contract_salary).forEach(([key, val]) => {
                        sum_credit += (parseFloat(val.emp_salary_credit))
                    });
                   
                    return sum_credit.toFixed(2);
                },
                totalDepit: function () {
                    var sum_depit = this.contract_depit_salary;

                        Object.entries(this.emp_contract_salary).forEach(([key, val]) => {
                            sum_depit += (parseFloat(val.emp_salary_debit))
                    });
                    return sum_depit.toFixed(2);
                },
                total: function () {
                    var  totalall =  this.totalCredit - this.totalDepit
                    return totalall.toFixed(2);
                },
            }
        })
    </script>
@endsection

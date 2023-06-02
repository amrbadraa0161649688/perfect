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

            <div class="row clearfix">
                <div class="col-md-12">

                    <div class="card">

                        <div class="card-body demo-card">
                            <div class="row clearfix">
                                <div class="col-lg-4 col-md-12">
                                    <label>@lang('home.choose_request_type')</label>
                                    <div class="form-group multiselect_div">
                                        <select name="emp_request_type" id="request_type" class="form-control"
                                                onchange="getForm()">
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($request_types as $request_type )
                                                <option value="{{$request_type->system_code}}">
                                                    {{app()->getLocale() == 'ar' ? $request_type->system_code_name_ar
                                                     : $request_type->system_code_name_en}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="alert alert-danger" v-if="error_message">
                        @{{error_message}}

                    </div>

                    {{--طلب الاجازه--}}
                    <x-employees.vacation-request
                            :vacationTypes="$vacation_types"
                            :alterEmployees="$alter_employees"
                            :employees="$employees"
                            :stringNumber="$string_number">

                    </x-employees.vacation-request>

                    {{--طلب مباشرة العمل--}}
                    <x-employees.direct-request
                            :employees="$employees"
                            :stringNumber="$string_number">
                    </x-employees.direct-request>


                    {{--طلب التامين الطبي--}}
                    <x-employees.medical-request
                            :employees="$employees"
                            :insuranceCategories="$insurance_categories"
                            :insuranceTypes="$insurance_types"
                            :stringNumber="$string_number">
                        <x-employees.employee>

                        </x-employees.employee>
                    </x-employees.medical-request>


                    {{--تسليم العهده--}}
                    <x-employees.hand-over
                            :employees="$employees"
                            :handOverItems="$hand_over_items"
                            :stringNumber="$string_number"
                            :handOverStatuses="$hand_over_statuses">
                        <x-employees.employee>

                        </x-employees.employee>
                    </x-employees.hand-over>


                    {{--اجراء جزائي--}}
                    <x-employees.panel-action
                            :employees="$employees"
                            :panelActionReasons="$panel_action_reasons"
                            :stringNumber="$string_number">

                    </x-employees.panel-action>

                    {{-- طلب سلفه--}}
                    <x-employees.ancestors-request
                            :employees="$employees"
                            :accountPeriods="$account_periods"
                            :stringNumber="$string_number">
                        <x-employees.employee>

                        </x-employees.employee>
                    </x-employees.ancestors-request>


                    {{-- طلب توقف عن العمل--}}
                    <x-employees.stop-working-request
                            :employees="$employees"
                            :accountPeriods="$account_periods"
                            :stopWorkingReasons="$stop_working_reasons"
                            :stringNumber="$string_number">
                        <x-employees.employee>

                        </x-employees.employee>
                    </x-employees.stop-working-request>


                    {{--تكليف بمهمه عمل--}}
                    <x-employees.job-assignement
                            :employees="$employees"
                            :companies="$companies"
                            :stringNumber="$string_number">
                        <x-employees.employee>
                        </x-employees.employee>
                    </x-employees.job-assignement>

                    <x-employees.job-leave
                            :employees="$employees"
                            :stopWorkingReasons="$stop_working_reasons"
                            :systemCodeItems="$system_code_items"
                            :stringNumber="$string_number">
                        <x-employees.employee>
                        </x-employees.employee>

                    </x-employees.job-leave>


                    {{--طلب تقييم موظف--}}
                    <x-employees.employee-evaluation
                            :employees="$employees"
                            :employeeEvaluationTypes="$employee_evaluation_types"
                            :company="$company"
                            :perEmployees="$per_employees"
                            :interviewEvaluations="$interview_evaluations"
                            :employeeEvaluations="$employee_evaluations"
                            :stringNumber="$string_number">

                        <x-employees.employee>

                        </x-employees.employee>

                    </x-employees.employee-evaluation>

                    {{--طلب الاستقاله--}}
                    <x-employees.resignation-job
                            :employees="$employees"
                            :stopWorkingReasons="$stop_working_reasons"
                            :stringNumber="$string_number">
                        <x-employees.employee>

                        </x-employees.employee>
                    </x-employees.resignation-job>


                    {{--طلب تصفيه حساب--}}
                    <x-employees.reckoning
                            :employees="$employees"
                            :stringNumber="$string_number">
                        <x-employees.employee>
                        </x-employees.employee>

                    </x-employees.reckoning>

                </div>
            </div>

        </div>
    </div>


@endsection

@section('scripts')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script>


        function checkEvaluation(id) {
            $('.sev_check' + id).click(function () {
                $('.sev_check' + id).not(this).prop('checked', false);
            });
        }

        function checkEmployeeEvaluation(id) {
            $('.sev_check2' + id).click(function () {
                $('.sev_check2' + id).not(this).prop('checked', false);
            });
        }

        var d = new Date();

        var month = d.getMonth() + 1;
        var day = d.getDate();

        var output = (day < 10 ? '0' : '') + day + '/' +
            (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
        ;
        $('#vacation_request_date').val(output)
        $('#medical_request_date').val(output)
        $('#hand_over_request_date').val(output)

        //
        // function submitForm(id) {
        //
        //     if ($('.subject-list' + id).filter(':checked').length < 1) {
        //         alert("Please Check at least one Box");
        //         return false;
        //     }
        // }


        function chooseItem(id) {
            $('.subject-list' + id).on('change', function () {
                $('.subject-list' + id).not(this).prop('checked', false);
            });

        }

        $(document).ready(function () {

            $('form').submit(function () {

                $('#submit').css('display', 'none')
                $('.spinner-border').css('display', 'block')

                $('#submit2').css('display', 'none')
                $('.spinner-border').css('display', 'block')

            })


        })


        function getForm() {

            $('#direct_request')[0].reset();
            $('#vacation_request')[0].reset();
            $('#medical_request')[0].reset();
            $('#hand_over_request')[0].reset();
            $('#panel_action_request')[0].reset();
            $('#ancestors_request')[0].reset();
            $('#stop_working_request')[0].reset();
            $('#job_assignment_request')[0].reset();
            $('#job_leave_request')[0].reset();
            $('#employee_evaluation_request')[0].reset();
            $('#resignation_request')[0].reset();
            $('#reckoning_request')[0].reset();

            $('.emp_request_type_id').val($('#request_type').val())

            if ($('#request_type').val() == 503) {

                $('#direct-work-form').css('display', 'none')
                $('#vacation-form').css('display', 'block')
                $('#medical-insurance-form').css('display', 'none')
                $('#hand-over-form').css('display', 'none')
                $('#ancestors-form').css('display', 'none')
                $('#stop-working-form').css('display', 'none')
                $('#job-assignment-form').css('display', 'none')
                $('#job-leave-form').css('display', 'none')
                $('#employee-evaluation-form').css('display', 'none')
                $('#resignation-form').css('display', 'none')
                $('#reckoning-form').css('display', 'none')
            }

            if ($('#request_type').val() == 504) {
                $('#vacation-form').css('display', 'none')
                $('#direct-work-form').css('display', 'block')
                $('#hand-over-form').css('display', 'none')
                $('#medical-insurance-form').css('display', 'none')
                $('#panel-action-form').css('display', 'none')
                $('#ancestors-form').css('display', 'none')
                $('#stop-working-form').css('display', 'none')
                $('#job-assignment-form').css('display', 'none')
                $('#job-leave-form').css('display', 'none')
                $('#employee-evaluation-form').css('display', 'none')
                $('#resignation-form').css('display', 'none')
                $('#reckoning-form').css('display', 'none')
            }


            if ($('#request_type').val() == 46004) {
                $('#medical-insurance-form').css('display', 'block')
                $('#vacation-form').css('display', 'none')
                $('#direct-work-form').css('display', 'none')
                $('#hand-over-form').css('display', 'none')
                $('#panel-action-form').css('display', 'none')
                $('#ancestors-form').css('display', 'none')
                $('#stop-working-form').css('display', 'none')
                $('#job-assignment-form').css('display', 'none')
                $('#job-leave-form').css('display', 'none')
                $('#employee-evaluation-form').css('display', 'none')
                $('#resignation-form').css('display', 'none')
                $('#reckoning-form').css('display', 'none')

            }

            if ($('#request_type').val() == 46005) {
                $('#medical-insurance-form').css('display', 'none')
                $('#vacation-form').css('display', 'none')
                $('#direct-work-form').css('display', 'none')
                $('#hand-over-form').css('display', 'block')
                $('#panel-action-form').css('display', 'none')
                $('#ancestors-form').css('display', 'none')
                $('#stop-working-form').css('display', 'none')
                $('#job-assignment-form').css('display', 'none')
                $('#job-leave-form').css('display', 'none')
                $('#employee-evaluation-form').css('display', 'none')
                $('#resignation-form').css('display', 'none')
                $('#reckoning-form').css('display', 'none')
            }


            if ($('#request_type').val() == 46006) {
                $('#medical-insurance-form').css('display', 'none')
                $('#vacation-form').css('display', 'none')
                $('#direct-work-form').css('display', 'none')
                $('#hand-over-form').css('display', 'none')
                $('#ancestors-form').css('display', 'block')
                $('#stop-working-form').css('display', 'none')
                $('#job-assignment-form').css('display', 'none')
                $('#job-leave-form').css('display', 'none')
                $('#employee-evaluation-form').css('display', 'none')
                $('#resignation-form').css('display', 'none')
                $('#reckoning-form').css('display', 'none')

            }

            if ($('#request_type').val() == 46009) {
                $('#medical-insurance-form').css('display', 'none')
                $('#vacation-form').css('display', 'none')
                $('#direct-work-form').css('display', 'none')
                $('#hand-over-form').css('display', 'none')
                $('#panel-action-form').css('display', 'block')
                $('#stop-working-form').css('display', 'none')
                $('#job-assignment-form').css('display', 'none')
                $('#job-leave-form').css('display', 'none')
                $('#employee-evaluation-form').css('display', 'none')
                $('#resignation-form').css('display', 'none')
                $('#reckoning-form').css('display', 'none')

            }

            if ($('#request_type').val() == 46007) {
                $('#medical-insurance-form').css('display', 'none')
                $('#vacation-form').css('display', 'none')
                $('#direct-work-form').css('display', 'none')
                $('#hand-over-form').css('display', 'none')
                $('#panel-action-form').css('display', 'none')
                $('#stop-working-form').css('display', 'none')
                $('#job-assignment-form').css('display', 'block')
                $('#job-leave-form').css('display', 'none')
                $('#employee-evaluation-form').css('display', 'none')
                $('#resignation-form').css('display', 'none')
                $('#reckoning-form').css('display', 'none')

            }

            if ($('#request_type').val() == 46010) {
                $('#medical-insurance-form').css('display', 'none')
                $('#vacation-form').css('display', 'none')
                $('#direct-work-form').css('display', 'none')
                $('#hand-over-form').css('display', 'none')
                $('#panel-action-form').css('display', 'none')
                $('#stop-working-form').css('display', 'block')
                $('#job-assignment-form').css('display', 'none')
                $('#job-leave-form').css('display', 'none')
                $('#employee-evaluation-form').css('display', 'none')
                $('#resignation-form').css('display', 'none')
                $('#reckoning-form').css('display', 'none')

            }

            if ($('#request_type').val() == 46008) {
                $('#medical-insurance-form').css('display', 'none')
                $('#vacation-form').css('display', 'none')
                $('#direct-work-form').css('display', 'none')
                $('#hand-over-form').css('display', 'none')
                $('#panel-action-form').css('display', 'none')
                $('#stop-working-form').css('display', 'none')
                $('#job-assignment-form').css('display', 'none')
                $('#job-leave-form').css('display', 'block')
                $('#employee-evaluation-form').css('display', 'none')
                $('#resignation-form').css('display', 'none')
                $('#reckoning-form').css('display', 'none')

            }
            if ($('#request_type').val() == 46003) {//////////تقييم موظف
                $('#medical-insurance-form').css('display', 'none')
                $('#vacation-form').css('display', 'none')
                $('#direct-work-form').css('display', 'none')
                $('#hand-over-form').css('display', 'none')
                $('#panel-action-form').css('display', 'none')
                $('#stop-working-form').css('display', 'none')
                $('#job-assignment-form').css('display', 'none')
                $('#job-leave-form').css('display', 'none')
                $('#employee-evaluation-form').css('display', 'block')
                $('#resignation-form').css('display', 'none')
                $('#reckoning-form').css('display', 'none')
            }


            if ($('#request_type').val() == 46011) { ///طلب استقالة
                $('#medical-insurance-form').css('display', 'none')
                $('#vacation-form').css('display', 'none')
                $('#direct-work-form').css('display', 'none')
                $('#hand-over-form').css('display', 'none')
                $('#panel-action-form').css('display', 'none')
                $('#stop-working-form').css('display', 'none')
                $('#job-assignment-form').css('display', 'none')
                $('#job-leave-form').css('display', 'none')
                $('#employee-evaluation-form').css('display', 'none')
                $('#resignation-form').css('display', 'block')
                $('#reckoning-form').css('display', 'none')
            }

            if ($('#request_type').val() == 46012) { ///طلب تصفيه حساب
                $('#medical-insurance-form').css('display', 'none')
                $('#vacation-form').css('display', 'none')
                $('#direct-work-form').css('display', 'none')
                $('#hand-over-form').css('display', 'none')
                $('#panel-action-form').css('display', 'none')
                $('#stop-working-form').css('display', 'none')
                $('#job-assignment-form').css('display', 'none')
                $('#job-leave-form').css('display', 'none')
                $('#employee-evaluation-form').css('display', 'none')
                $('#resignation-form').css('display', 'none')
                $('#reckoning-form').css('display', 'block')
            }

        }


    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>

    <script>
        new Vue({
            el: '#app',
            data: {
                start_date: '',
                end_date: '',
                days_count: '',
                emp_id: '',
                days_available: '',
                branch: {},
                job: {},
                division: {},
                employee: {},
                pending_vacations: {},
                manager: {},
                vacation_id: '',
                vacation: {},
                direct_date: '',
                error_message: '',
                days_count2: '',
                vacation_message: '',
                social_status: {},
                nationality: {},

                ////التامين الطبي
                items_insurance: [{
                    'item_name_ar': '',
                    'item_relation': '',
                    'item_date': '',
                    'item_required': false
                }],
                hand_over: [{
                    'item_id': '',
                    'item_qunt': '',
                    'item_value': '',
                    'item_status': '',
                    'item_notes': '',
                }],
                panel_action_days_required: false,
                panel_action_date_required: false,
                item_reasons: '',
                emp_id_s1: '',
                employee_s1: {},
                job_s1: {},
                social_status_s1: {},

                emp_id_s2: '',
                employee_s2: {},
                job_s2: {},
                social_status_s2: {},

                stopWorking_item_qunt: '',
                stopWorking_start_date: '',
                stopWorking_end_date: '',

                jobAssignment_start_date: '',
                jobAssignment_end_date: '',
                jobAssignment_item_qunt: '',
                jobAssignment_company_id: '',
                branches: {},

                stopWorking_item_qunt: '',
                stopWorking_start_date: '',
                stopWorking_end_date: '',

                evaluate_show1: false,
                evaluate_show2: false,
                evaluate_show3: false,
                evaluation_type: '',

                per_employee: '',
                per_emp_id: '',
                total_evaluation: 0,

                last_vacation: {},
                error_message: '',
                days: '',
                months: '',
                years: '',
                items: {} ////العهد المسلمه للموظف
            },
            methods: {
                //تصفيه حساب
                getVacationEmployee() {
                    this.error_message = ''
                    this.last_vacation = {}
                    this.days = ''
                    this.months = ''
                    this.years = ''
                    this.items = {}

                    $.ajax({
                        type: 'GET',
                        data: {emp_id: this.emp_id},
                        url: '{{ route('get-employee-vacation-data') }}'
                    }).then(response => {
                        this.days = response.days
                        this.months = response.months
                        this.years = response.years

                        this.items = response.items


                        if (response.message) {
                            this.error_message = response.message
                        }
                        if (response.last_vacation) {
                            this.last_vacation = response.last_vacation
                        }

                    })
                },

                calculateTotal(num, event) {
                    var total = 0;
                    if (event.target.checked) {
                        console.log(10)
                        total += parseInt(num);
                    } else {
                        total -= parseInt(num);
                    }

                    this.total_evaluation = total;
                    // return total;

                },
                ///////////طلب تقييم موظف
                getEvaluationForm() {
                    //مقابله شخصيه
                    if (this.evaluation_type == 119001) {
                        this.evaluate_show1 = true
                        this.evaluate_show2 = false
                        this.evaluate_show3 = false
                    }
                    //تقييم تحت التجربه
                    if (this.evaluation_type == 119002) {
                        this.evaluate_show1 = false
                        this.evaluate_show2 = true
                        this.evaluate_show3 = false
                    }

                    //تقييم موظف
                    if (this.evaluation_type == 119003) {
                        this.evaluate_show1 = false
                        this.evaluate_show2 = false
                        this.evaluate_show3 = true
                    }
                },
                getPerEmployee() {

                    this.per_employee = ''

                    if (this.per_emp_id) {
                        $.ajax({
                            type: 'GET',
                            data: {emp_id: this.per_emp_id},
                            url: '{{ route('get-employee') }}'
                        }).then(response => {
                            this.per_employee = response.employee

                        })
                    }
                },

                //تكليف بمهمه عمل
                getDiffDateJobAssignment() {
                    if (this.jobAssignment_start_date && this.jobAssignment_end_date) {
                        $.ajax({
                            type: 'GET',
                            data: {start_date: this.jobAssignment_start_date, end_date: this.jobAssignment_end_date},
                            url: '{{ route('requests.diffDate') }}'
                        }).then(response => {
                            this.jobAssignment_item_qunt = response.days
                        })
                    }
                },
                getBranches() {
                    $.ajax({
                        type: 'GET',
                        data: {company_id: this.jobAssignment_company_id},
                        url: '{{ route('job-assignment.getBranches') }}'
                    }).then(response => {
                        this.branches = response.data
                    })
                },
                ////////////////////////

                ///////////////طلب ايقاف عن العمل
                getDiffDate() {
                    if (this.stopWorking_start_date && this.stopWorking_end_date) {
                        $.ajax({
                            type: 'GET',
                            data: {start_date: this.stopWorking_start_date, end_date: this.stopWorking_end_date},
                            url: '{{ route('requests.diffDate') }}'
                        }).then(response => {
                            this.stopWorking_item_qunt = response.days
                        })
                    }

                },

                /////////اجراء جزائي
                validatePanelActionForm() {
                    this.panel_action_days_required = false
                    this.panel_action_date_required = false
                    if (this.item_reasons == 108003) {
                        this.panel_action_days_required = true
                    }
                    if (this.item_reasons == 108007) {
                        this.panel_action_date_required = true
                    }

                },
                ////////////////////

                /////////////////التامين الطبي
                addInsuranceRow() {
                    this.items_insurance.push({
                        'item_name_ar': '',
                        'item_relation': '',
                        'item_date': '',
                        'item_required': false
                    })
                },
                removeRow(index) {
                    this.items_insurance.splice(index, 1)
                },
                ///////////////////////

                /////////تسليم العهده
                addHandOverRow() {
                    this.hand_over.push({
                        'item_id': '',
                        'item_qunt': '',
                        'item_value': '',
                        'item_status': '',
                        'item_notes': '',
                    })
                },
                removeHandOverRow(index) {
                    this.hand_over.splice(index, 1)
                },
                ////////////////////


                /////////////السلفه
                getEmployee_s1() {
                    this.employee_s1 = {}
                    this.job_s1 = {}
                    this.social_status_s1 = {}

                    if (this.emp_id_s1) {
                        $.ajax({
                            type: 'GET',
                            data: {emp_id: this.emp_id_s1},
                            url: '{{ route('get-employee') }}'
                        }).then(response => {
                            this.employee_s1 = response.employee
                            this.job_s1 = this.employee_s1.job
                            this.social_status_s1 = this.employee_s1.emp_social_status
                        })
                    }
                },

                getEmployee_s2() {

                    this.employee_s2 = {}
                    this.job_s2 = {}
                    this.social_status_s2 = {}

                    if (this.emp_id_s2) {
                        $.ajax({
                            type: 'GET',
                            data: {emp_id: this.emp_id_s2},
                            url: '{{ route('get-employee') }}'
                        }).then(response => {
                            this.employee_s2 = response.employee
                            this.job_s2 = this.employee_s2.job
                            this.social_status_s2 = this.employee_s2.emp_social_status
                        })
                    }
                },
                //////////////////

                getDaysCount() {
                    if (this.start_date && this.end_date) {
                        $.ajax({
                            type: 'GET',
                            data: {
                                start_date: this.start_date, end_date: this.end_date,
                                direct_date: this.direct_date
                            },
                            url: '{{ route('employee-requests-getDays') }}'
                        }).then(response => {
                            if (response.status == 500) {
                                this.error_message = response.message
                            } else {
                                this.days_count = response.data
                            }

                        })
                    }
                },
                getDaysCount2() {
                    this.days_count = 0
                    if (this.start_date && this.direct_date) {
                        $.ajax({
                            type: 'GET',
                            data: {
                                start_date: this.start_date, end_date: this.direct_date,
                            },
                            url: '{{ route('employee-requests-getDays') }}'
                        }).then(response => {
                            if (response.status == 500) {
                                this.error_message = response.message
                            } else {
                                this.days_count = response.data
                            }

                        })
                    }
                },
                getVacation2() {
                    this.days_count2 = ''
                    if (this.start_date && this.emp_id) {
                        $.ajax({
                            type: 'GET',
                            data: {
                                start_date: this.start_date, emp_id: this.emp_id,
                            },
                            url: '{{ route('employee-requests-getDays-now') }}'
                        }).then(response => {
                            this.days_count2 = response.data
                        });
                    }
                },

                getEmployee() {

                    this.employee = {}
                    this.days_available = ''
                    this.branch = {}
                    this.job = {}
                    this.division = {}
                    this.pending_vacations = {}
                    this.manager = {}
                    this.nationality = {}
                    this.social_status = {}

                    if (this.emp_id) {
                        $.ajax({
                            type: 'GET',
                            data: {emp_id: this.emp_id},
                            url: '{{ route('get-employee') }}'
                        }).then(response => {
                            this.employee = response.employee
                            this.days_available = response.days_available
                            this.branch = this.employee.branch
                            this.job = this.employee.job
                            this.division = this.employee.division
                            this.pending_vacations = this.employee.pending_vacation
                            this.manager = this.employee.manager
                            this.nationality = this.employee.nationality
                            this.social_status = this.employee.emp_social_status
                        })
                    }
                },

                getVacation() {
                    $.ajax({
                        type: 'GET',
                        data: {vacation_id: this.vacation_id},
                        url: '{{ route('get-employee-vacation') }}'
                    }).then(response => {
                        this.vacation = response.data
                        this.start_date = this.vacation.emp_request_start_date
                    })
                },
                {{--getVacation2() {--}}
                {{--this.days_count2 = ''--}}
                {{--this.vacation_message = ''--}}
                {{--if (this.emp_id) {--}}
                {{--$.ajax({--}}
                {{--type: 'GET',--}}
                {{--data: {--}}
                {{--emp_id: this.emp_id,--}}
                {{--},--}}
                {{--url: '{{ route('employee-requests-getDays-now') }}'--}}
                {{--}).then(response => {--}}
                {{--if (response.status == 500) {--}}
                {{--this.vacation_message = response.message--}}
                {{--} else {--}}
                {{--this.days_count2 = response.data--}}
                {{--}--}}

                {{--});--}}
                {{--}--}}
                {{--},--}}
            }
        });
    </script>

@endsection


@extends('Layouts.master')
@section('style')

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
@endsection
@section('content')

    <div id="app">

        <div class="section-body">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item">
                            <a href="#data-grid" data-toggle="tab"
                               class="nav-link active">@lang('home.data')</a>
                        </li>

                        <li class="nav-item"><a class="nav-link" href="#files-grid"
                                                data-toggle="tab">@lang('home.files')</a></li>
                        <li class="nav-item"><a class="nav-link" href="#notes-grid"
                                                data-toggle="tab">@lang('home.notes')</a></li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="section-body mt-3">
            <div class="container-fluid">

                <div class="tab-content mt-3">
                    {{-- dATA --}}
                    <div class="tab-pane fade active show"
                         id="data-grid" role="tabpanel">

                        <div class="row clearfix">

                            <div class="col-lg-12">

                                <form action="{{route('Bonds-capture.update',$bond->bond_id)}}"
                                      enctype="multipart/form-data" method="post">
                                    @csrf
                                    @method('put')

                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.sub_company')</label>
                                                    <input type="text" class="form-control" disabled=""
                                                           value="{{ app()->getLocale()=='ar' ? $bond->company->company_name_ar :
                                           $bond->company->company_name_en }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.branch')</label>
                                                    <input type="text" class="form-control" disabled=""
                                                           value="{{ app()->getLocale()=='ar' ? $bond->
                                           branch->branch_name_ar : $bond->branch->branch_name_en }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.created_date')</label>
                                                    <input type="text" value="{{ $bond->bond_date }}"
                                                           class="form-control"
                                                           disabled="">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.user')</label>
                                                    <input type="text" class="form-control" disabled=""
                                                           value="{{ app()->getLocale()=='ar' ? $bond->userCreated->user_name_ar :
                                                $bond->userCreated->user_name_en }}">
                                                </div>
                                            </div>

                                            {{--القيد--}}
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.journal')</label>
                                                    <a href="{{ route('journal-entries.show',$bond->journalCapture->journal_hd_id) }}"
                                                       class="btn btn-primary btn-sm">
                                                        @lang('home.journal_details')
                                                        {{$bond->journalCapture->journal_hd_code}}
                                                    </a>
                                                </div>
                                            </div>


                                            {{--رقم السند--}}
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.bond_code')</label>
                                                    <input type="text" class="form-control"
                                                           value="{{$bond->bond_code}}" disabled>
                                                </div>
                                            </div>

                                            @if($bond->bond_ref_no)
                                                {{--النشاط--}}
                                                <div class="col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.bonds_activity')</label>
                                                        <input type="text" disabled=""
                                                               @if($bond->transactionType) value="{{ app()->getLocale()=='ar' ?
                                     $bond->transactionType->app_menu_name_ar : $bond->transactionType->app_menu_name_en }}"
                                                               @endif
                                                               class="form-control">

                                                    </div>
                                                </div>

                                                {{--الرقم المرجعي--}}
                                                <div class="col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.reference_number')</label>
                                                        <input type="text" class="form-control" disabled=""
                                                               value="{{ $bond->bond_ref_no }}" name="bond_ref_no"
                                                               required>
                                                    </div>
                                                </div>
                                            @endif


                                            {{--نوع الحساب--}}
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.account_type')</label>

                                                    @if(($bond->customer_type == 'customer' || $bond->customer_type == 'supplier') && $bond->bond_ref_no)
                                                        @if($bond->customer && $bond->customer->cus_type)
                                                            <input type="text" disabled="" class="form-control" value="{{app()->getLocale()=='ar' ?
                                 $bond->customer->cus_type->system_code_name_ar : $bond->customer->cus_type->system_code_name_en }}">
                                                        @endif
                                                    @elseif($bond->customer_type == 'employee' && $bond->bond_ref_no)
                                                        <input type="text" disabled="" class="form-control" value="{{app()->getLocale()=='ar' ?
                                          'موظف' : 'employee'}}">

                                                    @elseif(!$bond->bond_ref_no)

                                                        <select class="form-control" required
                                                                @change="getAccountList()" name="account_type"
                                                                v-model="system_code">
                                                            <option value="">@lang('home.choose')</option>
                                                            @foreach($account_types as $account_type)
                                                                <option value="{{ $account_type->system_code }}">
                                                                    {{ app()->getLocale()=='ar' ? $account_type->system_code_name_ar :
                                                                         $account_type->system_code_name_en }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">

                                                    <label class="form-label">@lang('home.customer')</label>

                                                    @if(($bond->customer_type == 'customer' || $bond->customer_type == 'supplier') && $bond->bond_ref_no)
                                                        <input type="text" disabled="" class="form-control" value="{{ app()->getLocale()=='ar' ?
                                         $bond->customer->customer_name_full_ar :    $bond->customer->customer_name_full_en }}">
                                                    @elseif($bond->customer_type == 'employee' && $bond->bond_ref_no)
                                                        <input type="text" disabled="" class="form-control" value="{{ app()->getLocale()=='ar' ?
                                         $bond->customer->emp_name_full_ar :    $bond->customer->emp_name_full_en }}">

                                                    @elseif(!$bond->bond_ref_no)

                                                        <select class="form-control" v-model="customer_id"
                                                                name="customer_id" @change="getCustomerAccount()"
                                                                v-if="customers.length > 0">
                                                            <option v-for="customer in customers"
                                                                    :value="customer.customer_id">
                                                                @{{customer.customer_name_full_ar}}
                                                            </option>
                                                        </select>

                                                        <select class="form-control" v-model="customer_id"
                                                                name="customer_id" @change="getRelatedAccount()"
                                                                v-else-if="employees.length > 0">
                                                            <option v-for="employee in employees"
                                                                    :value="employee.emp_id">
                                                                @{{employee.emp_name_full_ar}}
                                                            </option>
                                                        </select>

                                                        <select class="form-control" v-model="customer_id"
                                                                name="customer_id" @change="getRelatedAccount()"
                                                                v-else-if="branches.length > 0">
                                                            <option v-for="branch in branches"
                                                                    :value="branch.branch_id">
                                                                @{{branch.branch_name_ar}}
                                                            </option>
                                                        </select>

                                                        <select class="form-control" v-model="customer_id"
                                                                name="customer_id" @change="getRelatedAccount()"
                                                                v-else-if="cars.length > 0">
                                                            <option v-for="car in cars"
                                                                    :value="car.truck_id">
                                                                @{{car.truck_name}}
                                                            </option>
                                                        </select>

                                                    @endif
                                                </div>
                                            </div>
                                            <input type="hidden" name="customer_type" v-model="customer_type">
                                            {{--{قم الحساب --}}
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.account_code')</label>
                                                    @if($bond->bond_ref_no)
                                                        <input type="text" class="form-control" disabled=""
                                                               name="bond_acc_id"
                                                               value="{{ $bond->bond_acc_id ? $bond->account->acc_code . $bond->account->acc_name_ar : ''}}">
                                                    @else
                                                        <input type="text" readonly class="form-control"
                                                               v-if="account_obj"
                                                               :value="account_obj.acc_code + account_obj.acc_name_ar">

                                                        <input type="hidden" name="bond_acc_id"
                                                               v-if="account_obj"
                                                               :value="account_obj.acc_id">
                                                    @endif
                                                </div>
                                            </div>

                                            {{--انواع الايرادات--}}
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.revenue_types')</label>
                                                    @if($bond->bondDocType && $bond->bond_ref_no)
                                                        <input type="text" class="form-control" disabled="" value="{{app()->getLocale()=='ar' ?
                                        $bond->bondDocType->system_code_name_ar :
                                    $bond->bondDocType->system_code_name_en }}">

                                                        <input type="hidden" name="bond_doc_type"
                                                               value="{{$bond->bond_doc_type}}">
                                                    @elseif(!$bond->bond_ref_no)
                                                        <select class="form-control"
                                                                name="bond_doc_type" v-model="system_code_type"
                                                                @change="getRelatedAccount()">
                                                            @foreach($system_code_types as $system_code)
                                                                <option value="{{$system_code->system_code_id}}">
                                                                    {{ app()->getLocale()=='ar' ?
                                                                $system_code->system_code_name_ar :  $system_code->system_code_name_en }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <input type="text" class="form-control" disabled=""
                                                               value="">
                                                    @endif


                                                </div>
                                            </div>

                                            {{--الحساب المرتبط--}}
                                            <div class="col-md-4">
                                                <div class="form-group mb-0">
                                                    <label class="form-label">@lang('home.related_account')</label>
                                                    <input type="text" disabled=""
                                                           :value="related_account_obj.acc_code + related_account_obj.acc_name_ar"
                                                           class="form-control"
                                                           required>
                                                </div>
                                            </div>

                                            {{--طرق الدفع --}}

                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.payment_method')</label>
                                                    <select class="form-control" v-model="payment_method_code"
                                                            @change="validInputs()" name="bond_method_type" required
                                                            :disabled="payment_disabled">
                                                        <ooption value="">@lang('home.choose')</ooption>
                                                        @foreach($payment_methods as $payment_method)
                                                            <option value="{{ $payment_method->system_code }}"
                                                                    @if($bond->bond_method_type == $payment_method->system_code)
                                                                    selected @endif>{{ app()->getLocale()=='ar' ?
                                                 $payment_method->system_code_name_ar : $payment_method->system_code_name_en }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{--رقم العمليه--}}
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.process_number')</label>
                                                    <input type="text" class="form-control" name="bond_check_no"
                                                           v-model="process_number" :disabled="process_number_valid"
                                                           :required="!process_number_valid">
                                                </div>
                                            </div>

                                            {{--البنك--}}
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.bank')</label>
                                                    <select class="form-control" name="bond_bank_id"
                                                            v-model="bond.bond_bank_id"
                                                            :disabled="bank_valid" :required="!bank_valid">
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($banks as $bank)
                                                            <option value="{{ $bank->system_code_id }}">
                                                                {{ app()->getLocale()=='ar' ? $bank->system_code_name_ar :
                                                                 $bank->system_code_name_en }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{--القيمه--}}
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.value')</label>
                                                    <input type="text" class="form-control"
                                                           value="{{ $bond->bond_amount_debit}}"
                                                           name="bond_amount_debit" readonly>
                                                </div>
                                            </div>


                                            <div class="col-sm-6 col-md-6">
                                                <label class="form-label">@lang('home.notes')</label>
                                                <textarea class="form-control" name="bond_notes">
                                                        {{ $bond->bond_notes ? $bond->bond_notes : '' }}
                                                    </textarea>
                                            </div>

                                        </div>
                                    </div>

                                    <button class="btn btn-primary" type="submit">@lang('home.save')</button>


                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- files part --}}
                    <div class="tab-pane fade" id="files-grid" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-12">

                                <x-files.form>
                                    <input type="hidden" name="transaction_id" value="{{ $bond->bond_id }}">
                                    <input type="hidden" name="app_menu_id" value="53">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('home.attachment_type')</label>
                                            <select class="form-control" name="attachment_type" required>
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($attachment_types as $attachment_type)
                                                    <option value="{{ $attachment_type->system_code_id }}">{{app()->getLocale()=='ar' ? $attachment_type->system_code_name_ar
                                                     : $attachment_type->system_code_name_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </x-files.form>

                                <x-files.attachment>

                                    @foreach($attachments as $attachment)
                                        <tr>
                                            <td>{{ app()->getLocale()=='ar' ?
                                         $attachment->attachmentType_2->system_code_name_ar :
                                          $attachment->attachmentType_2->system_code_name_en}}</td>
                                            <td>{{ date('d-m-Y', strtotime($attachment->issue_date )) }}</td>
                                            <td>{{ date('d-m-Y', strtotime($attachment->expire_date )) }}</td>
                                            <td>{{ $attachment->issue_date_hijri }}</td>
                                            <td>{{ $attachment->expire_date_hijri }}</td>
                                            <td>{{ $attachment->copy_no }}</td>
                                            <td>
                                                <a href="{{ url('/attachments/download-pdf?name=' . $attachment->attachment_file_url) }}">
                                                    <i class="fa fa-download text-blue fa-2x"></i></a>
                                                <a href="{{ asset('Files/'.$attachment->attachment_file_url) }}"
                                                   target="_blank" class="mr-1 ml-1"><i class="fa fa-eye text-blue"
                                                                                        style="font-size:20px"></i></a>
                                            </td>
                                            <td>
                                                <div class="badge text-gray text-wrap" style="width: 400px;">
                                                    {{ $attachment->attachment_data }}</div>
                                            </td>
                                            <td>{{ $attachment->userCreated->user_name_ar }}</td>
                                            <td>{{ $attachment->created_at }}</td>
                                        </tr>
                                    @endforeach

                                </x-files.attachment>

                            </div>
                        </div>
                    </div>

                    {{-- notes part --}}
                    <div class="tab-pane fade" id="notes-grid" role="tabpanel">

                        <div class="row">
                            <div class="col-lg-12">
                                <x-files.form-notes>
                                    <input type="hidden" name="transaction_id" value="{{ $bond->bond_id }}">
                                    <input type="hidden" name="app_menu_id" value="53">
                                </x-files.form-notes>

                                <x-files.notes>
                                    @foreach($notes as $note)
                                        <tr>
                                            <td>
                                                <div class="badge text-gray text-wrap" style="width: 400px;">
                                                    {{ $note->notes_data }}</div>
                                            </td>
                                            <td>{{ date('d-m-Y', strtotime($note->notes_date )) }}</td>
                                            <td>{{ $note->user->user_name_ar }}</td>
                                            <td>{{ $note->notes_serial }}</td>
                                        </tr>
                                    @endforeach
                                </x-files.notes>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">

        $(function () {
            $("#issue_date_hijri").hijriDatePicker();
            $("#expire_date_hijri").hijriDatePicker();
        });

    </script>
    <script>
        $(document).ready(function () {

            $('#add_files').click(function () {
                var display = $("#add_files_form").css("display");
                if (display == 'none') {
                    $('#add_files_form').css('display', 'block')
                } else {
                    $('#add_files_form').css('display', 'none')
                }

            });

            $('#add_note').click(function () {
                var display = $("#add_note_form").css("display");
                if (display == 'none') {
                    $('#add_note_form').css('display', 'block')
                } else {
                    $('#add_note_form').css('display', 'none')
                }
            });

        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>

        new Vue({
            el: '#app',
            data: {
                expire_date: '',
                issue_date: '',
                issue_date_hijri: '',
                expire_date_hijri: '',
                date: '',
                bond: {},
                bond_id: '',
                payment_method_code: '',
                process_number_valid: true,
                process_number: '',
                payment_disabled: true,
                bank_valid: true,
                bank: '',
                system_code: '',
                employees: [],
                customers: [],
                branches: [],
                cars: [],
                customer_id: '',
                account_obj: {},
                related_account_obj: {},
                system_code_type: ''
            },
            mounted() {
                this.bond_id = {!! $id !!}
                    this.getBond()

                $('#issue_date_hijri').on("dp.change", (e) => {
                    this.issue_date_hijri = $('#issue_date_hijri').val()
                    this.getGeorgianDate()
                });

                $('#expire_date_hijri').on("dp.change", (e) => {
                    this.expire_date_hijri = $('#expire_date_hijri').val()
                    this.getGeorgianDate2()
                });

            },
            methods: {
                getAccountList() {
                    this.customers = []
                    this.employees = []
                    this.branches = []
                    this.cars = []
                    // this.account_obj = {}
                    $.ajax({
                        type: 'GET',
                        data: {system_code: this.system_code},
                        url: '{{ route("Bonds-capture.getAccountList") }}'
                    }).then(response => {
                        if (response.employees) {
                            this.employees = response.employees
                        } else if (response.customers) {
                            this.customers = response.customers
                        } else if (response.branches) {
                            this.branches = response.branches
                        } else if (response.suppliers) {
                            this.customers = response.suppliers
                        } else if (response.cars) {
                            this.cars = response.cars
                        }
                    })
                },
                getCustomerAccount() {
                    this.account_obj = {}
                    //عميل او مورد
                    if (this.customer_id) {
                        $.ajax({
                            type: 'GET',
                            data: {customer_id: this.customer_id},
                            url: '{{ route("Bonds-capture.getCustomerAccount") }}'
                        }).then(response => {
                            this.account_obj = response.account
                        })
                    }

                },
                getBond() {
                    this.payment_disabled = true
                    if (this.bond_id) {
                        $.ajax({
                            type: 'GET',
                            data: {bond_id: this.bond_id},
                            url: ''
                        }).then(response => {

                            this.bond = response.data
                            this.customer_id = this.bond.customer_id
                            this.system_code_type = this.bond.bond_doc_type

                            this.getRelatedAccount()

                            this.account_obj = response.account

                            this.system_code = response.account_type
                            this.getAccountList()
                            this.payment_method_code = this.bond.bond_method_type
                            if (this.payment_method_code == 57001 || this.payment_method_code == 57002 || this.payment_method_code == 57003 || this.payment_method_code == 57004 || this.payment_method_code == 57005 || this.payment_method_code == 57006) {
                                this.payment_disabled = false
                            } else {
                                this.payment_disabled = true
                            }

                            if (this.payment_method_code == 57005) {
                                this.bank_valid = false
                                this.process_number_valid = false
                                this.process_number = this.bond.bond_check_no
                            }

                            this.process_number = this.bond.bond_check_no
                            if (this.process_number && this.payment_method_code == 57002 || this.process_number && this.payment_method_code == 57003) {
                                this.process_number_valid = false
                            }
                            this.bank = this.bond.bond_bank_id
                        })
                    }
                },
                getGeorgianDate() {
                    if (this.issue_date_hijri) {
                        $.ajax({
                            type: 'GET',
                            data: {date: this.issue_date_hijri},
                            url: '{{ route("test-date") }}'
                        }).then(response => {
                            this.issue_date = response.data
                        })
                    }
                },
                getGeorgianDate2() {
                    if (this.expire_date_hijri) {
                        $.ajax({
                            type: 'GET',
                            data: {date: this.expire_date_hijri},
                            url: '{{ route("test-date") }}'
                        }).then(response => {
                            this.expire_date = response.data
                        })
                    }
                },
                getIssueDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.issue_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.issue_date_hijri = response.data
                    })
                },
                getExpireDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.expire_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.expire_date_hijri = response.data
                    })
                },
                getIssueDateHijri() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.issue_date_hijri},
                        url: '{{ route("api.getDate2") }}'
                    }).then(response => {
                        this.issue_date = response.data
                    })
                },
                validInputs() {
                    console.log(this.payment_method_code)
                    //التقدي
                    if (this.payment_method_code == 57001) {
                        this.bank_valid = true
                        this.process_number_valid = true
                        this.bank = ''
                        this.process_number = ''
                    }
                    //مدي وفيزا وماستر
                    if (this.payment_method_code == 57002 || this.payment_method_code == 57003 || this.payment_method_code == 57006
                        || this.payment_method_code == 57004) {
                        this.process_number_valid = false
                        this.bank_valid = true
                        this.bank = ''
                    }

                    /// تحويل
                    if (this.payment_method_code == 57005) {
                        this.bank_valid = false
                        this.process_number_valid = false
                    }


                },
                getRelatedAccount() {
                    this.related_account = ''
                    this.related_account_obj = {}
                    if (this.emp_id || this.branch_id) {
                        this.account_obj = {}
                    }

                    $.ajax({
                        type: 'GET',
                        data: {system_code_id: this.system_code_type},
                        url: '{{ route("Bonds-capture.getRelatedAccount") }}'
                    }).then(response => {
                        this.related_account = response.data
                        this.related_account_obj = response.account
                        if (this.emp_id || this.branch_id) {
                            this.account_obj = response.account
                        }
                    })

                }
            },
            computed: {
                customer_type: function () {
                    if (this.emp_id) {
                        return 'employee'
                    }
                    if (this.branch_id) {
                        return 'branch'
                    }
                    if (this.customer_id) {
                        return 'customer'
                    }
                },
            }
        });
    </script>
@endsection

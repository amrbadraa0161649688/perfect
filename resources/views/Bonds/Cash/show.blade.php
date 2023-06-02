@extends('Layouts.master')
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
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.bond_code')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       value="{{$bond->bond_code}}">
                                            </div>
                                        </div>

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
                                                <input type="text" value="{{ $bond->bond_date }}" class="form-control"
                                                       disabled="">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.user')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       value="{{ app()->getLocale()=='ar' ? $bond->userCreated->user_name_ar :
                                                $bond->userCreated->user_name_en }}">
                                            </div>
                                        </div>

                                        {{--النشاط--}}
                                        <div class="col-sm-5 col-md-5">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.bonds_activity')</label>
                                                <input type="text" disabled="" @if($bond->transactionType) value="{{ app()->getLocale()=='ar' ?
                                     $bond->transactionType->app_menu_name_ar : $bond->transactionType->app_menu_name_en }}"
                                                       @endif    class="form-control">

                                            </div>
                                        </div>

                                        {{--الرقم المرجعي--}}
                                        <div class="col-sm-5 col-md-5">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.reference_number')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       value="{{ $bond->bond_ref_no }}" name="bond_ref_no" required>
                                            </div>
                                        </div>


                                        {{--نوع الحساب--}}
                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.account_type')</label>
                                                @if($bond->customer_type == 'customer' || $bond->customer_type == 'supplier')
                                                    @if($bond->customer && $bond->customer->cus_type)
                                                        <input type="text" disabled="" class="form-control" value="{{app()->getLocale()=='ar' ?
                                 $bond->customer->cus_type->system_code_name_ar : $bond->customer->cus_type->system_code_name_en }}">
                                                    @endif
                                                @elseif($bond->customer_type == 'employee')
                                                    <input type="text" disabled="" class="form-control" value="{{app()->getLocale()=='ar' ?
                                          'موظف' : 'employee'}}">
                                                @elseif($bond->customer_type == 'branch')
                                                    <input type="text" disabled="" class="form-control" value="{{app()->getLocale()=='ar' ?
                                          'فرع' : 'branch'}}">
                                                @elseif($bond->customer_type == 'car')
                                                    <input type="text" disabled="" class="form-control" value="{{app()->getLocale()=='ar' ?
                                          'شاحنه' : 'truck'}}">
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                @if($bond->customer_type == 'customer' || $bond->customer_type == 'supplier' )
                                                    <label class="form-label">@lang('home.customer')</label>

                                                    <input type="text" disabled="" class="form-control" value="{{ app()->getLocale()=='ar' ?
                                         $bond->customer->customer_name_full_ar :    $bond->customer->customer_name_full_en }}">
                                                @elseif($bond->customer_type == 'employee')
                                                    <label class="form-label">@lang('home.employee')</label>

                                                    <input type="text" class="form-control" disabled="" value="{{ app()->getLocale()=='ar' ?
                                         $bond->customer->emp_name_full_ar :    $bond->customer->emp_name_full_en }}">
                                                @elseif($bond->customer_type == 'branch')
                                                    <label class="form-label">@lang('home.branch')</label>

                                                    <input type="text" class="form-control" disabled="" value="{{ app()->getLocale()=='ar' ?
                                         $bond->customer->branch_name_ar :    $bond->customer->branch_name_en }}">
                                                @elseif($bond->customer_type == 'car')
                                                    <label class="form-label">@lang('home.truck')</label>

                                                    <input type="text" disabled="" class="form-control"
                                                           value="{{$bond->truck->truck_code .$bond->truck->truck_name }}">
                                                @endif
                                            </div>
                                        </div>

                                        {{--{قم الحساب --}}
                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.account_code')</label>
                                                @if($bond->account)
                                                    @if(app()->getLocale()=='ar')
                                                        <input type="text" class="form-control" disabled=""
                                                               name="bond_acc_id" value="{{ $bond->account->acc_code
                                            ? $bond->account->acc_code .$bond->account->acc_name_ar : ''  }}">
                                                    @else
                                                        <input type="text" class="form-control" disabled=""
                                                               name="bond_acc_id" value="{{ $bond->account->acc_code
                                            ? $bond->account->acc_code .$bond->account->acc_name_en : ''  }}">
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                        {{--انواع الايرادات--}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.cash_types')</label>

                                                <input type="text" class="form-control" disabled="" value="{{app()->getLocale()=='ar' ? $bond->bondDocType->system_code_name_ar :
                                    $bond->bondDocType->system_code_name_en }}">
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.journal')</label>
                                                @if($bond->journalCash)
                                                    <a href="{{route('journal-entries.show',$bond->journalCash->journal_hd_id)}}"
                                                       class="btn btn-primary btn-block">{{$bond->journalCash->journal_hd_code}}</a>

                                                @else
                                                    <input type="text" class="form-control" value="لا يوجد قيد"
                                                           readonly>
                                                @endif

                                            </div>
                                        </div>

                                        {{--طرق الدفع--}}
                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.payment_method')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       value="{{ app()->getLocale()=='ar' ? $bond->paymentMethod->system_code_name_ar :
                                     $bond->paymentMethod->system_code_name_en }}">
                                            </div>
                                        </div>

                                        {{--رقم العمليه--}}
                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.process_number')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       value="{{ $bond->bond_check_no ? $bond->bond_check_no : ''}}">
                                            </div>
                                        </div>

                                        {{--البنك--}}
                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.bank')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       value="{{  $bond->bank ? $bond->bank->system_code_name_en : ''}}">
                                            </div>
                                        </div>

                                        {{--القيمه بدون الضريبه--}}
                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.value')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       value="{{ $bond->bond_amount_credit - $bond->bond_vat_amount}}">
                                            </div>
                                        </div>

                                        {{--نسبه اضريبه--}}
                                        <div class="col-sm-6 col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.vat_rate')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       value="{{ $bond->bond_vat_rate}}" name="bond_vat_rate">
                                            </div>
                                        </div>

                                        {{--قيمه اضريبه--}}
                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.vat_amount')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       value="{{ $bond->bond_vat_amount}}" name="bond_vat_amount">
                                            </div>
                                        </div>

                                        {{-- الاجمالي شامل الضريبه--}}
                                        <div class="col-sm-6 col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.total_value')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       value="{{ $bond->bond_amount_credit}}" name="bond_amount_credit">
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <label class="form-label">@lang('home.notes')</label>
                                            <textarea class="form-control" name="bond_notes" disabled="">
                                    {{ $bond->bond_notes ? $bond->bond_notes : '' }}
                                </textarea>
                                        </div>

                                    </div>
                                </div>
                                {{--<div class="card-footer text-right">--}}
                                {{--<a href="{{ route('Bonds-capture.export-pdf',$bond->bond_id) }}" target="_blank"--}}
                                {{--class="btn btn-primary">@lang('home.print')</a>--}}
                                {{--</div>--}}

                            </div>

                        </div>
                    </div>


                    {{-- files part --}}
                    <div class="tab-pane fade" id="files-grid" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-12">

                                <x-files.form>
                                    <input type="hidden" name="transaction_id" value="{{ $bond->bond_id }}">
                                    <input type="hidden" name="app_menu_id" value="54">
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
                                    <input type="hidden" name="app_menu_id" value="54">
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
                date: ''
            },
            mounted() {
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
                }

            }
        });
    </script>
@endsection

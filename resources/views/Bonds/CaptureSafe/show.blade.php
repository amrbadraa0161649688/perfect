@extends('Layouts.master')
@section('content')

    <div id="app">

        <div class="section-body mt-3">
            <div class="container-fluid">

                <div class="tab-content mt-3">
                    {{-- dATA --}}
                    <div class="tab-pane fade active show"
                         id="data-grid" role="tabpanel">

                        <div class="row clearfix">

                            <div class="col-lg-12">

                                <form action="#"
                                      enctype="multipart/form-data" method="post">
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

                                            @if($bond->journalCapture)
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
                                            @endif


                                            {{--رقم السند--}}
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.bond_code')</label>
                                                    <input type="text" class="form-control"
                                                           value="{{$bond->bond_code}}" disabled>
                                                </div>
                                            </div>

                                            {{--النشاط--}}
                                            <div class="col-sm-6 col-md-6" hidden>
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.bonds_activity')</label>
                                                    <input type="text" disabled="" @if($bond->transactionType) value="{{ app()->getLocale()=='ar' ?
                                     $bond->transactionType->app_menu_name_ar : $bond->transactionType->app_menu_name_en }}"
                                                           @endif
                                                           class="form-control">

                                                </div>
                                            </div>

                                            {{--الرقم المرجعي--}}
                                            <div class="col-sm-6 col-md-6" hidden>
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

                                                        <input type="text" disabled="" class="form-control" value="{{app()->getLocale()=='ar' ?
                                 $bond->customer->cus_type->system_code_name_ar : $bond->customer->cus_type->system_code_name_en }}">
                                                    @elseif($bond->customer_type == 'employee')
                                                        <input type="text" disabled="" class="form-control" value="{{app()->getLocale()=='ar' ?
                                          'موظف' : 'employee'}}">
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">

                                                    <label class="form-label">@lang('home.customer')</label>

                                                    @if($bond->customer_type == 'customer' || $bond->customer_type == 'supplier')
                                                        <input type="text" disabled="" class="form-control" value="{{ app()->getLocale()=='ar' ?
                                         $bond->customer->customer_name_full_ar : $bond->customer->customer_name_full_en }}">
                                                    @elseif($bond->customer_type == 'employee')
                                                        <input type="text" disabled="" value="{{ app()->getLocale()=='ar' ?
                                         $bond->customer->emp_name_full_ar :    $bond->customer->emp_name_full_en }}">
                                                    @endif
                                                </div>
                                            </div>

                                            {{--{قم الحساب --}}
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.account_code')</label>
                                                    <input type="text" class="form-control" disabled=""
                                                           name="bond_acc_id"
                                                           value="{{ $bond->bond_acc_id ? $bond->account->acc_code . $bond->account->acc_name_ar : ''}}">
                                                </div>
                                            </div>

                                            {{--انواع الايرادات--}}
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.revenue_types')</label>
                                                    @if($bond->bondDocType)
                                                        <input type="text" class="form-control" disabled="" value="{{app()->getLocale()=='ar' ?
                                        $bond->bondDocType->system_code_name_ar :
                                    $bond->bondDocType->system_code_name_en }}">
                                                    @else
                                                        <input type="text" class="form-control" disabled="" value="">
                                                    @endif
                                                </div>
                                            </div>


                                            {{--طرق الدفع--}}
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.payment_method')</label>
                                                    @if( $bond->paymentMethod)
                                                        <input type="text" class="form-control" disabled=""
                                                               value="{{ app()->getLocale()=='ar' ? $bond->paymentMethod->system_code_name_ar :
                                                       $bond->paymentMethod->system_code_name_en }}">
                                                    @endif
                                                </div>
                                            </div>


                                            {{--رقم العمليه--}}
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.process_number')</label>
                                                    <input type="text" class="form-control" name="bond_check_no"
                                                           value="{{$bond->bond_check_no}}" disabled="">
                                                </div>
                                            </div>

                                            {{--البنك--}}
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.bank')</label>

                                                    <input type="text" class="form-control" disabled=""
                                                           name="{{$bond->bank ? $bond->bank->system_code_name_ar : ''}}">
                                                </div>
                                            </div>

                                            {{--القيمه--}}
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.value')</label>
                                                    <input type="text" class="form-control"
                                                           value="{{ $bond->bond_amount_debit}}"
                                                           name="bond_amount_debit" disabled="">
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

                                </form>
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
@endsection

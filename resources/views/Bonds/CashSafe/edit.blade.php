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

    <div id="app">
        <div class="section-body mt-3">
            <div class="container-fluid">

                <div class="tab-content mt-3">
                    {{-- dATA --}}
                    <div class="tab-pane fade active show"
                         id="data-grid" role="tabpanel">

                        <form action="{{route('bonds.cash.safe.update',$bond->bond_id)}}" method="post">
                            @csrf
                            @method('put')
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
                                                    <input type="text" value="{{ $bond->bond_date }}"
                                                           class="form-control"
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
                                            <div class="col-sm-5 col-md-5" hidden>
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.bonds_activity')</label>
                                                    <input type="text" disabled="" @if($bond->transactionType) value="{{ app()->getLocale()=='ar' ?
                                     $bond->transactionType->app_menu_name_ar : $bond->transactionType->app_menu_name_en }}"
                                                           @endif    class="form-control">

                                                </div>
                                            </div>

                                            {{--الرقم المرجعي--}}
                                            <div class="col-sm-5 col-md-5" hidden>
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


                                            {{--انواع المصروفات--}}
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.cash_types')</label>
                                                    <select class="form-control" data-live-search="true"
                                                            name="bond_doc_type" v-model="system_code_type" required
                                                            @change="getRelatedAccount()">
                                                        @foreach($system_code_types as $system_code)
                                                            <option value="{{$system_code->system_code_id}}">
                                                                {{ app()->getLocale()=='ar' ?
                                                            $system_code->system_code_name_ar :     $system_code->system_code_name_en }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            @if($bond->journalCash)
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.journal')</label>
                                                        <a href="{{route('journal-entries.show',$bond->journalCash->journal_hd_id)}}"
                                                           class="btn btn-primary btn-block">{{$bond->journalCash->journal_hd_code}}</a>
                                                    </div>
                                                </div>
                                            @endif

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
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.bank')</label>
                                                    <select class="form-control" name="bond_bank_id" v-model="bank"
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

                                            {{--القيمه بدون الضريبه--}}
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.value')</label>
                                                    <input type="text" class="form-control"
                                                           id="bond_amount_with_out_vat"
                                                           value="{{ $bond->bond_amount_credit - $bond->bond_vat_amount}}">
                                                </div>
                                            </div>

                                            {{--نسبه اضريبه--}}
                                            <div class="col-sm-6 col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.vat_rate')</label>
                                                    <input type="text" class="form-control"
                                                           id="bond_vat_rate" readonly
                                                           value="{{ $bond->bond_vat_rate}}" name="bond_vat_rate">
                                                </div>
                                            </div>

                                            {{--قيمه اضريبه--}}
                                            <div class="col-sm-6 col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.vat_amount')</label>
                                                    <input type="text" class="form-control" id="bond_vat_amount"
                                                           readonly
                                                           value="{{ $bond->bond_vat_amount}}" name="bond_vat_amount">
                                                </div>
                                            </div>

                                            {{-- الاجمالي شامل الضريبه--}}
                                            <div class="col-sm-6 col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.total_value')</label>
                                                    <input type="text" class="form-control"
                                                           id="bond_amount_credit" readonly
                                                           value="{{ $bond->bond_amount_credit}}"
                                                           name="bond_amount_credit">
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
                                </div>

                                <div class="card-footer">
                                    <button class="btn btn-primary">{{__('edit')}}</button>
                                </div>
                            </div>
                        </form>

                        @if(!$bond->journalCash)
                            <div class="row mb-5">
                                <div class="col-md-12">
                                    <form action="{{route('bonds.cash.safe.approve')}}" method="post"
                                          style="float:left">
                                        @csrf
                                        <input type="hidden" name="bond_id" value="{{$bond->bond_id}}">
                                        <button type="submit" class="btn btn-primary btn-lg">{{__('approve')}}</button>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#bond_amount_with_out_vat').keyup(function () {
                $('#bond_vat_amount').val($('#bond_amount_with_out_vat').val() * $('#bond_vat_rate').val())
                $('#bond_amount_credit').val(parseFloat($('#bond_amount_with_out_vat').val() * $('#bond_vat_rate').val()) + parseFloat($('#bond_amount_with_out_vat').val()))
            });

            $('#bond_vat_rate').keyup(function () {
                $('#bond_vat_amount').val($('#bond_amount_with_out_vat').val() * $('#bond_vat_rate').val())
                $('#bond_amount_credit').val(parseFloat($('#bond_amount_with_out_vat').val() * $('#bond_vat_rate').val()) + parseFloat($('#bond_amount_with_out_vat').val()))
            })
        })

    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                bond: {},
                bond_id: '',
                payment_method_code: '',
                process_number_valid: true,
                process_number: '',
                payment_disabled: true,
                bank_valid: true,
                bank: '',
                system_code_type: ''
            },
            mounted() {
                this.bond_id = {!! $id !!}
                    this.getBond()
            },
            methods: {
                getBond() {
                    this.payment_disabled = true
                    if (this.bond_id) {
                        $.ajax({
                            type: 'GET',
                            data: {bond_id: this.bond_id},
                            url: ''
                        }).then(response => {

                            this.bond = response.data
                            this.payment_method_code = this.bond.bond_method_type
                            if (this.payment_method_code == 57001 || this.payment_method_code == 57002 || this.payment_method_code == 57003) {
                                this.payment_disabled = false
                            } else {
                                this.payment_disabled = true
                            }

                            this.process_number = this.bond.bond_check_no
                            if (this.process_number && this.payment_method_code == 57002 || this.process_number && this.payment_method_code == 57003) {
                                this.process_number_valid = false
                            } else {
                                this.process_number_valid = true
                            }
                            this.bank = this.bond.bond_bank_id
                            this.system_code_type = this.bond.bond_doc_type
                        })
                    }
                },
                validInputs() {
                    //التقدي
                    if (this.payment_method_code == 57001) {
                        this.bank_valid = true
                        this.process_number_valid = true
                        this.bank = ''
                        this.process_number = ''
                    }
                    //مدي وفيزا وماستر
                    if (this.payment_method_code == 57002 || this.payment_method_code == 57003
                        || this.payment_method_code == 57004) {
                        this.process_number_valid = false
                        this.bank_valid = true
                        this.bank = ''
                    }

                    //تحويل
                    if (this.payment_method_code == 57005) {
                        this.bank_valid = false
                        this.process_number_valid = false
                    }


                },

            }

        });
    </script>
@endsection

@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>
    <style type="text/css">
        .ctd {
            text-align: center;

        }

        .full {
            padding-left: 40%;
        }
    </style>

@endsection

@section('content')

    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">

                <div class="header-action">

                </div>
            </div>
        </div>
    </div>


    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="data-grid" role="tabpanel">
                    <div class="card-body">
                        <div class="row card">
                            <form id="item_data_form" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="row">
                                            <input type="hidden" class="form-control" name="store_hd_id"
                                                   id="store_hd_id" value="{{ $purchase->store_hd_id }}">
                                            <input type="hidden" class="form-control" name="purchase_uuid"
                                                   id="purchase_uuid" value="{{ $purchase->uuid }}">
                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label "> رقم مرتجع
                                                    مورد </label>
                                                <input type="text" class="form-control" name="store_hd_code"
                                                       id="store_hd_code" value="{{ $purchase->store_hd_code }}"
                                                       readonly disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('purchase.item_category') </label>
                                                <select class="form-select form-control" name="store_category_type"
                                                        id="store_category_type" disabled>
                                                    <option value="" selected> choose</option>
                                                    @foreach($warehouses_type_list as $w_t)
                                                        <option value="{{$w_t->system_code}}" {{($purchase->store_category_type == $w_t->system_code_id? 'selected': '' )}}> {{ $w_t->getSysCodeName() }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label">  @lang('purchase.vendor') </label>
                                                <select class="form-select form-control" name="store_acc_no"
                                                        id="store_acc_no" required disabled>
                                                    <option value="" selected> choose</option>
                                                    @foreach($vendor_list as $vendor)
                                                        <option value="{{$vendor->customer_id}}"
                                                                data-vendorname="{{ $vendor->getCustomerName() }}"
                                                                data-vendorvat="{{ $vendor->customer_vat_no }}" {{($purchase->store_acc_no == $vendor->customer_id ? 'selected': '' )}}> {{ $vendor->getCustomerName() }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label "> تاريخ مرتج
                                                    المورد </label>
                                                <input type="text" class="form-control" name="created_date"
                                                       id="created_date"
                                                       value="{{ $purchase->created_date->format('Y-m-d H:m') }}"
                                                       readonly disabled>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('purchase.vendor_name') </label>
                                                <input type="text" class="form-control" name="store_acc_name"
                                                       id="store_acc_name" value="{{$purchase->store_acc_name}}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('purchase.vat_no') </label>
                                                <input type="number" class="form-control" name="store_acc_tax_no"
                                                       id="store_acc_tax_no" value="{{$purchase->store_acc_tax_no}}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('purchase.payment_method')  </label>
                                                <select class="form-select form-control" name="store_vou_pay_type"
                                                        id="store_vou_pay_type" required>
                                                    <option value="" selected> choose</option>
                                                    @foreach($payemnt_method_list as $p_method)
                                                        <option value="{{$p_method->system_code}}" {{($purchase->store_vou_pay_type == $p_method->system_code_id? 'selected': '' )}}> {{ $p_method->getSysCodeName() }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> {{__('Discount Value')}} </label>
                                                <input type="number" class="form-control " name="vou_discount_rate"
                                                       id="vou_discount_rate" value="{{$purchase->store_vou_desc }}"
                                                       @if($purchase->store_vou_desc > 0 )  readonly @endif>
                                            </div>

                                            @if($purchase->store_vou_ref_before)
                                                <div class="col-md-4">
                                                    <label for="recipient-name"
                                                           class="col-form-label "> {{__('sales receiving')}} </label>
                                                    <a href="{{route('store-purchase-receiving.edit',$purchase->invSalesUuid)}}"
                                                       target="_blank" class="btn btn-success btn-block">
                                                        {{$purchase->store_vou_ref_before }}
                                                    </a>
                                                </div>
                                            @endif

                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> {{__('Payment')}} </label>
                                                <input type="text" class="form-control" disabled
                                                       value="{{$purchase->store_vou_payment}}">
                                            </div>

                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label">@lang('purchase.note') </label>
                                                <textarea rows="2" class="form-control" name="store_vou_notes"
                                                          id="store_vou_notes" placeholder="Here can be your note"
                                                          value=""> {{$purchase->tore_vou_notes }}</textarea>
                                            </div>

                                            <div class="col-md-4">
                                                <br>
                                                <br>
                                                <button type="button" onclick="saveItemRow(2)"
                                                        class="btn btn-primary btn-block">
                                                    <i class="fe fe-save mr-2"></i> تحديث
                                                </button>
                                            </div>

                                            @if($purchase->store_vou_payment != $purchase->store_vou_total)
                                                <div class="col-md-4">
                                                    <br>
                                                    <br>
                                                    <button type="button" class="btn btn-primary btn-lg"
                                                            data-toggle="modal"
                                                            data-target="#exampleModal" data-whatever="@mdo">
                                                        @lang('home.add_bond')
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>


                        {{-- bond modal --}}
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"
                                            id="exampleModalLabel">@lang('home.add_capture_bond')</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <form action="{{ route('store-purchase-return.addBondWithJournal2') }}"
                                              method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.sub_company')</label>
                                                        @if(session('company'))
                                                            <input type="text" class="form-control" disabled=""
                                                                   value="{{ app()->getLocale()=='ar' ? session('company')['company_name_ar']
                                                            : session('company')['company_name_en ']}}">
                                                        @else
                                                            <input type="text" class="form-control" disabled=""
                                                                   value="{{ app()->getLocale()=='ar' ? auth()->user()->company->company_name_ar
                                                            :  auth()->user()->company->company_name_en }}">
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.branch')</label>
                                                        <input type="text" class="form-control" disabled=""
                                                               value="{{ app()->getLocale()=='ar' ? session('branch')['branch_name_ar']
                                                            : session('branch')['branch_name_en'] }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.created_date')</label>
                                                        <input type="text" id="date" class="form-control" disabled="">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.user')</label>
                                                        <input type="text" class="form-control" disabled=""
                                                               placeholder="Company"
                                                               value="{{ app()->getLocale()=='ar' ? auth()->user()->user_name_ar :
                                                auth()->user()->user_name_en }}">
                                                    </div>
                                                </div>


                                                <input type="hidden" name="transaction_id"
                                                       value="{{$purchase->store_hd_id}}">
                                                {{--النشاط--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.bonds_activity')</label>

                                                        <input type="text" disabled="" value="مرتجع مورد"
                                                               class="form-control">
                                                        <input type="hidden" name="transaction_type" value="66">

                                                    </div>
                                                </div>

                                                {{--الرقم المرجعي--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.reference_number')</label>

                                                        <input type="text" class="form-control"
                                                               name="bond_ref_no"
                                                               value="{{ $purchase->store_hd_code }}" readonly>
                                                    </div>
                                                </div>

                                                {{--القيمه المستحقه--}}
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.deserved_value')</label>
                                                        <input type="text" class="form-control" readonly
                                                               id="deserved_value"
                                                               value="{{ $purchase->store_vou_total - $purchase->store_vou_payment <0 ?  0 :
                                                               round($purchase->store_vou_total - $purchase->store_vou_payment,2)}}">

                                                    </div>
                                                </div>

                                                {{--نوع الحساب--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.account_type')</label>

                                                        <input type="text" class="form-control" readonly
                                                               value="{{app()->getLocale()=='ar' ? \App\Models\SystemCode::where('system_code',56001)
                                                   ->first()->system_code_name_ar : \App\Models\SystemCode::where('system_code',56001)
                                                   ->first()->system_code_name_en}}">
                                                        <input type="hidden" value="{{ \App\Models\SystemCode::where('system_code',56001)
                                            ->first()->system_code_id}}" name="account_type">
                                                    </div>
                                                </div>

                                                {{--نوع العميل--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <input type="hidden" name="customer_type" value="supplier">

                                                        <label class="form-label">@lang('home.suppliers')</label>

                                                        <input type="text" readonly class="form-control"
                                                               value="{{app()->getLocale()=='ar' ? $purchase->vendor->customer_name_full_ar :
                                                              $purchase->vendor->customer_name_full_en  }}">
                                                        <input type="hidden" name="customer_id"
                                                               value="{{ $purchase->vendor->customer_id }}">
                                                    </div>
                                                </div>

                                                {{--{قم الحساب للمورد--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.account_code')</label>
                                                        <input type="hidden" class="form-control"
                                                               name="bond_acc_id"
                                                               value="{{ $purchase->vendor->customer_account_id}}">
                                                        <input type="text" readonly class="form-control"
                                                               value="{{app()->getLocale() == 'ar' ?
                                                               $purchase->vendor->account->acc_name_ar :
                                                               $purchase->vendor->account->acc_name_en}} . {{ $purchase->vendor->account->acc_code }}">

                                                    </div>
                                                </div>

                                                {{--انواع الايرادات--}}
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.revenue_types')</label>
                                                        <select class="selectpicker" data-live-search="true"
                                                                name="bond_doc_type" required>
                                                            <option value="">@lang('home.choose')</option>
                                                            @foreach($system_code_types as $system_code)
                                                                <option value="{{$system_code->system_code_id}}"
                                                                        @if($system_code->system_code == 580002) selected @endif>
                                                                    {{ app()->getLocale()=='ar' ?
                                                                $system_code->system_code_name_ar :     $system_code->system_code_name_en }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{--طرق الدفع--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.payment_method')</label>
                                                        <select class="form-control" v-model="payment_method_code"
                                                                @change="validInputs()" name="bond_method_type"
                                                                required>
                                                            <ooption value="">@lang('home.choose')</ooption>
                                                            @foreach($payment_methods as $payment_method)
                                                                <option value="{{ $payment_method->system_code }}">{{ app()->getLocale()=='ar' ?
                                                 $payment_method->system_code_name_ar : $payment_method->system_code_name_en }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{--رقم العمليه--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.process_number')</label>
                                                        <input type="text" class="form-control"
                                                               :disabled="process_number_valid"
                                                               v-model="process_number" name="process_number"
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

                                                {{--القيمه--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.value')</label>
                                                        <input type="text" class="form-control"
                                                               id="value"
                                                               value="{{$purchase->store_vou_total - $purchase->store_vou_payment < 0 ? 0 :
                                                               round($purchase->store_vou_total - $purchase->store_vou_payment,2)}}"
                                                               name="bond_amount_credit" required>
                                                        <small class="text-danger" id="error_message">
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-6">
                                                    <label class="form-label">@lang('home.notes')</label>
                                                    <textarea class="form-control" name="bond_notes"
                                                              placeholder="@lang('home.notes')"></textarea>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary btn-sm"
                                                            id="submit_button">
                                                        @lang('home.add_bond')
                                                    </button>
                                                </div>


                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row card">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#search_item_modal">
                                <i class="fe fe-search mr-2"></i> عرض الاصناف
                            </button>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <br>
                                    <div class="table-responsive">
                                        @include('store.purchase.return.table.item_table')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer row">
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{config('app.telerik_server')}}?rpt={{$purchase->report_url_ins_r->report_url}}&id={{$purchase->uuid}}&lang=ar&skinName=bootstrap"
                                   title="{{trans('Print')}}" class="btn btn-warning btn-block" id="showReport"
                                   target="_blank">
                                    {{trans('Print')}}

                                </a>
                            </div>
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{ route('store-purchase-return.index') }}"
                                   class="btn btn-secondary btn-block"> @lang('storeItem.back_button') </a>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
            @include('store.search.search_item')
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script type="text/javascript">
                @if(session('company'))
        var company_id =
                {{ request()->company_id ? request()->company_id : session('company')['company_id'] }}
                @else
        var company_id ={{ request()->company_id ? request()->company_id : auth()->user()->company_id }}
        @endif
    </script>

    <script type="text/javascript">

        $(document).ready(function () {
            $('#vou_discount_rate').keyup(function () {
                // alert($('#vou_discount_rate').val())
                var vat_ratio = $('#vou_discount_rate').val();
                var total_sum_div = $('#total_sum_div').text();
                $('#total_discount_div').val(vat_ratio)

                $('#total_sum_vat_div').text(0.15 * (total_sum_div - $('#total_discount_div').val()))

                var tt = parseFloat($('#total_sum_vat_div').text()) + parseFloat(total_sum_div) - parseFloat($('#total_discount_div').val())
                $('#total_sum_net_div').text(tt.toFixed(2))
            })

            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (day < 10 ? '0' : '') + day + '/' +
                (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
            ;
            $('#date').val(output)


            $('#value').change(function () {
                console.log($('#deserved_value').val())
                console.log($('#value').val())

                if (parseInt($('#deserved_value').val()) < parseInt($('#value').val())) {
                    console.log('a')
                    $('#submit_button').attr("disabled", "disabled").button('refresh');
                    $('#error_message').text('المسدد اكبر من المستحق')
                } else {
                    console.log('b')
                    $('#submit_button').removeAttr("disabled").button('refresh');
                    $('#error_message').text('')
                }
            })

            vat_rate = 15 / 100;
            $('#item_id').on('change', function () {
                item = $('#item_id :selected').data('item');
                $('#store_vou_item_code').val($('#item_id :selected').data('itemname'));
                $('#item_balance').val($('#item_id :selected').data('balance'));
                $('#store_vou_item_price_unit').val(item.item_price_cost);
                $('#item_price_cost').val(item.item_price_cost);
                $('#last_price_cost').val(item.last_price_cost);
            });

            $('#store_vou_qnt_r').change(function () {
                if ($('#item_id').val() == '') {
                    $('#store_vou_qnt_r').val(0)
                    return toastr.warning('لابد من اختيار الصنف');
                }
                $('#store_vou_item_total_price').val($('#store_vou_qnt_r').val() * $('#store_vou_item_price_unit').val());
                $('#store_vou_vat_amount').val($('#store_vou_item_total_price').val() * vat_rate);
                $('#store_vou_price_net').val(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val()));
            });

            $('#store_vou_item_price_unit').change(function () {
                if ($('#item_id').val() == '') {
                    $('#store_vou_qnt_r').val(0)
                    return toastr.warning('لابد من اختيار الصنف');
                }
                if (!$('#store_vou_qnt_r').val()) {
                    $('#store_vou_qnt_r').val(0)
                    return toastr.warning('لابد من ادخال الكمية');
                }
                $('#store_vou_item_total_price').val($('#store_vou_qnt_r').val() * $('#store_vou_item_price_unit').val());
                $('#store_vou_vat_amount').val($('#store_vou_item_total_price').val() * vat_rate);
                $('#store_vou_price_net').val(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val()));
            });
        });

        function checkItemInput() {

            if (
                $('#item_id').val() == '' ||
                $('#store_vou_item_code').val() == '' ||
                $('#store_vou_qnt_r').val() == '' ||

                $('#store_vou_item_price_unit').val() == '' ||
                $('#store_vou_item_total_price').val() == '' ||
                $('#invoice_date_external').val() == '') {
                return false;
            }

            return true;

        }

        function saveItemRow(el) {
            if (el == 1) {
                if (!checkItemInput()) {
                    return toastr.warning('لا يوجد بيانات لاضافتها');
                }
                tableBody = $("#item_table");
                rowNo = parseFloat($('#item_row_count').val()) + 1; //// ;

                //return  alert(rowNo);
                row_data = {
                    'id': 'tr' + rowNo,
                    'count': rowNo,
                    'store_hd_id': $('#store_hd_id').val(),
                    'store_vou_item_id': parseFloat($('#item_id').val()),
                    'store_vou_item_code': $('#item_id :selected').data('item').item_code,
                    'store_vou_loc': $('#item_id :selected').data('item').item_location,
                    'store_vou_qnt_r': parseFloat($('#store_vou_qnt_r').val()),
                    'store_vou_item_price_cost': parseFloat($('#last_price_cost').val()),
                    'store_vou_item_price_unit': parseFloat($('#store_vou_item_price_unit').val()),
                    'store_vou_item_amount': parseFloat($('#store_vou_item_amount').val()),
                    'item_balance': parseFloat($('#item_balance').val()),
                    'store_vou_item_total_price': parseFloat($('#store_vou_item_total_price').val()),
                    'last_price_cost': parseFloat($('#last_price_cost').val()),
                    'store_vou_vat_rate': (15 / 100),
                    'store_vou_vat_amount': parseFloat($('#store_vou_vat_amount').val()),
                    'store_vou_price_net': parseFloat($('#store_vou_price_net').val()),
                };

                url = '{{ route('store-item-purchase-order.store') }}';
                var form = new FormData($('#item_data_form')[0]);
                form.append('item_table_data', JSON.stringify(row_data));
                form.append('item_type', 'order');

                var data = form;
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,

                }).done(function (data) {
                    if (data.success) {
                        toastr.success(data.msg);
                        updateItemTable(rowNo, row_data, data.uuid, data.total)
                    }
                    else {
                        toastr.warning(data.msg);
                    }
                });
            }
            if (el == 2) {

                row_data = {
                    'store_vou_vat_rate': (15 / 100),
                    'store_vou_vat_amount': parseFloat($('#total_sum_vat_div').text()),
                    'store_vou_price_net': parseFloat($('#total_sum_net_div').text()),
                    'store_vou_desc': parseFloat($('#total_discount_div').val())
                };

                url = '{{ route('store-item-purchase-new-receiving.update') }}';

                var form = new FormData($('#item_data_form')[0]);

                form.append('item_table_data', JSON.stringify(row_data));

                var data = form;
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,

                }).done(function (data) {
                    // console.log(data)
                    window.open(data, "_self")
                    // if (data.success) {
                    //     toastr.success(data.msg);
                    //     updateItemTable(rowNo, row_data, data.uuid, data.total);
                    // }
                    // else {
                    //     toastr.warning(data.msg);
                    // }

                });

            }
        }

        function updateItemTable(rowNo, rowData, uuid, total) {
            uuid = "'" + uuid + "'";
            markup =
                '<tr id=' + uuid + '>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'count">' + rowData['count'] + '</td>' +
                '<td  class="ctd " id="' + 'tr' + rowNo + 'store_vou_item_code">' + rowData['store_vou_item_code'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_item_code">' + rowData['store_vou_item_code'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_qnt_r">' + rowData['store_vou_qnt_r'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_item_price">' + rowData['store_vou_item_price_unit'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_item_total_price">' + rowData['store_vou_item_total_price'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_vat_amount">' + rowData['store_vou_vat_amount'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_price_net">' + rowData['store_vou_price_net'] + '</td>' +
                '<td  class="ctd"> <button type="button" id="btnSave[]" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only" onclick="deleteItem(' + uuid + ')"><i class="fa fa-trash"></i></button></td>' +
                '<tr>' +
                '<div id="new_row"></div>';
            tableBody.append(markup);
            updateTotal(total)
        }

        function deleteItem(uuid) {
            var form = new FormData($('#item_data_form')[0]);
            form.append('uuid', uuid);

            $.ajax({

                type: 'POST',
                url: "{{route('store-purchase.delete')}}",
                data: form,
                cache: false,
                contentType: false,
                processData: false,


            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    console.log('return true');
                    console.log(data.data.uuid);
                    $('#' + data.data.uuid).remove();
                    updateTotal(data.total);
                    return 'true';
                }
                else {
                    toastr.warning(data.msg);
                    console.log('return dalse');
                    return 'false';
                }
            });


        }

        function updateTotal(total) {
            $('#total_sum_div').text(total['total_sum']);
            $('#total_sum').val(total['total_sum']);

            $('#total_sum_net_div').text(total['total_sum_net']);
            $('#total_sum_net').val(total['total_sum_net']);

            $('#total_sum_vat_div').text(total['total_sum_vat']);
            $('#total_sum_vat').val(total['total_sum_vat']);
        }

        function getSearchResult() {
            url = '{{ route('store-item.search') }}'
            $('#searchData').html('');
            var form = $('#search_data_form').serialize();

            $.ajax({
                type: 'GET',
                url: url,
                data: form,
                dataType: 'json',

            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    $('#searchData').html(data.view);

                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                payment_method_code: '',
                process_number_valid: true,
                bank_valid: true,
                bank: '',
                process_number: '',
            },
            methods: {
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
            },
        })
    </script>
@endsection


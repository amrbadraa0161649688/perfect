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
                                                   id="store_hd_id" value="{{ $sales->store_hd_id }}">
                                            <input type="hidden" class="form-control" name="sales_uuid" id="sales_uuid"
                                                   value="{{ $sales->uuid }}">
                                            <input type="hidden" class="form-control" name="header_page"
                                                   id="header_page" value="return">
                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label "> رقم مرتجع
                                                    مورد </label>
                                                <input type="text" class="form-control" name="store_hd_code"
                                                       id="store_hd_code" value="{{ $sales->store_hd_code }}" readonly
                                                       disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('sales_car.item_category') </label>
                                                <select class="form-select form-control" name="store_category_type"
                                                        id="store_category_type" disabled>
                                                    <option value="" selected> choose</option>
                                                    @foreach($warehouses_type_list as $w_t)
                                                        <option value="{{$w_t->system_code}}" {{($sales->store_category_type == $w_t->system_code_id? 'selected': '' )}}> {{ $w_t->getSysCodeName() }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label">  @lang('sales_car.vendor') </label>
                                                <select class="form-select form-control" name="store_acc_no"
                                                        id="store_acc_no" required>
                                                    <option value="" selected> choose</option>
                                                    @foreach($vendor_list as $vendor)
                                                        <option value="{{$vendor->customer_id}}"
                                                                data-vendorname="{{ $vendor->getCustomerName() }}"
                                                                data-vendorvat="{{ $vendor->customer_vat_no }}" {{($sales->store_acc_no == $vendor->customer_id ? 'selected': '' )}}> {{ $vendor->getCustomerName() }}</option>
                                                    @endforeach
                                                </select>
                                            <!-- <input type="text" class="form-control" name="store_acc_no_text" id="store_acc_no_text" value="{{$sales->vendorBy($page)->first()->getCustomerName()}}" readonly>
                                            
                                            <input type="text" class="form-control" name="store_acc_no" id="store_acc_no" value="{{$sales->vendorBy($page)->first()->getCustomerName()}}" readonly> -->

                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label "> تاريخ
                                                    مرتجع </label>
                                                <input type="text" class="form-control" name="created_date"
                                                       id="created_date"
                                                       value="{{ $sales->created_date->format('Y-m-d H:m') }}" readonly
                                                       disabled>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('sales_car.vendor_name') </label>
                                                <input type="text" class="form-control" name="store_acc_name"
                                                       id="store_acc_name" value="{{$sales->store_acc_name}}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('sales_car.vat_no') </label>
                                                <input type="number" class="form-control" name="store_acc_tax_no"
                                                       id="store_acc_tax_no" value="{{$sales->store_acc_tax_no}}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('sales_car.payment_method')  </label>
                                                <select class="form-select form-control" name="store_vou_pay_type"
                                                        id="store_vou_pay_type" required>
                                                    <option value="" selected> choose</option>
                                                    @foreach($payemnt_method_list as $p_method)
                                                        <option value="{{$p_method->system_code}}" {{($sales->store_vou_pay_type == $p_method->system_code_id? 'selected': '' )}}> {{ $p_method->getSysCodeName() }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label">@lang('sales_car.note') </label>
                                                <textarea rows="2" class="form-control" name="store_vou_notes"
                                                          id="store_vou_notes" placeholder="Here can be your note"
                                                          value=""> {{$sales->store_vou_notes }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <br>
                                                <br>
                                                <button type="button" onclick="updateHeader()"
                                                        class="btn btn-primary btn-block">
                                                    <i class="fe fe-save mr-2"></i> تحديث
                                                </button>
                                            </div>
                                            @if($sales->store_vou_payment != $sales->store_vou_total)
                                                <div class="col-md-2">
                                                    <br>
                                                    <br>
                                                    <button type="button" class="btn btn-primary btn-lg"
                                                            data-toggle="modal"
                                                            data-target="#exampleModal{{$sales->uuid}}"
                                                            data-whatever="@mdo">
                                                        @lang('home.add_bond')
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @if($sales->storeVouType->system_code == 104007)
                            {{-- bond modal --}}
                            <div class="modal fade" id="exampleModal{{$sales->uuid}}" tabindex="-1" role="dialog"
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
                                            <form action="{{ route('sales-car-inv.addBondWithJournal2') }}"
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
                                                            <input type="text" class="form-control date"
                                                                   disabled="">
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
                                                           value="{{$sales->store_hd_id}}">
                                                    {{--النشاط--}}
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">@lang('home.bonds_activity')</label>

                                                            <input type="text" disabled="" value="مرتجع مورد"
                                                                   class="form-control">
                                                            {{--///////////// اذن مرتجع سيارات مورد استيراد اذن استلام--}}
                                                            <input type="hidden" name="transaction_type" value="84">

                                                        </div>
                                                    </div>

                                                    {{--الرقم المرجعي--}}
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">@lang('home.reference_number')</label>

                                                            <input type="text" class="form-control"
                                                                   name="bond_ref_no"
                                                                   value="{{ $sales->store_hd_code }}" readonly>
                                                        </div>
                                                    </div>

                                                    {{--القيمه المستحقه--}}
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">@lang('home.deserved_value')</label>
                                                            <input type="text" class="form-control" readonly
                                                                   id="deserved_value"
                                                                   value="{{ $sales->store_vou_total - $sales->store_vou_payment }}">

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
                                                            @if($sales->storeVouType->system_code == 104007)
                                                                @if($sales->vendor)
                                                                    <input type="text" readonly class="form-control"
                                                                           value="{{app()->getLocale()=='ar' ? $sales->vendor->customer_name_full_ar :
                                                              $sales->vendor->customer_name_full_en  }}">
                                                                    <input type="hidden" name="customer_id"
                                                                           value="{{ $sales->vendor->customer_id }}">
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{--{قم الحساب للمورد--}}
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">@lang('home.account_code')</label>
                                                            @if($sales->storeVouType->system_code == 104007)
                                                                @if( $sales->vendor)
                                                                    <input type="hidden" class="form-control"
                                                                           name="bond_acc_id"
                                                                           value="{{ $sales->vendor->customer_account_id}}">
                                                                    <input type="text" readonly class="form-control"
                                                                           value="{{app()->getLocale() == 'ar' ?
                                                               $sales->vendor->account->acc_name_ar :
                                                               $sales->vendor->account->acc_name_en}} . {{ $sales->vendor->account->acc_code }}">
                                                                @endif
                                                            @endif

                                                        </div>
                                                    </div>

                                                    {{--انواع الايرادات--}}
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">@lang('home.revenue_types')</label>
                                                            <select class="form-control" data-live-search="true"
                                                                    name="bond_doc_type" required>
                                                                <option value="">@lang('home.choose')</option>
                                                                @foreach(App\Models\SystemCode::where('sys_category_id', 58)
                                                ->where('company_group_id', $sales->company_group_id)->get() as $system_code)
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
                                                                    onchange="validInputs($(this))"
                                                                    name="bond_method_type"
                                                                    required>
                                                                <ooption value="">@lang('home.choose')</ooption>
                                                                @foreach(App\Models\SystemCode::where('sys_category_id', 57)
                                        ->where('company_group_id', $sales->company_group_id)->get() as $payment_method)
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
                                                                   name="process_number" disabled="">
                                                        </div>
                                                    </div>

                                                    {{--البنك--}}
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">@lang('home.bank')</label>
                                                            <select class="form-control" name="bond_bank_id"
                                                                    disabled="">
                                                                <option value="">@lang('home.choose')</option>
                                                                @foreach(App\Models\SystemCode::where('sys_category_id', 40)
                                        ->where('company_group_id', $sales->company_group_id)->get() as $bank)
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
                                                                   onkeyup="validPaid($(this))"
                                                                   value="{{$sales->store_vou_total - $sales->store_vou_payment}}"
                                                                   name="bond_amount_credit" required>
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
                        @endif

                        @if($sales->storeVouType->system_code == 104006)
                            {{-- bond modal --}}
                            <div class="modal fade" id="exampleModal{{$sales->uuid}}" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"
                                                id="exampleModalLabel">@lang('home.add_cash_bond')</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <form action="{{ route('store-sales-car-receiving-bondJournal') }}"
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
                                                            <input type="text" class="form-control date"
                                                                   disabled="">
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
                                                           value="{{$sales->store_hd_id}}">

                                                    {{--النشاط--}}
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">@lang('home.bonds_activity')</label>

                                                            <input type="text" disabled="" value="مرتجع عميل"
                                                                   class="form-control">
                                                            {{--مرتجع عميل استيراد فاتوره المبيعات--}}
                                                            <input type="hidden" name="transaction_type" value="84">

                                                        </div>
                                                    </div>

                                                    {{--الرقم المرجعي--}}
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">@lang('home.reference_number')</label>

                                                            <input type="text" class="form-control"
                                                                   name="bond_ref_no"
                                                                   value="{{ $sales->store_hd_code }}" readonly>
                                                        </div>
                                                    </div>

                                                    {{--القيمه المستحقه--}}
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">@lang('home.deserved_value')</label>
                                                            <input type="text" class="form-control" readonly
                                                                   id="deserved_value"
                                                                   value="{{ $sales->store_vou_total - $sales->store_vou_payment }}">

                                                        </div>
                                                    </div>

                                                    {{--نوع الحساب--}}
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">@lang('home.account_type')</label>

                                                            <input type="text" class="form-control" readonly
                                                                   value="{{app()->getLocale()=='ar' ? \App\Models\SystemCode::where('system_code',56002)
                                                   ->first()->system_code_name_ar : \App\Models\SystemCode::where('system_code',56002)
                                                   ->first()->system_code_name_en}}">
                                                            <input type="hidden" value="{{ \App\Models\SystemCode::where('system_code',56002)
                                            ->first()->system_code_id}}" name="account_type">
                                                        </div>
                                                    </div>

                                                    {{--نوع العميل--}}
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="form-group">
                                                            <input type="hidden" name="customer_type" value="customer">

                                                            <label class="form-label">@lang('home.customer')</label>
                                                            @if( $sales->customer)
                                                                <input type="text" readonly class="form-control"
                                                                       value="{{app()->getLocale()=='ar' ? $sales->customer->customer_name_full_ar :
                                                              $sales->customer->customer_name_full_en  }}">
                                                                <input type="hidden" name="customer_id"
                                                                       value="{{ $sales->customer->customer_id }}">
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- قم الحساب للعميل--}}
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">@lang('home.account_code')</label>
                                                            @if($sales->customer)
                                                                <input type="hidden" class="form-control"
                                                                       name="bond_acc_id"
                                                                       value="{{ $sales->customer->customer_account_id}}">
                                                                <input type="text" readonly class="form-control"
                                                                       value="{{app()->getLocale() == 'ar' ?
                                                               $sales->customer->account->acc_name_ar :
                                                               $sales->customer->account->acc_name_en}} . {{ $sales->customer->account->acc_code }}">
                                                            @endif

                                                        </div>
                                                    </div>

                                                    {{--انواع المصروفات--}}
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">@lang('home.cash_types')</label>
                                                            @if(app()->getLocale()=='ar')
                                                                <input type="text" class="form-control" readonly value="{{\App\Models\SystemCode::where('system_code',590004)
                                                            ->where('company_group_id',$sales->company_group_id)->first()->system_code_name_ar}}">
                                                            @else
                                                                <input type="text" class="form-control" readonly value="{{\App\Models\SystemCode::where('system_code',590004)
                                                            ->where('company_group_id',$sales->company_group_id)->first()->system_code_name_en}}">
                                                            @endif

                                                            <input type="hidden" value="{{\App\Models\SystemCode::where('system_code',590004)
                                                            ->where('company_group_id',$sales->company_group_id)->first()->system_code_id}}"
                                                                   name="bond_doc_type">

                                                        </div>
                                                    </div>

                                                    {{--طرق الدفع--}}
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">@lang('home.payment_method')</label>
                                                            <select class="form-control"
                                                                    onchange="validInputs($(this))"
                                                                    name="bond_method_type"
                                                                    required>
                                                                <ooption value="">@lang('home.choose')</ooption>
                                                                @foreach(App\Models\SystemCode::where('sys_category_id', 57)
                                        ->where('company_group_id', $sales->company_group_id)->get() as $payment_method)
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
                                                                   name="process_number" disabled="">
                                                        </div>
                                                    </div>

                                                    {{--البنك--}}
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">@lang('home.bank')</label>
                                                            <select class="form-control" name="bond_bank_id"
                                                                    disabled="">
                                                                <option value="">@lang('home.choose')</option>
                                                                @foreach(App\Models\SystemCode::where('sys_category_id', 40)
                                        ->where('company_group_id', $sales->company_group_id)->get() as $bank)
                                                                    <option value="{{ $bank->system_code_id }}">
                                                                        {{ app()->getLocale()=='ar' ? $bank->system_code_name_ar :
                                                                         $bank->system_code_name_en }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>


                                                    {{--ااجمالي شامل الضريبه--}}
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">@lang('home.total_value')</label>
                                                            <input type="text" class="form-control"
                                                                   value="{{$sales->store_vou_total - $sales->store_vou_payment }}"
                                                                   name="bond_amount_total" required
                                                                   onkeyup="validPaid($(this),'{{$sales->store_hd_id}}')">
                                                        </div>
                                                    </div>

                                                    <input type="hidden" step=".0001" class="form-control"
                                                           name="bond_vat_rate" value="0">

                                                    <input type="hidden" class="form-control"
                                                           value="0"
                                                           name="bond_vat_amount" required>


                                                    <div class="col-sm-6 col-md-6">
                                                        <label class="form-label">@lang('home.notes')</label>
                                                        <textarea class="form-control" name="bond_notes"
                                                                  placeholder="@lang('home.notes')"></textarea>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary btn-sm"
                                                                id="submit_button{{$sales->store_hd_id}}">
                                                            @lang('home.add_bond')
                                                        </button>
                                                    </div>


                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif


                        <div class="row card">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#search_item_modal">
                                <i class="fe fe-search mr-2"></i> عرض الاصناف
                            </button>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <br>
                                    <div class="table-responsive">
                                        @include('salesCar.return.table.item_table')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer row">
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{config('app.telerik_server')}}?rpt={{$sales->report_url_ins_r->report_url}}&id={{$sales->uuid}}&lang=ar&skinName=bootstrap"
                                   title="{{trans('Print')}}" class="btn btn-warning btn-block" id="showReport"
                                   target="_blank">
                                    {{trans('Print')}}

                                </a>
                            </div>
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{ route('sales-car-return.index') }}"
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

        var d = new Date();

        var month = d.getMonth() + 1;
        var day = d.getDate();

        var output = (day < 10 ? '0' : '') + day + '/' +
            (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
        ;
        $('.date').val(output)


        $(document).ready(function () {
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


            $('#store_acc_no').on('change', function () {
                $('#store_acc_name').val($('#store_acc_no :selected').data('vendorname'));
                $('#store_acc_name').removeClass('is-invalid');
                $('#store_acc_tax_no').val($('#store_acc_no :selected').data('vendorvat'));
                $('#store_acc_tax_no').removeClass('is-invalid');
            });

            $('#store_acc_no').change(function () {
                if (!$('#store_acc_no').val()) {
                    $('#store_acc_no').addClass('is-invalid');
                    //$('.car').addClass("is-invalid");
                } else {
                    $('#store_acc_no').removeClass('is-invalid');
                    //$('.car').removeClass("is-invalid");
                }
            });

            $('#store_vou_pay_type').change(function () {
                if (!$('#store_vou_pay_type').val()) {
                    $('#store_vou_pay_type').addClass('is-invalid');
                    //$('.car').addClass("is-invalid");
                } else {
                    $('#store_vou_pay_type').removeClass('is-invalid');
                    //$('.car').removeClass("is-invalid");
                }
            });

            $('#store_acc_name').keyup(function () {
                if ($('#store_acc_name').val().length < 3) {
                    $('#store_acc_name').addClass('is-invalid')
                } else {
                    $('#store_acc_name').removeClass('is-invalid');
                }
            });

            $('#store_acc_tax_no').keyup(function () {
                if ($('#store_acc_tax_no').val().length < 3) {
                    $('#store_acc_tax_no').addClass('is-invalid')
                } else {
                    $('#store_acc_tax_no').removeClass('is-invalid');
                }
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

        function saveItemRow() {
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

        function updateHeader() {
            url = '{{ route('car-sales.header.update') }}'
            var form = new FormData($('#item_data_form')[0]);
            //var form  = $('#search_data_form').serialize() ;

            $.ajax({
                type: 'POST',
                url: url,
                data: form,
                cache: false,
                contentType: false,
                processData: false,

            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);

                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }
    </script>
@endsection


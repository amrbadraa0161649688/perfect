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
                <ul class="nav nav-tabs page-header-tab">

                    <li class="nav-item">
                        <a href="#edit-grid" data-toggle="tab"
                           class="nav-link">@lang('home.edit')</a>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="#bonds-cash-grid"
                                            data-toggle="tab">@lang('home.bonds_cash')</a></li>

                    <li class="nav-item"><a class="nav-link" href="#bonds-capture-grid"
                                            data-toggle="tab">@lang('home.bonds_capture')</a></li>

                    <li class="nav-item"><a class="nav-link" href="#files-grid"
                                            data-toggle="tab">@lang('home.files')</a></li>

                    <li class="nav-item"><a class="nav-link" href="#notes-grid"
                                            data-toggle="tab">@lang('home.notes')</a></li>

                    <li class="nav-item"><a class="nav-link" href="#journals-grid"
                                            data-toggle="tab">@lang('home.journals')</a></li>

                </ul>
                <div class="header-action"></div>
            </div>
        </div>
    </div>


    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">

                <div class="tab-pane fade show active" id="edit-grid" role="tabpanel">
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

                                            <input type="hidden" class="form-control" name="customer_addition_rate"
                                                   id="customer_addition_rate"
                                                   value="{{ $purchase->customer->customer_vat_rate }}">
                                            <input type="hidden" class="form-control" name="customer_discount_rate"
                                                   id="customer_discount_rate"
                                                   value="{{ $purchase->customer->customer_discount_rate }}">
                                                   <input type="hidden" class="form-control" name="customer_vat_rate"
                                                   id="customer_vat_rate"
                                                   value="{{ $purchase->customer->customer_vat_rate }}">
                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label "> رقم فاتورة
                                                    المبيعات </label>
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
                                                @if($purchase->mntsCard)
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('sales.sales_inv_mntns_no') </label>
                                                    <input type="text" class="form-control" disabled=""
                                                           value="{{ $purchase->mntsCard->mntns_cards_no }}">

                                                @elseif(!$purchase->mntsCard && $purchase->mntsCar)
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('sales.sales_inv_car_no') </label>
                                                    <input type="text" class="form-control" disabled=""
                                                           value="{{ $purchase->mntsCar->mntns_cars_plate_no . ' '.$purchase->mntsCar->brand->system_code_name_ar}}">
                                                @else
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('sales.customer_name')  </label>
                                                    <select class="form-select form-control" name="store_acc_no"
                                                            id="store_acc_no" required disabled>
                                                        <option value="" selected> choose</option>
                                                        @foreach($customer as $vendor)
                                                            <option value="{{$vendor->customer_id}}"
                                                                    data-vendorname="{{ $vendor->getCustomerName() }}"
                                                                    data-vendorvat="{{ $vendor->customer_vat_no }}" {{($purchase->store_acc_no == $vendor->customer_id ? 'selected': '' )}}> {{ $vendor->getCustomerName() }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label "> تاريخ فاتورة
                                                    المبيعات </label>
                                                <input type="date" class="form-control" name="created_date"
                                                       id="created_date" required
                                                       value="{{ $purchase->created_date->format('Y-m-d') }}">
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('sales.customer_name')  </label>
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
                                                       id="vou_discount_rate"
                                                       @if($purchase->store_vou_desc > 0) readonly @endif
                                                       value="{{$purchase->store_vou_desc}}">
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
                                                        class="btn btn-primary btn-block"
                                                        @if($purchase->store_vou_total != 0 &&
                                                        $purchase->total_bonds_inv  == $purchase->store_vou_total)
                                                        disabled @endif
                                                        @if($purchase->store_vou_desc > 0) disabled @endif>
                                                    <i class="fe fe-save mr-2"></i> تحديث
                                                </button>
                                            </div>

                                            <div class="col-md-2">
                                                <br>
                                                <br>

                                                @if($purchase->store_vou_status == App\Models\SystemCode::where('company_group_id',
                                                    $company->company_group_id)->where('system_code', '=', '125001')->first()->system_code_id)
                                                    <button type="button"
                                                            onclick="genInvoice('{{$purchase->uuid}}')"
                                                            class="btn btn-primary btn-lg" id="export_invoice"
                                                            style="margin-bottom: 10px;margin-right: 10px">
                                                        اصدار فاتورة
                                                    </button>
                                                @endif

                                            </div>

                                            @if($purchase->store_vou_payment != $purchase->store_vou_total)
                                                <div class="col-md-4">
                                                    <br>
                                                    <br>
                                                    <button type="button" class="btn btn-primary btn-lg"
                                                            data-toggle="modal"
                                                            data-target="#exampleModal" data-whatever="@mdo"
                                                            @if($purchase->status->system_code != 125002) disabled @endif>
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
                                        <form action="{{ route('store-sales-inv.addBondWithJournal2') }}"
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
                                                        <input type="date" id="date" class="form-control"
                                                               name="bond_date"
                                                               value="{{ $purchase->created_date->format('Y-m-d') }}"
                                                               required>
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

                                                        <input type="text" disabled="" value="فاتوره بيع"
                                                               class="form-control">
                                                        <input type="hidden" name="transaction_type" value="65">

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
                                                               value="{{ $purchase->store_vou_total - $purchase->store_vou_payment }}">

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

                                                        <input type="text" readonly class="form-control"
                                                               value="{{app()->getLocale()=='ar' ? $purchase->customer->customer_name_full_ar :
                                                              $purchase->customer->customer_name_full_en  }}">
                                                        <input type="hidden" name="customer_id"
                                                               value="{{ $purchase->customer->customer_id }}">
                                                    </div>
                                                </div>

                                                {{-- قم الحساب للعميل--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.account_code')</label>
                                                        <input type="hidden" class="form-control"
                                                               name="bond_acc_id"
                                                               value="{{ $purchase->customer->customer_account_id}}">
                                                        <input type="text" readonly class="form-control"
                                                               value="{{app()->getLocale() == 'ar' ?
                                                               $purchase->customer->account->acc_name_ar :
                                                               $purchase->customer->account->acc_name_en}} . {{ $purchase->customer->account->acc_code }}">

                                                    </div>
                                                </div>

                                                {{--انواع الايرادات--}}
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.revenue_types')</label>
                                                        {{--<select class="selectpicker" data-live-search="true"--}}
                                                        {{--name="bond_doc_type" required>--}}
                                                        {{--<option value="">@lang('home.choose')</option>--}}
                                                        {{--@foreach($system_code_types as $system_code)--}}
                                                        {{--<option value="{{$system_code->system_code_id}}"--}}
                                                        {{--@if($system_code->system_code == 580003) selected @endif>--}}
                                                        {{--{{ app()->getLocale()=='ar' ?--}}
                                                        {{--$system_code->system_code_name_ar :     $system_code->system_code_name_en }}--}}
                                                        {{--</option>--}}
                                                        {{--@endforeach--}}
                                                        {{--</select>--}}

                                                        @if(app()->getLocale() == 'ar')
                                                            <input type="text" class="form-control" readonly value="{{App\Models\SystemCode::where('company_group_id',
                                $purchase->company_group_id)->where('system_code',580003)->first()->system_code_name_ar}}">
                                                        @else
                                                            <input type="text" class="form-control" readonly value="{{App\Models\SystemCode::where('company_group_id',
                                $purchase->company_group_id)->where('system_code',580003)->first()->system_code_name_en}}">
                                                        @endif

                                                        <input type="hidden" name="bond_doc_type" value="{{App\Models\SystemCode::where('company_group_id',
                                $purchase->company_group_id)->where('system_code',580003)->first()->system_code_id}}">
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
                                                               value="{{$purchase->store_vou_total - $purchase->store_vou_payment }}"
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
                                        @if($purchase->status->system_code != 125002)
                                            @include('store.sales.inv.table.item_new_table')
                                        @else
                                            @include('store.sales.inv.table.item_table')
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer row">
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                @if($purchase->status->system_code != 125001)
                                    <a href="{{config('app.telerik_server')}}?rpt={{$purchase->report_url_inv->report_url}}&id={{$purchase->uuid}}&lang=ar&skinName=bootstrap"
                                       title="{{trans('Print')}}" class="btn btn-warning btn-block" id="showReport"
                                       target="_blank">
                                        {{trans('Print')}}

                                    </a>
                                @endif
                            </div>
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{ route('store-sales-inv.index') }}"
                                   class="btn btn-secondary btn-block"> @lang('storeItem.back_button') </a>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>

                <div class="tab-pane fade" id="bonds-cash-grid" role="tabpanel">

                    <div class="card-title">
                        <a href="{{ url('bonds-add/cash/create?store_sales_inv_id='.$purchase->store_hd_id) }}"
                           class="btn btn-primary text-white" target="_blank">@lang('home.add_bond')</a>
                    </div>

                    <div class="card-body">
                        <div class="row card">
                            <div class="table-responsive table_e2">
                                <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>@lang('home.bonds_number')</th>
                                        <th>@lang('home.bonds_date')</th>
                                        <th>@lang('home.sub_company')</th>
                                        <th>@lang('home.branch')</th>
                                        <th>@lang('home.bonds_account')</th>
                                        <th>@lang('home.payment_method')</th>
                                        <th>@lang('home.value')</th>
                                        <th>@lang('home.user')</th>
                                        <th>@lang('home.journal')</th>
                                        <th></th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($bonds_cash as $bond)
                                        <tr>
                                            <td>{{ $bond->bond_code }}</td>
                                            <td>{{ $bond->created_date }}</td>
                                            <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->company->company_name_ar :
                                            $bond->company->company_name_en }}</td>

                                            <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->branch->branch_name_ar :
                                            $bond->branch->branch_name_en }}</td>
                                            <td>{{ $bond->bond_acc_id }}</td>
                                            <td>{{ app()->getLocale() == 'ar' ? $bond->paymentMethod->system_code_name_ar :
                                              $bond->paymentMethod->system_code_name_en }}</td>
                                            <td>{{ $bond->bond_amount_credit }}</td>
                                            <td>{{ app()->getLocale()=='ar' ? $bond->userCreated->user_name_ar :
                                            $bond->userCreated->user_name_en }}</td>
                                            <td>
                                                @if($bond->journalCash)

                                                    <a href="{{route('journal-entries.show',$bond->journalCash->journal_hd_id)}}"
                                                       class="btn btn-primary btn-block">{{$bond->journalCash->journal_hd_code}}</a>

                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{config('app.telerik_server')}}?rpt={{$bond->report_url_payment->report_url}}&id={{$bond->bond_id}}&lang=ar&skinName=bootstrap"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.show')"><i
                                                            class="fa fa-print"></i></a>
                                                <a href="{{ route('Bonds-cash.show',$bond->bond_id) }}"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.show')"><i
                                                            class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="tab-pane fade" id="bonds-capture-grid" role="tabpanel">
                    <div class="card-body">
                        <form action="{{route('store-sales-inv.storeBond')}}" method="post">
                            @csrf
                            <div class="row mb-2">

                                <input type="hidden" name="bond_id" :value="bond.bond_id"
                                       v-if="Object.keys(bond).length > 0">
                                <input type="hidden" name="purchase_id" value="{{$purchase->store_hd_id}}">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="bond_code_capture"
                                           placeholder="الكود.........." v-model="bond_code_capture"
                                           @change="getBond()">
                                    <small v-if="bond_message" class="text-danger">@{{ bond_message }}</small>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary"
                                            :disabled="bond_disabled">@lang('home.save')</button>
                                </div>


                            </div>
                        </form>
                        <div class="row card">

                            <div class="table-responsive table_e2">
                                <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>@lang('home.bonds_number')</th>
                                        <th>@lang('home.bonds_date')</th>
                                        <th>@lang('home.sub_company')</th>
                                        <th>@lang('home.branch')</th>
                                        <th>@lang('home.bonds_account')</th>
                                        <th>@lang('home.payment_method')</th>
                                        <th>@lang('home.value')</th>
                                        <th>@lang('home.user')</th>
                                        <th>@lang('home.journal')</th>
                                        <th></th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="Object.keys(bond).length > 0">
                                        <td>@{{ bond.bond_code }}</td>
                                        <td>@{{ bond.created_date }}</td>
                                        <td>@if(app()->getLocale() == 'ar')
                                                @{{ bond.company.company_name_ar }}
                                            @else
                                                @{{ bond.company.company_name_en }}
                                            @endif
                                        </td>
                                        <td>@if(app()->getLocale() == 'ar')
                                                @{{ bond.branch.branch_name_ar }}
                                            @else
                                                @{{ bond.branch.branch_name_en }}
                                            @endif
                                        </td>
                                        <td>@{{ bond.account ? bond.account.account_code : '' }}</td>
                                        <td>@if(app()->getLocale() == 'ar')
                                                @{{ bond.paymentMethod.system_code_name_ar }}
                                            @else
                                                @{{ bond.paymentMethod.system_code_name_en }}
                                            @endif
                                        </td>
                                        <td>@{{ bond.bond_amount_debit }}</td>
                                        <td>
                                            @if(app()->getLocale() == 'ar')
                                                @{{ bond.user.user_name_ar }}
                                            @else
                                                @{{ bond.user.user_name_en }}
                                            @endif
                                        </td>
                                        <td>
                                            @{{ bond.journalCapture.journal_hd_code }}
                                        </td>
                                        <td></td>
                                    </tr>

                                    @foreach($bonds_capture as $bond)
                                        <tr>
                                            <td>{{ $bond->bond_code }}</td>
                                            <td>{{ $bond->created_date }}</td>
                                            <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->company->company_name_ar :
                                            $bond->company->company_name_en }}</td>

                                            <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->branch->branch_name_ar :
                                            $bond->branch->branch_name_en }}</td>
                                            <td>{{ $bond->bond_acc_id }}</td>
                                            <td>
                                                @if($bond->bond_method_type)
                                                    {{ app()->getLocale() == 'ar' ? \App\Models\SystemCode::where('system_code',$bond->bond_method_type)
                                                    ->first()->system_code_name_ar :
                                                  \App\Models\SystemCode::where('system_code',$bond->bond_method_type)
                                                    ->first()->system_code_name_en }}
                                                @endif
                                            </td>
                                            <td>{{ $bond->bond_amount_debit }}</td>
                                            <td>{{ app()->getLocale()=='ar' ? $bond->userCreated->user_name_ar :
                                            $bond->userCreated->user_name_en }}</td>
                                            <td>
                                                @if($bond->journalBondInvoiceSales)
                                                    <a href="{{ route('journal-entries.show',$bond->journalBondInvoiceSales
                                                        ->journal_hd_id) }}"
                                                       class="btn btn-primary btn-sm">
                                                        @lang('home.journal_details')
                                                        {{$bond->journalBondInvoiceSales->journal_hd_code}}
                                                    </a>
                                                @endif

                                            </td>
                                            <td>
                                                <a href="{{config('app.telerik_server')}}?rpt={{$bond->report_url_receipt->report_url}}&id={{$bond->bond_id}}&lang=ar&skinName=bootstrap"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.print')"><i
                                                            class="fa fa-print"></i></a>

                                                <a href="{{ route('Bonds-capture.show',$bond->bond_id) }}"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.show')"><i
                                                            class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- files part --}}
                <div class="tab-pane fade" id="files-grid" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-12">

                            <x-files.form>
                                <input type="hidden" name="transaction_id"
                                       value="{{ $purchase->store_hd_id }}">
                                <input type="hidden" name="app_menu_id" value="65">
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
                                        <td>
                                            @if($attachment->attachmentType_2)
                                                {{ app()->getLocale()=='ar' ?
                                         $attachment->attachmentType_2->system_code_name_ar :
                                          $attachment->attachmentType_2->system_code_name_en}}
                                            @endif
                                        </td>
                                        <td>{{ date('d-m-Y', strtotime($attachment->issue_date )) }}</td>
                                        <td>{{ date('d-m-Y', strtotime($attachment->expire_date )) }}</td>
                                        <td>{{ $attachment->issue_date_hijri }}</td>
                                        <td>{{ $attachment->expire_date_hijri }}</td>
                                        <td>{{ $attachment->copy_no }}</td>
                                        <td>
                                            <a href="{{ url('/attachments/download-pdf?name=' . $attachment->attachment_file_url) }}">
                                                <i class="fa fa-download fa-2x"></i></a>
                                            <a href="{{ asset('Files/'.$attachment->attachment_file_url) }}"
                                               target="_blank" class="mr-1 ml-1"><i class="fa fa-eye text-info"
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
                {{--end files part--}}

                {{-- notes part --}}
                <div class="tab-pane fade" id="notes-grid" role="tabpanel">

                    <div class="row">
                        <div class="col-lg-12">
                            <x-files.form-notes>
                                <input type="hidden" name="transaction_id"
                                       value="{{ $purchase->store_hd_id }}">
                                <input type="hidden" name="app_menu_id" value="65">
                            </x-files.form-notes>

                            <x-files.notes>
                                @foreach($notes as $note)
                                    <tr>
                                        <td>
                                            <div class="badge text-gray text-wrap" style="width: 100%;">
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
                {{--end notes part--}}

                <div class="tab-pane fade" id="journals-grid" role="tabpanel">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-body pt-0">
                                <div class="table-responsive table_e2">
                                    <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                        <thead>
                                        <tr>
                                            <th> @lang('home.restriction_number')  </th>
                                            <th> @lang('home.date')  </th>
                                            <th style="width:150px">@lang('home.notes') </th>
                                            <th>@lang('home.debit') </th>
                                            <th>@lang('home.credit') </th>
                                            <th>@lang('home.user') </th>
                                            <th>@lang('home.restriction_account_status') </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($journals as $journal)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('journal-entries.edit',$journal[0]->journal_hd_id) }}"
                                                       class="btn btn-primary btn-sm">
                                                        {{ $journal[0]->journal_hd_code }}
                                                    </a>
                                                </td>
                                                <td>{{$journal[0]->journal_hd_date}}</td>
                                                <td>{{$journal[0]->journal_hd_notes}}</td>
                                                <td>{{$journal[0]->journal_hd_debit }}</td>
                                                <td>{{$journal[0]->journal_hd_credit }}</td>
                                                <td>
                                                    {{ app()->getLocale()=='ar' ? $journal[0]->user_name_ar :
                                                 $journal[0]->user_name_en }}
                                                </td>

                                                <td>
                                                    <span class="tag tag-success">
                                                        {{ app()->getLocale()=='ar' ? $journal[0]->system_code_name_ar :
                                                     $journal[0]->system_code_name_en }}
                                                     </span>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


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
        function getNum(number) {
            return parseFloat(number).toFixed(2);
        }

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

            $('#vou_discount_rate').keyup(function () {
                // alert($('#vou_discount_rate').val())
                var vat_ratio = $('#vou_discount_rate').val();
                var total_sum_div = $('#total_sum_div').text();
                $('#total_discount_div').val(vat_ratio)

                $('#total_sum_vat_div').text(0.15 * (total_sum_div - $('#total_discount_div').val()))
                $('#total_sum_net_div').text(getNum(parseFloat($('#total_sum_vat_div').text()) + parseFloat(total_sum_div) - parseFloat($('#total_discount_div').val())))
            });

          //  alert($('#customer_vat_rate').val())
            vat_rate = $('#customer_vat_rate').val();
            customer_addition_rate = $('#customer_addition_rate').val();
            customer_discount_rate = $('#customer_discount_rate').val();

            $('#store_vou_item_price_unit').keyup(function () {
                $('#store_vou_item_price_unit_vat').val(calculateItemPriceByAdditionRate($('#store_vou_item_price_unit').val(), customer_addition_rate))
            });


            $(function () {
                var params = new window.URLSearchParams(window.location.search);
                console.log(params.get('qr'));
                if (params.get('qr') == 'bond') {
                    $('#exampleModal').modal('show')
                }
            });


            // $('#exampleModal').modal('show')
            var d = new Date();
            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (day < 10 ? '0' : '') + day + '/' +
                (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
            ;

            $('#date').val(output)


            $('#value').keyup(function () {
                console.log($('#deserved_value').val())

                if (parseInt($('#deserved_value').val()) < parseInt($('#value').val())) {
                    $('#submit_button').attr("disabled", "disabled").button('refresh');
                    $('#error_message').text('المسدد اكبر من المستحق')
                } else {
                    $('#submit_button').removeAttr("disabled").button('refresh');
                    $('#error_message').text('')
                }
            })

            vat_rate = $('#customer_vat_rate').val();;
            $('#item_id').on('change', function () {

                $('#store_vou_qnt_o').val('');
                $('#store_vou_item_total_price').val('');
                $('#store_vou_vat_amount').val('');
                $('#store_vou_price_net').val('');
                $('#store_vou_disc_amount').val('');
                $('#item_balance').val('');
                $('#item_price_cost').val('');
                $('#last_price_cost').val('');

                if ($('#item_id').val() !== '') {
                    item = $('#item_id :selected').data('item');
                    $('#store_vou_item_code').val($('#item_id :selected').data('itemname'));
                    $('#item_balance').val($('#item_id :selected').data('balance'));
                    $('#store_vou_item_price_unit').val(getNum(calculateItemPriceByAdditionRate(item.item_price_sales, customer_addition_rate)));
                    $('#store_vou_item_price_unit_vat').val(getNum(calculateItemPriceByAdditionRate($('#store_vou_item_price_unit').val(), customer_addition_rate)));
                    $('#item_price_cost').val(item.item_price_sales);
                    $('#item_price_sales').val(item.item_price_sales);
                    $('#last_price_cost').val(item.item_price_sales);
                    $('#store_vou_qnt_o').val('');
                    $('#store_vou_item_total_price').val('');

                    $('#discount_rate').val(customer_discount_rate * 100);

                }
            });

            function calculateItemPriceByAdditionRate($price, $rate) {
                result = parseFloat($price) + (parseFloat($price) * parseFloat($rate));
                return result;
            }


            function calculateItemPriceByDiscountRate($price, $rate) {
                $result = (parseFloat($price) * parseFloat($rate));
                return $result;
            }


            $('#store_vou_qnt_r').change(function () {
                if ($('#item_id').val() == '') {
                    $('#store_vou_qnt_r').val(0)
                    return toastr.warning('لابد من اختيار الصنف');
                }
                $('#store_vou_item_total_price').val($('#store_vou_qnt_r').val() * $('#store_vou_item_price_unit').val());
                $('#store_vou_vat_amount').val($('#store_vou_item_total_price').val() * vat_rate);
                $('#store_vou_price_net').val(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val()));

                $('#store_vou_disc_amount').val(getNum($('#store_vou_qnt_o').val()) * getNum(calculateItemPriceByDiscountRate($('#store_vou_item_price_unit').val(), customer_discount_rate)));


                var total_sum_div = $('#total_sum_div').text();
                var a = parseFloat(total_sum_div) + parseFloat($('#store_vou_item_total_price').val());
                $('#total_sum_div_0').text(a);


                var total_sum_vat_div = $('#total_sum_vat_div').text();
                var b = parseFloat(total_sum_vat_div) + parseFloat($('#store_vou_vat_amount').val());
                $('#total_sum_vat_div_0').text(b);


                var total_sum_net_div = $('#total_sum_net_div').text();
                var c = parseFloat(total_sum_net_div) + parseFloat($('#store_vou_price_net').val());
                $('#total_sum_net_div_0').text(c.toFixed(2))
            });

            $('#store_vou_qnt_o').change(function () {
                if ($('#item_id').val() == '') {
                    $('#store_vou_qnt_o').val(0)
                    return toastr.warning('لابد من اختيار الصنف');
                }
                $('#store_vou_item_total_price').val(getNum($('#store_vou_qnt_o').val()) * getNum($('#store_vou_item_price_unit').val() - getNum(calculateItemPriceByDiscountRate($('#store_vou_item_price_unit').val(), customer_discount_rate))));
                $('#store_vou_vat_amount').val(getNum($('#store_vou_item_total_price').val() * vat_rate));
                $('#store_vou_price_net').val(getNum(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val())));
                $('#store_vou_disc_amount').val(getNum($('#store_vou_qnt_o').val()) * getNum(calculateItemPriceByDiscountRate($('#store_vou_item_price_unit').val(), customer_discount_rate)));


                var total_sum_div = $('#total_sum_div').text();
                var a = parseFloat(total_sum_div) + parseFloat($('#store_vou_item_total_price').val());
                $('#total_sum_div_0').text(a);


                var total_sum_vat_div = $('#total_sum_vat_div').text();
                var b = parseFloat(total_sum_vat_div) + parseFloat($('#store_vou_vat_amount').val());
                $('#total_sum_vat_div_0').text(b);


                var total_sum_net_div = $('#total_sum_net_div').text();
                var c = parseFloat(total_sum_net_div) + parseFloat($('#store_vou_price_net').val());
                $('#total_sum_net_div_0').text(c.toFixed(2))

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

                $('#store_vou_disc_amount').val(getNum($('#store_vou_qnt_o').val()) * getNum(calculateItemPriceByDiscountRate($('#store_vou_item_price_unit').val(), customer_discount_rate)));

                var total_sum_div = $('#total_sum_div').text();
                var a = parseFloat(total_sum_div) + parseFloat($('#store_vou_item_total_price').val());
                $('#total_sum_div_0').text(a.toFixed(2));


                var total_sum_vat_div = $('#total_sum_vat_div').text();
                var b = parseFloat(total_sum_vat_div) + parseFloat($('#store_vou_vat_amount').val());
                $('#total_sum_vat_div_0').text(b.toFixed(2));


                var total_sum_net_div = $('#total_sum_net_div').text();
                var c = parseFloat(total_sum_net_div) + parseFloat($('#store_vou_price_net').val());
                $('#total_sum_net_div_0').text(c.toFixed(2))
            });

            $('#store_vou_item_price_unit_vat').change(function () {

                $('#store_vou_item_price_unit').val(getNum($('#store_vou_item_price_unit_vat').val() / (1 + parseFloat(customer_addition_rate))))

                if ($('#item_id').val() == '') {
                    $('#store_vou_qnt_o').val(0)
                    return toastr.warning('لابد من اختيار الصنف');
                }
                if (!$('#store_vou_qnt_o').val()) {
                    $('#store_vou_qnt_o').val(0)
                    return toastr.warning('لابد من ادخال الكمية');
                }
                $('#store_vou_item_total_price').val(getNum($('#store_vou_qnt_o').val()) * getNum($('#store_vou_item_price_unit').val() - getNum(calculateItemPriceByDiscountRate($('#store_vou_item_price_unit').val(), customer_discount_rate))));
                $('#store_vou_vat_amount').val(getNum($('#store_vou_item_total_price').val() * vat_rate));
                $('#store_vou_price_net').val(getNum(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val())));
                $('#store_vou_disc_amount').val(getNum($('#store_vou_qnt_o').val()) * getNum(calculateItemPriceByDiscountRate($('#store_vou_item_price_unit').val(), customer_discount_rate)));

                var total_sum_div = $('#total_sum_div').text();
                var a = parseFloat(total_sum_div) + parseFloat($('#store_vou_item_total_price').val());
                $('#total_sum_div_0').text(a.toFixed(2));


                var total_sum_vat_div = $('#total_sum_vat_div').text();
                var b = parseFloat(total_sum_vat_div) + parseFloat($('#store_vou_vat_amount').val());
                $('#total_sum_vat_div_0').text(b.toFixed(2));


                var total_sum_net_div = $('#total_sum_net_div').text();
                var c = parseFloat(total_sum_net_div) + parseFloat($('#store_vou_price_net').val());
                $('#total_sum_net_div_0').text(c.toFixed(2))

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


            if ($('#item_id :selected').data('item').item_price_sales > $('#store_vou_item_price_unit').val()) {
                return 'price';
            }

            return true;

        }

        function saveItemRow(el) {
            console.log(el)
            if (el == 1) {
                // if (!checkItemInput()) {
                //     return toastr.warning('لا يوجد بيانات لاضافتها');
                // }

                if (checkItemInput() == 'price') {
                    return toastr.warning('سعر البيع  للمنتج اقل من سعر التكلفه');
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
                    'store_vou_qnt_o': parseFloat($('#store_vou_qnt_o').val()),
                    'store_vou_item_price_cost': $('#item_id :selected').data('item').item_price_cost,
                    'store_vou_item_price_sales': parseFloat($('#last_price_cost').val()),
                    'store_vou_item_price_unit': parseFloat($('#store_vou_item_price_unit').val()),
                    'store_vou_item_amount': parseFloat($('#store_vou_item_amount').val()),
                    'item_balance': parseFloat($('#item_balance').val()),
                    'store_vou_item_total_price': parseFloat($('#store_vou_item_total_price').val()),
                    'last_price_cost': parseFloat($('#last_price_cost').val()),
                    'store_vou_vat_rate': (vat_rate),
                    'store_vou_vat_amount': parseFloat($('#store_vou_vat_amount').val(), 2),
                    'store_vou_price_net': parseFloat($('#store_vou_price_net').val()),
                    'store_vou_disc_amount': parseFloat($('#store_vou_disc_amount').val()),
                    'store_voue_disc_value': parseFloat(customer_discount_rate),
                    'store_vou_disc_type': 533,
                    'item_stor_dt_serial': $("input[name='item_stor_dt_serial[]']:checkbox:checked")
                        .map(function () {
                            return $(this).val();
                        }).get(),
                    'item_stor_dt_serial_n': $("input[name='item_stor_dt_serial_n[]']")
                        .map(function () {
                            return $(this).val();
                        }).get(),
                };

                url = '{{ route('store-item-sales-invnew.store') }}';
                var form = new FormData($('#item_data_form')[0]);
                form.append('item_table_data', JSON.stringify(row_data));
                form.append('item_type', 'invnew');

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
                        updateItemTable(rowNo, row_data, data.uuid, data.total);

                        $('#total_sum_div_0').text(0);
                        $('#total_sum_vat_div_0').text(0);
                        $('#total_sum_net_div_0').text(0)

                        location.reload(true)

                    }
                    else {
                        toastr.warning(data.msg);
                    }
                });

            }

            if (el == 2) {
                row_data = {
                    'store_vou_vat_rate': ($('#customer_vat_rate').val()),
                    'store_vou_vat_amount': parseFloat($('#total_sum_vat_div').text()),
                    'store_vou_price_net': parseFloat($('#total_sum_net_div').text()),
                    'store_vou_desc': parseFloat($('#total_discount_div').val() / 115 * 100)
                };

                url = '{{ route('store-sales-inv.update') }}';

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
                    window.open(data, "_self")
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
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_qnt_o">' + rowData['store_vou_qnt_o'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_item_price">' + rowData['store_vou_item_price_unit'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_item_price">' + '<i class="btn btn-danger fa fa-plus btn-block"></i>' + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_disc_amount">' + rowData['store_vou_disc_amount'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_item_total_price">' + rowData['store_vou_item_total_price'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_vat_amount">' + rowData['store_vou_vat_amount'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_price_net">' + rowData['store_vou_price_net'] + '</td>' +
                '<td  class="ctd"> <button type="button" id="btnSave[]" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only" onclick="deleteItem(' + uuid + ')"><i class="fa fa-trash"></i></button></td>' +
                '<tr>' +
                '<div id="new_row"></div>';
            tableBody.append(markup);
            updateTotal(total);
            //reset input
            $('#item_id').val('').change();
            $('#add_item_div').find('input').val('');
            $('#item_id').selectpicker('refresh');
            $('#store_vou_qnt_o').val('');
            $('#store_vou_item_total_price').val('');
            $('#store_vou_vat_amount').val('');
            $('#store_vou_price_net').val('');
            $('#store_vou_disc_amount').val('');
            $('#item_balance').val('');
            $('#item_price_cost').val('');
            $('#last_price_cost').val('');
        }

        function deleteItem(uuid) {
            var form = new FormData($('#item_data_form')[0]);
            form.append('uuid', uuid);

            $.ajax({

                type: 'POST',
                url: "{{route('store-sales.delete')}}",
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

                        $('#total_sum_div_0').text(0);
                        $('#total_sum_vat_div_0').text(0);
                        $('#total_sum_net_div_0').text(0);
                        return 'true';
                    }
                    else {
                        toastr.warning(data.msg);
                        console.log('return dalse');
                        return 'false';
                    }
                }
            )

        }

        function updateTotal(total) {
            $('#total_sum_div').text(total['total_sum']);
            $('#total_sum').val(total['total_sum']);

            $('#total_sum_net_div').text(total['total_sum_net']);
            $('#total_sum_net').val(total['total_sum_net']);

            $('#total_sum_vat_div').text(total['total_sum_vat']);
            $('#total_sum_vat').val(total['total_sum_vat']);
            $('#total_discount_div').val(0);
            $('#vou_discount_rate').val(0);

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
                customer_id: '{{$purchase->customer->customer_id}}',
                bond_code_capture: '',
                bond: {},
                bond_message: '',
                bond_disabled: true,
                item_serial: 0,
                item_id: '',
                items: [],
                item_stor_dt_serial: [],
                item_stor_dt_serial_n: [{'item_serial': ''}],
                expire_date: '',
                issue_date: '',
                issue_date_hijri: '',
                expire_date_hijri: ''
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
                getBond() {
                    this.bond = {}
                    this.bond_message = ''
                    this.bond_disabled = true

                    if (this.bond_code_capture) {
                        $.ajax({
                            type: 'GET',
                            data: {customer_id: this.customer_id, bond_code: this.bond_code_capture},
                            url: '{{ route("Bonds-capture.GetBond") }}'
                        }).then(response => {
                            console.log(response)
                            if (response.status == "200") {
                                this.bond = response.data
                                this.bond_disabled = false
                            }

                            if (response.status == "500") {
                                this.bond_message = 'لا يوجد سند بهذا الكود وغير مرتبط بنشاط'
                            }

                        })
                    }

                },
                getItems() {
                    $.ajax({
                        type: 'GET',
                        data: {item_id: this.item_id},
                        url: '{{ route("store-sales-inv.getItems") }}'
                    }).then(response => {
                        console.log(response)
                        this.items = response.data
                    });
                },
                addRow() {
                    this.item_stor_dt_serial_n.push({'item_serial': ''})
                },
                supRow(index) {
                    this.item_stor_dt_serial_n.splice(index, 1)
                }
            },
            computed: {
                // item_error: function () {
                //     if (this.item_stor_dt_serial.length != this.item_serial) {
                //         this.disable_button = true
                //         return 'عدد العناصر الي تم اختيارها غير متساوي مع الكميه';
                //     } else {
                //         this.disable_button = false
                //         return '';
                //     }
                // },
                item_serial_rows: function () {
                    return parseInt(this.item_serial);
                }
            }
        })

        function genInvoice(uuid) {
            var form = new FormData($('#item_data_form')[0]);
            form.append('uuid', uuid);

            $.ajax({

                type: 'POST',
                url: "{{route('store-item-sales-invnew.generate')}}",
                data: form,
                cache: false,
                contentType: false,
                processData: false,


            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    location.reload();
                    $('#add_bond').removeAttr('disabled')
                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }
    </script>

@endsection


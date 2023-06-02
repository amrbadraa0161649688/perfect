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
                                                   id="store_hd_id" value="{{ $sales->store_hd_id }}">
                                            <input type="hidden" class="form-control" name="sales_uuid" id="sales_uuid"
                                                   value="{{ $sales->uuid }}">
                                            <input type="hidden" class="form-control" name="header_page"
                                                   id="header_page" value="inv">
                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label "> رقم فاتورة
                                                    المبيعات </label>
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
                                                    @foreach($customer as $vendor)
                                                        <option value="{{$vendor->customer_id}}"
                                                                data-vendorname="{{ $vendor->getCustomerName() }}"
                                                                data-vendorvat="{{ $vendor->customer_vat_no }}" {{($sales->store_acc_no == $vendor->customer_id ? 'selected': '' )}}> {{ $vendor->getCustomerName() }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label "> تاريخ فاتورة
                                                    المبيعات </label>
                                                <input type="text" class="form-control" name="created_date"
                                                       id="created_date"
                                                       value="{{ $sales->created_date->format('Y-m-d H:m') }}" readonly
                                                       disabled>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="recipient-name" class="col-form-label "> اسم العميل </label>
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
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('sales_car.store_vou_client_mob') </label>
                                                <input type="text" class="form-control" name="store_vou_client_mob"
                                                       id="store_vou_client_mob"
                                                       value="{{$sales->store_vou_client_mob}}">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('sales_car.store_vou_client_address') </label>
                                                <input type="text" class="form-control" name="store_vou_client_address"
                                                       id="store_vou_client_address"
                                                       value="{{$sales->store_vou_client_address}}">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label">@lang('sales_car.note') </label>
                                                <textarea rows="2" class="form-control" name="store_vou_notes"
                                                          id="store_vou_notes" placeholder="Here can be your note"
                                                          value=""> {{$sales->store_vou_notes }}</textarea>
                                            </div>


                                            @if($sales->store_vou_payment != $sales->store_vou_total)
                                                @if($sales->status)
                                                    <div class="col-md-4">
                                                        <br>
                                                        <br>
                                                        <button type="button" class="btn btn-primary btn-lg"
                                                                data-toggle="modal"
                                                                data-target="#exampleModal" data-whatever="@mdo"
                                                                @if($sales->status->system_code != 125002) disabled @endif>
                                                            @lang('home.add_bond')
                                                        </button>
                                                    </div>
                                                @endif
                                            @endif

                                            <div class="col-md-3">
                                                <br>
                                                <br>
                                                <button type="button" onclick="updateHeader()"
                                                        class="btn btn-primary btn-block">
                                                    <i class="fe fe-save mr-2"></i> تحديث
                                                </button>
                                            </div>
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
                                        <form action="{{route('sales-car-inv.addBondWithJournal2')}}" method="post">
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
                                                       value="{{$sales->store_hd_id}}">

                                                {{--النشاط--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.bonds_activity')</label>

                                                        <input type="text" disabled="" value="فاتوره بيع سيارات"
                                                               class="form-control">
                                                        <input type="hidden" name="transaction_type" value="83">

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

                                                        <input type="text" readonly class="form-control"
                                                               value="{{app()->getLocale()=='ar' ? $sales->customer->customer_name_full_ar :
                                                              $sales->customer->customer_name_full_en  }}">
                                                        <input type="hidden" name="customer_id"
                                                               value="{{ $sales->customer->customer_id }}">
                                                    </div>
                                                </div>

                                                {{-- قم الحساب للعميل--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.account_code')</label>
                                                        <input type="hidden" class="form-control"
                                                               name="bond_acc_id"
                                                               value="{{ $sales->customer->customer_account_id}}">
                                                        <input type="text" readonly class="form-control"
                                                               value="{{app()->getLocale() == 'ar' ?
                                                               $sales->customer->account->acc_name_ar :
                                                               $sales->customer->account->acc_name_en}} . {{ $sales->customer->account->acc_code }}">

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
                                $sales->company_group_id)->where('system_code',580008)->first()->system_code_name_ar}}">
                                                        @else
                                                            <input type="text" class="form-control" readonly value="{{App\Models\SystemCode::where('company_group_id',
                                $sales->company_group_id)->where('system_code',580008)->first()->system_code_name_en}}">
                                                        @endif

                                                        <input type="hidden" name="bond_doc_type" value="{{App\Models\SystemCode::where('company_group_id',
                                $sales->company_group_id)->where('system_code',580008)->first()->system_code_id}}">
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
                                                               value="{{$sales->store_vou_total - $sales->store_vou_payment }}"
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
                        {{-- bond modal --}}


                        <div class="row card">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <br>
                                    <div class="table-responsive">
                                        @include('salesCar.sales.inv.table.item_table')
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer row">
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{config('app.telerik_server')}}?rpt={{$sales->report_url_inv->report_url}}&id={{$sales->uuid}}&lang=ar&skinName=bootstrap"
                                   title="{{trans('Print')}}" class="btn btn-warning btn-block" id="showReport"
                                   target="_blank">
                                    {{trans('Print')}}

                                </a>
                            </div>
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{ route('sales-car-inv.index') }}"
                                   class="btn btn-secondary btn-block"> @lang('storeItem.back_button') </a>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>

                <div class="tab-pane fade" id="bonds-cash-grid" role="tabpanel">
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
                                                {{--@if($bond->journalCashMaintenanceCard)--}}
                                                {{--<a href="{{ route('journal-entries.show',$bond->journalCashMaintenanceCard->journal_hd_id) }}"--}}
                                                {{--class="btn btn-primary btn-sm">--}}
                                                {{--@lang('home.journal_details')--}}
                                                {{--{{$bond->journalCashMaintenanceCard->journal_hd_code}}--}}
                                                {{--</a>--}}
                                                {{--@endif--}}
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
                        <form action="{{route('sales-car-inv.storeBond')}}" method="post">
                            @csrf
                            <div class="row mb-2">

                                <input type="hidden" name="bond_id" :value="bond.bond_id"
                                       v-if="Object.keys(bond).length > 0">
                                <input type="hidden" name="sales_id" value="{{$sales->store_hd_id}}">
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
            </div>
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
        var company_id = {{ request()->company_id ? request()->company_id : auth()->user()->company_id }}
                @endif

            $(document).ready(function () {

                $(function () {
                    var params = new window.URLSearchParams(window.location.search);
                    console.log(params.get('qr'));
                    if (params.get('qr') == 'bond') {
                        $('#exampleModal').modal('show')
                    }
                });


                var d = new Date();
                var month = d.getMonth() + 1;
                var day = d.getDate();

                var output = (day < 10 ? '0' : '') + day + '/' +
                    (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
                ;

                $('#date').val(output)

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
                customer_id: '{{$sales->customer->customer_id}}',
                bond_code_capture: '',
                bond: {},
                bond_message: '',
                bond_disabled: true
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

                }
            },
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


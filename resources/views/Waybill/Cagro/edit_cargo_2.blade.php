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
    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">

                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-1">
                                </div>
                                <div class="col-md-3">
                                    <div class="font-25">
                                        @lang('waybill.add_new_waybill2')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section-body mt-6" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-6">

               
            {{-- Basic information --}}
                <div class="tab-pane fade show active " id="data-grid" role="tabpanel">

                
                    <div class="card">
                        <div class="section-body">
                            <div class="container-fluid">
                                <div class="d-flex justify-content-between align-items-center">
                                    <ul class="nav nav-tabs page-header-tab">
                                        <li class="nav-item">
                                            <a href="#form-grid" data-toggle="tab"
                                               class="nav-link active">@lang('home.update_form')</a>
                                        </li>

                                        <li class="nav-item"><a class="nav-link" href="#files-grid"
                                                                data-toggle="tab">@lang('home.files')</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#notes-grid"
                                                                data-toggle="tab">@lang('home.notes')</a></li>

                                                   
                                    </ul>
                                    <div class="header-action"></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="tab-content mt-3">
                        {{-- Form To Create Waybill--}}
                        <div class="tab-pane fade show active" id="form-grid" role="tabpanel">

                    {{-- Form To Create Waybill--}}
                    <form class="card" id="validate-form"
                          action="{{ route('Waybillcargo2.update',$waybill_hd->waybill_id) }}"
                          method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            {{--inputs data--}}
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="row">

                                        <div class="col-md-3">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('trucks.sub_company') </label>
                                            <input type="text" class="form-control" value="{{ app()->getLocale()=='ar' ?
                                             $company->company_name_ar : $company->company_name_en }}" readonly>
                                            <input type="hidden" name="company_id" value="{{ $company->company_id }}">
                                        </div>


                                        <div class="col-md-2">
                                            {{-- حاله الشحنه --}}
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_status') </label>

                                            <select class="form-select form-control" name="waybill_status"
                                                    id="waybill_status" required onchange="addPropReq()">
                                                @foreach($sys_codes_waybill_status as $sys_code_waybill_status)
                                                    <option value="{{$sys_code_waybill_status->system_code}}"
                                                            @if($waybill_hd->waybill_status == $sys_code_waybill_status->system_code_id)
                                                            selected @endif>
                                                        @if(app()->getLocale() == 'ar')
                                                            {{$sys_code_waybill_status->system_code_name_ar}}
                                                        @else
                                                            {{$sys_code_waybill_status->system_code_name_en}}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>

                                        <div class="col-md-3">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('waybill.waybill_no') </label>
                                            <input type="text" class="form-control"
                                                   value="{{ $waybill_hd->waybill_code }}" readonly>

                                        </div>


                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('waybill.created_date')</label>
                                                <input type="text" class="form-control" name="waybill_date"
                                                       id="waybill_date" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('home.user')</label>
                                                <input type="text" readonly class="form-control"
                                                       value="@if(app()->getLocale()=='ar') {{ auth()->user()->user_name_ar }}
                                                       @else {{ auth()->user()->user_name_en }} @endif">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        {{--اسم العميل--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"
                                                   style="text-decoration: underline;"> @lang('waybill.customer_name') </label>

                                            <input type="text" readonly class="form-control" value="{{app()->getLocale()=='ar' ?
                                             $waybill_hd->customer->customer_name_full_ar :  $waybill_hd->customer->customer_name_full_en }}">

                                        </div>
                                        {{--محطه الشحن--}}
                                        <div class="col-md-5">
                                            <label for="recipient-name"
                                                   class="col-form-label"
                                                   style="text-decoration: underline;"> @lang('waybill.waybill_froms') </label>
                                                
                                                   @if($waybill_status_system_code->system_code == 41004)
                                                <input type="text" class="form-control" readonly
                                                       value="@foreach(json_decode($waybill_hd->waybill_loc_from) as $loc)
                                                       {{app()->getLocale()=='ar' ?
                                                        \App\Models\SystemCode::where('system_code_id',$loc)->first()->system_code_name_ar .',' : \App\Models\SystemCode::where('system_code_id',$loc)->first()->system_code_name_en.','  }}
                                                       @endforeach">
                                            @else
                                                <select class="selectpicker form-control" multiple
                                                        data-live-search="true"
                                                        name="waybill_loc_from[]" id="waybill_loc_from">
                                                    @foreach($sys_codes_location as $sys_code_location)
                                                        <option value="{{ $sys_code_location->system_code_id }}"
                                                                @if($waybill_hd->waybill_loc_from)
                                                                @foreach(json_decode($waybill_hd->waybill_loc_from) as $loc_from)
                                                                @if($loc_from ==  $sys_code_location->system_code_id ) selected
                                                                @endif @endforeach @endif>
                                                            @if(app()->getLocale() == 'ar')
                                                                {{ $sys_code_location->system_code_name_ar }}
                                                            @else
                                                                {{ $sys_code_location->system_code_name_en }}@endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                        {{--عدد مواقع  الشحن --}}

                                        <div class="col-md-1">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_loc_from_no') </label>
                                            <input type="text" class="form-control"
                                                   name="waybill_sender_city"
                                                   id="waybill_sender_city"
                                                   value="{{ $waybill_hd->details->waybill_sender_city ?
                                                        $waybill_hd->details->waybill_sender_city  : 1 }}">
                                        </div>
                                        {{--تاريخ التحميل--}}
                                        <div class="col-md-3">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('waybill.waybill_date_loaded') </label>
                                            <input type="datetime-local" class="form-control" name="waybill_load_date"
                                                   id="waybill_date_loaded"
                                                   value="{{ $waybill_hd->waybill_load_date }}">
                                        </div>

                                    </div>
                                    <div class="row">

                                        {{-- رقم البوليصه--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_waybill_no') </label>
                                            <input type="text" class="form-control"
                                                   @if($waybill_status_system_code->system_code == 41004) 
                                                   @endif
                                                   name="waybill_ticket_no" id="waybill_ticket_no"
                                                   value="{{ $waybill_hd->waybill_ticket_no }}">
                                        </div>

                                        {{--محطه التفريغ--}}
                                        <div class="col-md-5">
                                            <label for="recipient-name"
                                                   class="col-form-label"
                                                   style="text-decoration: underline;"> @lang('waybill.waybill_tos')
                                            </label>
                                            @if($waybill_status_system_code->system_code == 41004)
                                                <input type="text" class="form-control" readonly
                                                       value="@foreach(json_decode($waybill_hd->waybill_loc_to) as $loc)
                                                       {{app()->getLocale()=='ar' ?
                                                        \App\Models\SystemCode::where('system_code_id',$loc)->first()->system_code_name_ar .',' : \App\Models\SystemCode::where('system_code_id',$loc)->first()->system_code_name_en.','  }}
                                                       @endforeach">
                                            @else
                                                <select class="selectpicker form-control" multiple
                                                        data-live-search="true"
                                                        name="waybill_loc_to[]" id="waybill_loc_to">
                                                    @foreach($sys_codes_location as $sys_code_location)
                                                        <option value="{{ $sys_code_location->system_code_id }}"
                                                                @if($waybill_hd->waybill_loc_to)
                                                                @foreach(json_decode($waybill_hd->waybill_loc_to) as $loc_to)
                                                                @if($loc_to ==  $sys_code_location->system_code_id ) selected
                                                                @endif @endforeach @endif>
                                                            @if(app()->getLocale() == 'ar')
                                                                {{ $sys_code_location->system_code_name_ar }}
                                                            @else
                                                                {{ $sys_code_location->system_code_name_en }}@endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif

                                        </div>

                                        {{--عدد مواقع التفريغ--}}

                                        <div class="col-md-1">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_loc_to_no') </label>
                                            <input type="text" class="form-control"
                                                   name="waybill_receiver_city"
                                                   id="waybill_receiver_city"
                                                   value="{{ $waybill_hd->waybill_receiver_city }}">
                                        </div>


                                        {{--تاريخ الوصول المتوقع--}}
                                        <div class="col-md-3">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('waybill.waybill_date_expected') </label>
                                            <input type="datetime-local" class="form-control"
                                                   name="waybill_delivery_expected"
                                                   id="waybill_delivery_expected"
                                                   value="{{ $waybill_hd->waybill_delivery_expected }}">
                                        </div>
                                    </div>


                                    <div class="row">

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.customer_contract') </label>
                                            <input type="text" class="form-control"
                                                   name="customer_contract" id="customer_contract"
                                                   @if($waybill_status_system_code->system_code == 41004) readonly
                                                   @endif
                                                   value="{{$waybill_hd->customer_contract}}">
                                        </div>

                                        {{--الكميه المطلوبه للعميل--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_qut_request') </label>
                                            <input type="text" class="form-control"
                                                   name="waybill_qut_requried_customer"
                                                   id="waybill_qut_requried_customer" readonly
                                                   value="{{ number_format($waybill_hd->details->waybill_qut_requried_customer,2) }}">
                                        </div>

                                        {{--الكميه المستلمه--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_qut_receved') </label>
                                            <input type="text" class="form-control"
                                                   name="waybill_qut_received_customer"
                                                   id="waybill_qut_received_customer"
                                                   @if($waybill_status_system_code->system_code == 41004) readonly
                                                   @endif
                                                   value="{{ number_format($waybill_hd->details->waybill_qut_received_customer,2) }}">
                                        </div>

                                        {{--تاريخ التسليم--}}
                                        <div class="col-md-3">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('waybill.waybill_date_end') </label>
                                            <input type="datetime-local" class="form-control"
                                                   name="waybill_delivery_date"
                                                   id="waybill_delivery_date"
                                                   value="{{ $waybill_hd->waybill_delivery_date }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_item') </label>
                                            <input type="text" readonly class="form-control" value="{{app()->getLocale()=='ar' ?
                                             $waybill_hd->details->item->system_code_name_ar : $waybill_hd->details->item->system_code_name_en }}">
                                        </div>

                                        {{--الكميه--}}
                                        <div class="col-md-1">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_qut_actual') </label>
                                            <input type="number" class="form-control"
                                                   name="waybill_item_quantity" id="waybill_item_quantity"
                                                   @if($waybill_status_system_code->system_code == 41004) readonly
                                                   @endif
                                                   value="{{ number_format($waybill_hd->details->waybill_item_quantity,2) ?
                                                    $waybill_hd->details->waybill_item_quantity  : 0 }}"
                                                   step="0.01">

                                        </div>

                                        <div class="col-md-1">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybil_item_unit') </label>


                                            <input type="text" class="form-control"
                                                   name="waybill_item_unit" id="waybill_item_unit" readonly
                                                   value="{{app()->getLocale()=='ar' ? $waybill_hd->details->itemUnit->system_code_name_ar :
                                                $waybill_hd->details->itemUnit->system_code_name_en }}">

                                        </div>

                                        {{--سعر الوحده--}}
                                        <div class="col-md-1">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_price') </label>
                                            <input type="number" class="form-control" step="0.01"
                                                   name="waybill_item_price"
                                                   id="waybill_item_price"
                                                   @if($waybill_hd->invoiceno > '0') readonly
                                                   @endif
                                                   value="{{ $waybill_hd->details->waybill_item_price}}">
                                        </div>


                                        <div class="col-md-1">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_fees_add') </label>
                                            <input type="number" class="form-control"
                                                   name="waybill_add_amount" id="waybill_add_amount" step="0.01"
                                                   @if($waybill_hd->invoiceno > '0') readonly
                                                   @endif
                                                   value="{{ $waybill_hd->details->waybill_add_amount }}">

                                        </div>
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.total') </label>
                                            <input type="number" class="form-control"
                                                   name="waybill_sub_total_amount"
                                                   id="waybill_sub_total_amount" readonly
                                                   value="{{ $waybill_hd->details->waybill_add_amount
                                                   +( $waybill_hd->details->waybill_item_price *
                                                    $waybill_hd->details->waybill_item_quantity)}}" step="0.01">

                                        </div>

                                        {{--الضريبه--}}
                                        <div class="col-md-1">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_vat') </label>
                                            <input type="number" class="form-control"
                                                   name="waybill_item_vat_rate" step="0.01"
                                                   id="waybill_item_vat_rate"
                                                   @if($waybill_status_system_code->system_code == 41004) readonly
                                                   @endif
                                                   value="{{$waybill_hd->waybill_vat_rate}}">

                                        </div>

                                        <div class="col-md-1">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_vat_amount') </label>
                                            <input type="number" class="form-control"
                                                   step="0.01" name="waybill_item_vat_amount"
                                                   id="waybill_item_vat_amount" readonly
                                                   value="{{ $waybill_hd->waybill_vat_amount }}">

                                        </div>

                                        {{--الاجمالي--}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_total') </label>
                                            <input type="number" class="form-control" readonly
                                                   step="0.01" name="waybill_total_amount" id="waybill_total_amount"
                                                   value="{{ $waybill_hd->waybill_total_amount }}">

                                        </div>

                                    </div>

                                    <div class="row">


                                        {{--السائق--}}
                                        <div class="col-md-6">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_driver') </label>

                                            
                                                <select class="form-select form-control" name="waybill_driver_id"
                                                        id="waybill_driver_id" readonly>
                                                    <option value="" selected></option>
                                                    @foreach($employees as $employee)
                                                        <option value="{{$employee->emp_id}}"
                                                                @if($waybill_hd->waybill_driver_id == $employee->emp_id) selected @endif>
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$employee->emp_name_full_ar}}
                                                            @else
                                                                {{$employee->emp_name_full_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            
                                        </div>

                                        {{--الشاحنه--}}
                                        <div class="col-md-6">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_truck') </label>
  
                                                <select class="form-select form-control" name="waybill_truck_id"
                                                        id="waybill_truck_id"  readonly>
                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($trucks as $truck)
                                                        <option value="{{ $truck->truck_id }}"
                                                                @if($waybill_hd->waybill_truck_id == $truck->truck_id) selected @endif>
                                                            {{$truck->truck_name}}</option>
                                                    @endforeach
                                                </select>

                                          
                                        </div>
                                    </div>


                                    <div class="card bline" style="color:red">
                                    </div>

                                    {{--المصاريف --}}
                                    <div class="row">

                                    {{--الطريق--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_fees_road') </label>
                                                   <input type="number" class="form-control" name="waybill_fees_difference"
                                                   id="waybill_fees_difference" step="0.01"
                                                   @if($waybill_hd->invoiceno > '0') readonly
                                                   @endif
                                                   value="{{$waybill_hd->details->waybill_fees_difference ?
                                                   $waybill_hd->details->waybill_fees_difference : 0}}"
                                                   >

                                               
                                       
                                                  
                                        </div>

                                        {{--السائق--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_fees_driver') </label>
                                                   <input type="number" class="form-control" name="waybill_fees_wait"
                                                   id="waybill_fees_wait" step="0.01"
                                                   @if($waybill_hd->invoiceno > '0') readonly
                                                   @endif
                                                   value="{{$waybill_hd->details->waybill_fees_wait ?
                                                   $waybill_hd->details->waybill_fees_wait : 0 }}">

                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_fees_total') </label>
                                            <input type="number" class="form-control"
                                                   name="waybill_fees_total" step="0.01"
                                                   id="waybill_fees_total" readonly
                                                   value="{{ ($waybill_hd->details->waybill_fees_difference +
                                                   $waybill_hd->details->waybill_fees_wait)}}" >

                                        </div>

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('invoice.invoice_no') </label>
                                            <input type="text" class="form-control"
                                                   name="invoice_no"
                                                   id="invoice_no" readonly
                                                   value="{{ $waybill_hd->invoiceno ? $waybill_hd->invoiceno->invoice_no :''}}" >

                                        </div>
                                    </div>

                                    <div class="card bline" style="color:red">
                                    </div>

                                    


                                    <div class="row">

                                    <button class="btn btn-primary" type="submit" id="submit"
                                            onclick="alert('هل انت متاكد من اضافه البوليصه')">
                                        @lang('home.save')</button>
                                    <div class="spinner-border" role="status" style="display: none">
                                        <span class="sr-only">Loading...</span>
                                    </div>


                                        @if($waybill_hd->http_status != 200)
                                            <button type="button" class="btn btn-primary mr-1 ml-1"
                                                    id="waybill{{$waybill_hd->waybill_id}}"
                                                    onclick="createTrip1('{{$waybill_hd->waybill_id}}')">
                                                توثيق الحمولة
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-primary mr-1 ml-1" disabled>
                                                تم توثيق الحمولة
                                            </button>

                                        @endif

                                        @if($waybill_hd->http_status == 200 && !$waybill_hd->cancel_status)
                                            <button type="button" class="btn btn-primary mr-1 ml-1"
                                                    onclick="cancelWaybill('{{$waybill_hd->waybill_id}}')">
                                                الغاء الوثيقه
                                            </button>
                                        @endif

                                        @if($waybill_hd->cancel_status ==200)
                                            <button type="button" class="btn btn-primary mr-1 ml-1" disabled="">
                                                تم الغاء الوثيقه
                                            </button>
                                        @endif


                                        @if($waybill_hd->http_status == 200)
                                            <button type="button" class="btn btn-primary mr-1 ml-1"
                                                    onclick="printWaybill('{{$waybill_hd->waybill_id}}')">
                                                طباعه
                                            </button>
                                        @endif

                                    </div>


                                </div>
                            </div>
                        </div>
                    </form>
                </div>
               

                                {{-- files part --}}
                                <div class="tab-pane fade" id="files-grid" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-12">

                                            <x-files.form>
                                                <input type="hidden" name="transaction_id"
                                                    value="{{ $waybill_hd->waybill_id }}">
                                                <input type="hidden" name="app_menu_id" value="90">
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
                                                    value="{{ $waybill_hd->waybill_id }}">
                                                <input type="hidden" name="app_menu_id" value="90">
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
                                {{--end notes part--}}
                                </div>
                             </div>
                            </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script type="text/javascript"></script>
    <script>


function createTrip1(tripId) {
            $('#waybill' + tripId).prop('disabled', 'true')
            url = '{{ route('api.Waybill.createTrip') }}';
            $.ajax({
                type: 'POST',
                url: url,
                data:
                    {
                        "_token": "{{ csrf_token() }}",
                        'id': tripId,
                    },

            }).done(function (data) {
                $('#waybill' + tripId).removeAttr('disabled')
                if (data.success) {
                    toastr.success(data.msg);
                    location.reload();
                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }

        function cancelWaybill(waybill_id) {
            url = '{{ route('api.Waybill.cancelWaybill') }}';
            $.ajax({
                type: 'PUT',
                url: url,
                data:
                    {
                        "_token": "{{ csrf_token() }}",
                        'id': waybill_id,
                    },

            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    location.reload();
                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }

        function printWaybill(waybill_id) {
            //window.open("https://www.google.com");
            url = '{{ route('api.Waybill.printWaybill') }}';
            $.ajax({
                type: 'GET',
                url: url,
                data:
                    {
                        "_token": "{{ csrf_token() }}",
                        'id': waybill_id,
                    },

            }).done(function (data) {

                if (data.success) {
                    window.open(data.msg);
                    console.log(data.msg)
                    //  location.reload();
                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }


        function addPropReq() {
            if ($('#waybill_status').val() == 41001) {

                $('#customer_id').prop('required', true)
                $('#customer_id').addClass('is-invalid')

                $('#waybill_date_loaded').prop('required', true)
                $('#waybill_date_loaded').addClass('is-invalid')

                $('#waybill_loc_from').css('data-style', 'btn-danger')
                $('#waybill_loc_from').prop('required', 'true')

                $('#waybill_loc_to').css('data-style', 'btn-danger')
                $('#waybill_loc_to').prop('required', 'true')

                $('#waybill_qut_requried_customer').prop('required', true)
                $('#waybill_qut_requried_customer').addClass('is-invalid')

                $('.waybill_item_id').prop('required', true)
                $('.waybill_item_id').addClass('is-invalid')


                /////////remove required and is-invalid class
                $('#waybill_ticket_no').removeClass('is-invalid')
                $('#waybill_ticket_no').prop('required', false)

                $('#waybill_unload_date').removeClass('is-invalid')
                $('#waybill_unload_date').prop('required', false)

                $('#waybill_delivery_expected').removeClass('is-invalid')
                $('#waybill_delivery_expected').prop('required', false)

                $('#waybill_driver_id').removeClass('is-invalid')
                $('#waybill_driver_id').prop('required', false)

                $('#waybill_truck_id').removeClass('is-invalid')
                $('#waybill_truck_id').prop('required', false)

                $('#customer_contract').removeClass('is-invalid')
                $('#customer_contract').prop('required', false)

                $('#waybill_qut_received_customer').removeClass('is-invalid')
                $('#waybill_qut_received_customer').prop('required', false)

                $('#waybill_delivery_date').removeClass('is-invalid')
                $('#waybill_delivery_date').prop('required', false)

                $('#waybill_item_price').removeClass('is-invalid')
                $('#waybill_item_price').prop('required', false)

                $('#waybill_item_vat_rate').removeClass('is-invalid')
                $('#waybill_item_vat_rate').prop('required', false)

                $('#waybill_fees_wait').removeClass('is-invalid')
                $('#waybill_fees_wait').prop('required', false)

                $('#waybill_fees_difference').removeClass('is-invalid')
                $('#waybill_fees_difference').prop('required', false)

                $('#waybill_add_amount').removeClass('is-invalid')
                $('#waybill_add_amount').prop('required', false)

            }


            if ($('#waybill_status').val() == 41004) {

                $('#customer_id').prop('required', true)
                $('#customer_id').addClass('is-invalid')

                $('#waybill_date_loaded').prop('required', true)
                $('#waybill_date_loaded').addClass('is-invalid')

                $('#waybill_loc_from').prop('data-style', 'btn-danger')
                $('#waybill_loc_from').prop('required', 'true')

                $('#waybill_loc_to').prop('data-style', 'btn-danger')
                $('#waybill_loc_to').prop('required', 'true')

                $('#waybill_qut_requried_customer').prop('required', true)
                $('#waybill_qut_requried_customer').addClass('is-invalid')

                $('.waybill_item_id').prop('required', true)
                $('.waybill_item_id').addClass('is-invalid')

                $('#waybill_ticket_no').addClass('is-invalid')
                $('#waybill_ticket_no').prop('required', true)

                $('#waybill_unload_date').addClass('is-invalid')
                $('#waybill_unload_date').prop('required', true)

                $('#waybill_delivery_expected').addClass('is-invalid')
                $('#waybill_delivery_expected').prop('required', true)

                $('#waybill_driver_id').addClass('is-invalid')
                $('#waybill_driver_id').prop('required', true)

                $('#waybill_truck_id').addClass('is-invalid')
                $('#waybill_truck_id').prop('required', true)


                $('#waybill_qut_received_customer').addClass('is-invalid')
                $('#waybill_qut_received_customer').prop('required', true)

                $('#waybill_delivery_date').addClass('is-invalid')
                $('#waybill_delivery_date').prop('required', true)

                $('#waybill_item_price').addClass('is-invalid')
                $('#waybill_item_price').prop('required', true)

                $('#waybill_item_vat_rate').addClass('is-invalid')
                $('#waybill_item_vat_rate').prop('required', true)

                $('#waybill_fees_wait').addClass('is-invalid')
                $('#waybill_fees_wait').prop('required', true)

                $('#waybill_fees_difference').addClass('is-invalid')
                $('#waybill_fees_difference').prop('required', true)

                $('#waybill_add_amount').addClass('is-invalid')
                $('#waybill_add_amount').prop('required', true)

                $('#customer_contract').addClass('is-invalid')
                $('#customer_contract').prop('required', true)
            }
        }

        $('#waybill_item_price').keyup(function () {
            var total = parseFloat($('#waybill_item_price').val()) * parseFloat($('#waybill_item_quantity').val())
                + parseFloat($('#waybill_add_amount').val())

            $('#waybill_sub_total_amount').val(total.toFixed(2))

            var vat_amount = total * parseFloat($('#waybill_item_vat_rate').val()) / 100;

            // var total_vat_amount = total * parseFloat($('#waybill_item_vat_rate').val()) / 100
            $('#waybill_item_vat_amount').val(vat_amount.toFixed(2))

            var total_amount = vat_amount + total
            $('#waybill_total_amount').val(total_amount.toFixed(2))
        })

        $('#waybill_item_quantity').keyup(function () {
            var total = parseFloat($('#waybill_item_price').val()) * parseFloat($('#waybill_item_quantity').val())
                + parseFloat($('#waybill_add_amount').val())

            $('#waybill_sub_total_amount').val(total.toFixed(2))

            var vat_amount = total * parseFloat($('#waybill_item_vat_rate').val()) / 100;

            // var total_vat_amount = total * parseFloat($('#waybill_item_vat_rate').val()) / 100
            $('#waybill_item_vat_amount').val(vat_amount.toFixed(2))

            var total_amount = vat_amount + total
            $('#waybill_total_amount').val(total_amount.toFixed(2))
        })


        $('#waybill_add_amount').keyup(function () {
            var total = parseFloat($('#waybill_item_price').val()) * parseFloat($('#waybill_item_quantity').val())
                + parseFloat($('#waybill_add_amount').val())

            $('#waybill_sub_total_amount').val(total.toFixed(2))

            var vat_amount = total * parseFloat($('#waybill_item_vat_rate').val()) / 100;

            // var total_vat_amount = total * parseFloat($('#waybill_item_vat_rate').val()) / 100
            $('#waybill_item_vat_amount').val(vat_amount.toFixed(2))

            var total_amount = vat_amount + total
            $('#waybill_total_amount').val(total_amount.toFixed(2))
        })

        $('#waybill_item_vat_rate').keyup(function () {
            var total = parseFloat($('#waybill_item_price').val()) * parseFloat($('#waybill_item_quantity').val())
                + parseFloat($('#waybill_add_amount').val())

            $('#waybill_sub_total_amount').val(total.toFixed(2))

            var vat_amount = total * parseFloat($('#waybill_item_vat_rate').val()) / 100;

            // var total_vat_amount = total * parseFloat($('#waybill_item_vat_rate').val()) / 100
            $('#waybill_item_vat_amount').val(vat_amount.toFixed(2))

            var total_amount = vat_amount + total
            $('#waybill_total_amount').val(total_amount.toFixed(2))
        })

        $('#waybill_fees_wait').keyup(function () {
            var total = parseFloat($('#waybill_fees_wait').val()) +
                parseFloat($('#waybill_fees_difference').val())
            $('#waybill_fees_total').val(total.toFixed(2))
        })

        $('#waybill_fees_difference').keyup(function () {
            var total = parseFloat($('#waybill_fees_wait').val()) +
                parseFloat($('#waybill_fees_difference').val())
            $('#waybill_fees_total').val(total.toFixed(2))
        })

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

            $('form').submit(function () {

                $('#submit').css('display', 'none')
                $('.spinner-border').css('display', 'block')

            })

            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (day < 10 ? '0' : '') + day + '/' +
                (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
            ;
            $('#waybill_date').val(output)


            $('#waybill_qut_received_customer').keyup(function () {
                $('#waybill_item_quantity').val($('#waybill_qut_received_customer').val())
            })

        })
    </script>
    
    
        

       <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
     <script>
        new Vue({
            data : {
                expire_date: '',
                issue_date: '',
                issue_date_hijri: '',
                expire_date_hijri: '',
            },
            computed: {

                count_loc_from: function () {
                    return this.waybill_loc_from.length
                },
                count_loc_to: function () {
                    return this.waybill_loc_to.length
                },
            },
        
        methods: {
                getExpireDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.expire_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.expire_date_hijri = response.data
                    })
                },
                
                getIssueDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.issue_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.issue_date_hijri = response.data
                    })
                }
            }

        })

    </script>

@endsection

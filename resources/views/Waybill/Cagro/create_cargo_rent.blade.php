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
                                        @lang('waybill.add_new_waybill3')
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

                    {{-- Form To Create Waybill--}}
                    <form class="card" id="validate-form" action="{{ route('Waybillcargo2.store') }}"
                          method="post" enctype="multipart/form-data">
                        @csrf
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

                                        <div class="col-md-3">
                                            {{-- حاله الشحنه --}}
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_rent_status') </label>

                                            <select class="form-select form-control" name="waybill_status"
                                                    id="waybill_status" required onchange="addPropReq()">
                                                <option value="" selected>@lang('home.choose')</option>
                                                @foreach($sys_codes_waybill_status as $sys_code_waybill_status)
                                                    <option value="{{$sys_code_waybill_status->system_code}}">
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
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('waybill.created_date')</label>
                                                <input type="text" class="form-control" name="waybill_date"
                                                       id="waybill_date" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
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
                                            <select class="selectpicker form-control" data-live-search="true"
                                                    name="customer_id"
                                                    id="customer_id" v-model="customer_id"
                                                    @change="getPriceList()">
                                                <option value="" selected>@lang('home.choose')</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{$customer->customer_id }}">
                                                        @if(app()->getLocale() == 'ar')
                                                            {{ $customer->customer_name_full_ar }}
                                                        @else
                                                            {{ $customer->customer_name_full_en }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                        {{--محطه الشحن--}}
                                        <div class="col-md-5">
                                            <label for="recipient-name"
                                                   class="col-form-label"
                                                   style="text-decoration: underline;"> @lang('waybill.waybill_location')
                                                <span class="text-primary"
                                                      style="font-size: 20px">@{{count_loc_from}}</span></label>
                                            <select class="selectpicker form-control" multiple data-live-search="true"
                                                    name="waybill_loc_from[]" data-actions-box="true"
                                                    v-model="waybill_loc_from"
                                                    id="waybill_loc_from" @change="getPriceList()">
                                                <option value="" selected>@lang('home.choose')</option>
                                                @foreach($sys_codes_location as $sys_code_location)
                                                    <option value="{{ $sys_code_location->system_code_id }}">
                                                        @if(app()->getLocale() == 'ar')
                                                            {{ $sys_code_location->system_code_name_ar }}
                                                        @else
                                                            {{ $sys_code_location->system_code_name_en }}
                                                        @endif

                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{--عدد مواقع  الشحن --}}

                                        <div class="col-md-1">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_loc_from_no') </label>
                                            <input type="text" class="form-control"
                                                   name="waybill_sender_city"
                                                   id="waybill_sender_city"
                                            >
                                        </div>


                                        {{--تاريخ التحميل--}}
                                        <div class="col-md-3">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('waybill.waybill_start_date') </label>
                                            <input type="datetime-local" class="form-control" name="waybill_load_date"
                                                   id="waybill_date_loaded" value="{{$current_date}}"
                                                   placeholder="@lang('waybill.waybill_date_loaded')">
                                        </div>

                                    </div>
                                    <div class="row">

                                        {{-- رقم البوليصه--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_no') </label>
                                            <input type="text" class="form-control is-invalid" autocomplete="off"
                                                   name="waybill_ticket_no" id="waybill_ticket_no"
                                                   placeholder="@lang('waybill.waybill_waybill_no')">
                                        </div>

                                        {{--محطه التفريغ--}}
                                        <div  class="col-md-5">
                                            <label for="recipient-name"
                                                   class="col-form-label"
                                                   style="text-decoration: underline;"> @lang('waybill.waybill_tos')
                                                <span class="text-primary"
                                                      style="font-size: 20px">@{{count_loc_to}}</span></label>
                                            <select class="selectpicker form-control" multiple data-live-search="true"
                                                    name="waybill_loc_to[]" data-actions-box="true"
                                                    id="waybill_loc_to" v-model="waybill_loc_to"
                                                    @change="getPriceList()">
                                                <option value="" selected>@lang('home.choose')</option>
                                                @foreach($sys_codes_location as $sys_code_location)
                                                    <option value="{{ $sys_code_location->system_code_id }}">
                                                        @if(app()->getLocale() == 'ar')
                                                            {{ $sys_code_location->system_code_name_ar }}
                                                        @else
                                                            {{ $sys_code_location->system_code_name_en }}@endif

                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>


                                        {{--عدد مواقع التفريغ--}}

                                        <div  class="col-md-1">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_loc_to_no') </label>
                                            <input type="text" class="form-control"
                                                   name="waybill_receiver_city"
                                                   id="waybill_receiver_city"
                                            >
                                        </div>


                                        {{--تاريخ الوصول المتوقع--}}
                                        <div hidden class="col-md-3">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('waybill.waybill_date_expected') </label>
                                            <input type="datetime-local" class="form-control"
                                                   name="waybill_delivery_expected"
                                                   id="waybill_delivery_expected" value="{{$current_date}}"
                                                   placeholder="@lang('waybill.waybill_date_expected')">
                                        </div>
                                    </div>


                                    <div class="row">

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.customer_contract') </label>
                                            <input type="text" class="form-control" autocomplete="off"
                                                   name="customer_contract" id="customer_contract"
                                                   placeholder="@lang('waybill.customer_contract')">
                                        </div>

                                        {{--الكميه المطلوبه للعميل--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_days_req') </label>
                                            <input type="text" class="form-control"
                                                   name="waybill_qut_requried_customer"
                                                   id="waybill_qut_requried_customer"
                                                   v-model="waybill_qut_requried_customer"
                                                   placeholder="@lang('waybill.waybill_qut_request')">
                                        </div>

                                        {{--الكميه المستلمه--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_days_act') </label>
                                            <input type="text" class="form-control"
                                                   name="waybill_qut_received_customer"
                                                   id="waybill_qut_received_customer"
                                                   v-model="waybill_qut_received_customer"
                                                   placeholder="@lang('waybill.waybill_qut_receved')">
                                        </div>

                                        {{--تاريخ التسليم--}}
                                        <div class="col-md-3">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('waybill.waybill_end_date') </label>
                                            <input type="datetime-local" class="form-control"
                                                   name="waybill_delivery_date" value="{{$current_date}}"
                                                   id="waybill_delivery_date"
                                                   placeholder="@lang('waybill.waybill_date_end')">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_item') </label>
                                            <select class="form-select form-control waybill_item_id"
                                                    name="waybill_item_id"
                                                    id="waybill_item_id" v-model="waybill_item_id"
                                                    @change="getPriceList()">
                                                <option value="" selected></option>
                                                @foreach($sys_codes_item as $sys_code_item)
                                                    <option value="{{$sys_code_item->system_code_id}}">
                                                        @if(app()->getLocale() == 'ar')
                                                            {{$sys_code_item->system_code_name_ar}}
                                                        @else
                                                            {{$sys_code_item->system_code_name_en}}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>

                                        {{--الكميه--}}
                                        <div class="col-md-1">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_days_no') </label>
                                            <input type="number" class="form-control" v-model="waybill_item_quantity"
                                                   name="waybill_item_quantity" id="waybill_item_quantity" readonly
                                                   placeholder="@lang('waybill.waybill_qut_actual')" step="0.01">

                                        </div>

                                        <div class="col-md-1">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybil_item_unit') </label>
                                            <select class="form-select form-control is-invalid" name="waybill_item_unit"
                                                    id="waybill_item_unit" v-model="waybill_item_unit" required>
                                                <option value="" selected></option>
                                                @foreach($sys_codes_unit as $sys_code_unit)
                                                    <option value="{{$sys_code_unit->system_code_id}}">
                                                        @if(app()->getLocale() == 'ar')
                                                            {{$sys_code_unit->system_code_name_ar}}
                                                        @else
                                                            {{$sys_code_unit->system_code_name_en}}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>


                                        <div class="col-md-1">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_days_price') </label>
                                            <input type="number" class="form-control" step="0.01"
                                                   name="waybill_item_price" v-model="waybill_item_price"
                                                   id="waybill_item_price" placeholder="@lang('waybill.waybill_price')">
                                        </div>

                                        <div class="col-md-1">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_fees_add') </label>
                                            <input type="number" class="form-control" v-model="waybill_add_amount"
                                                   name="waybill_add_amount" id="waybill_add_amount" step="0.01"
                                                   placeholder="@lang('waybill.waybill_fees_add')">

                                        </div>
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.total') </label>
                                            <input type="number" class="form-control" readonly
                                                   v-model="waybill_sub_total_amount" name="waybill_sub_total_amount"
                                                   placeholder="@lang('waybill.total')" step="0.01">

                                        </div>
                                        <div class="col-md-1">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_vat') </label>
                                            <input type="number" class="form-control"
                                                   name="waybill_item_vat_rate" step="0.01"
                                                   id="waybill_item_vat_rate" v-model="waybill_item_vat_rate"
                                                   placeholder="@lang('waybill.waybill_vat')">

                                        </div>
                                        <div class="col-md-1">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_vat_amount') </label>
                                            <input type="number" class="form-control" readonly
                                                   v-model="waybill_item_vat_amount" step="0.01"
                                                   name="waybill_item_vat_amount" id="waybill_item_vat_amount"
                                                   placeholder="@lang('waybill.waybill_vat_amount')">

                                        </div>
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_total') </label>
                                            <input type="number" class="form-control" readonly
                                                   v-model="waybill_total_amount" step="0.01"
                                                   name="waybill_total_amount" id="waybill_total_amount"
                                                   placeholder="@lang('waybill.waybill_total')">

                                        </div>

                                    </div>

                                    <div class="row">

                                        {{--السائق--}}
                                        <div class="col-md-6">
                                            <label for="recipient-name"
                                                   class="col-form-label "> @lang('waybill.waybill_driver') </label>

                                            <input type="hidden" name="waybill_driver_id" :value="driver.emp_id"
                                                   v-if="Object.keys(driver).length">

                                            @if(app()->getLocale() == 'ar')
                                                <input type="text" class="form-control is-invalid"
                                                       :value="driver.emp_name_full_ar" readonly
                                                       v-if="Object.keys(driver).length">
                                            @else
                                                <input type="text" class="form-control is-invalid"
                                                       :value="driver.emp_name_full_en" readonly
                                                       v-if="Object.keys(driver).length">

                                            @endif

                                            {{--<input type="text" class="form-control" :value="driver_error"--}}
                                            {{--v-if="driver_error" readonly>--}}

                                            {{--<input type="text" class="form-control" readonly--}}
                                            {{--v-if="!driver_error && !Object.keys(driver).length">--}}

                                            <select name="waybill_driver_id" class="form-control"
                                                    v-model="driver_id"
                                                    v-if="!Object.keys(driver).length" @change="getTruck()">
                                                <option>@lang('home.choose')</option>
                                                @foreach($employees as $driver)
                                                    <option value="{{$driver->emp_id}}">
                                                        {{app()->getLocale()=='ar' ? $driver->emp_name_full_ar :
                                                        $driver->emp_name_full_en}}
                                                    </option>
                                                @endforeach
                                            </select>


                                        </div>

                                        {{--الشاحنه--}}
                                        <div class="col-md-6"
                                             v-if="!Object.keys(truck).length">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_truck') </label>

                                            <select class="selectpicker is-invalid" data-live-search="true"
                                                    name="waybill_truck_id" id="waybill_truck_id"
                                                    @change="getDriver()" v-model="truck_id">
                                                @foreach($trucks as $truck)
                                                    <option value="{{$truck->truck_id }}">
                                                        {{ $truck->truck_code}} //
                                                        {{ $truck->truck_name}} //
                                                        {{ $truck->truck_plate_no}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_truck') </label>

                                            <input type="text" class="form-control" v-if="Object.keys(truck).length"
                                                   :value="truck.truck_name" readonly>
                                            <input type="hidden" class="form-control"
                                                   v-if="Object.keys(truck).length"
                                                   :value="truck_id">
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
                                                   id="waybill_fees_difference" v-model="cost_fees"
                                                   placeholder="@lang('waybill.waybill_fees_driver')" step="0.01">

                                        </div>

                                        {{--السائق--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_fees_driver') </label>
                                            <input type="number" class="form-control" name="waybill_fees_wait"
                                                   id="waybill_fees_wait" v-model="distance_fees" step="0.01"
                                                   placeholder="@lang('waybill.waybill_fees_road')">


                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_fees_total') </label>
                                            <input type="number" class="form-control" readonly
                                                   v-model="waybill_fees_total" name="waybill_fees_total"
                                                   placeholder="@lang('waybill.waybill_fees_total')" step="0.01">

                                        </div>
                                    </div>

                                    <div class="card bline" style="color:red">
                                    </div>

                                    <button class="btn btn-primary" type="submit" id="submit" :disabled="dis_button"
                                            onclick="alert('هل انت متاكد من اضافه البوليصه')">
                                        @lang('home.save')</button>

                                    <div class="spinner-border" role="status" style="display: none">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
        $(document).ready(function () {
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
        })

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

                $('#waybill_ticket_no').prop('required', true)
                $('#waybill_ticket_no').addClass('is-invalid')

                $('#waybill_driver_id').prop('required', true)
                $('#waybill_driver_id').addClass('is-invalid')

                $('#waybill_truck_id').prop('required', true)
                $('#waybill_truck_id').addClass('is-invalid')


                $('.waybill_item_id').prop('required', true)
                $('.waybill_item_id').addClass('is-invalid')


                /////////remove required and is-invalid class
                // $('#waybill_ticket_no').removeClass('is-invalid')
                // $('#waybill_ticket_no').prop('required', false)

                $('#waybill_unload_date').removeClass('is-invalid')
                $('#waybill_unload_date').prop('required', false)

            //    $('#waybill_delivery_expected').removeClass('is-invalid')
              //  $('#waybill_delivery_expected').prop('required', false)

                // $('#waybill_driver_id').removeClass('is-invalid')
                //  $('#waybill_driver_id').prop('required', false)

                //  $('#waybill_truck_id').removeClass('is-invalid')
                //  $('#waybill_truck_id').prop('required', false)

                //  $('#customer_contract').removeClass('is-invalid')
                //  $('#customer_contract').prop('required', false)

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

                //  $('#customer_contract').addClass('is-invalid')
                // $('#customer_contract').prop('required', true)
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                waybill_item_vat_rate: 15,
                //customer
                waybill_qut_requried_customer: 1,
                waybill_qut_received_customer: 1,
                waybill_item_price: 0.00,
                waybill_item_unit: 93,
                waybill_loc_from: [],
                waybill_loc_to: [],
                customer_id: '',
                waybill_item_id: 511,
                cost_fees: 0.00,
                distance_fees: 0,
                waybill_add_amount: 0,
                driver_emp_id: 0,

                truck_id: '',
                driver: {},
                driver_error: '',
                driver_id: '',
                truck: {}
            },
            methods: {
                getTrucks() {
                    $.ajax({
                        type: 'GET',
                        data: {company_id: this.company_id},
                        url: '{{ route("api.company.trucks") }}'
                    }).then(response => {
                        this.trucks = response.data
                    })
                },
                getDriver() {
                    this.driver = {}
                    this.truck = {}
                    this.button_dis = false
                    $.ajax({
                        type: 'GET',
                        data: {truck_id: this.truck_id},
                        url: '{{ route("api.waybill.truck.driver") }}'
                    }).then(response => {
                        if (response.status == 500) {
                            this.driver_error = response.message
                            this.button_dis = true
                        } else {
                            this.button_dis = false
                            this.driver = response.data
                            this.driver_emp_id = driver.emp_id
                        }

                    })
                },
                getTruck() {
                    this.driver = {}
                    this.truck = {}
                    $.ajax({
                        type: 'GET',
                        data: {driver_id: this.driver_id},
                        url: '{{ route("api.waybill.truck") }}'
                    }).then(response => {
                        if (response.data) {
                            this.truck = response.data;
                            this.truck_id = response.data.truck_id
                        }
                    })
                },
                getPriceList() {
                    if (this.waybill_loc_from.length > 1) {
                        this.cost_fees = 0.00
                        this.waybill_item_price = 0.00
                        // مصؤوف الطريق
                        this.distance_fees = 0
                    }
                    if (this.customer_id && this.waybill_loc_from.length == 1 && this.waybill_loc_to.length > 0
                        && this.waybill_item_id) {
                        $.ajax({
                            type: 'GET',
                            data: {
                                loc_from: JSON.stringify(this.waybill_loc_from),
                                loc_to: JSON.stringify(this.waybill_loc_to),
                                customer_id: this.customer_id,
                                item_id: this.waybill_item_id
                            },
                            url: '{{ route("cargo2-getPriceList") }}'
                        }).then(response => {
                            //مصروف السائق
                            this.cost_fees = response.cost_fees
                            this.waybill_item_price = response.max_fees
                            // مصؤوف الطريق
                            this.distance_fees = response.distance_fees
                        })
                    }
                }
            },
            computed: {
                //customer
                waybill_item_quantity: function () {
                    if (this.waybill_qut_received_customer) {
                        return this.waybill_qut_received_customer
                    }
                    else if (this.waybill_qut_requried_customer) {
                        return this.waybill_qut_requried_customer
                    }
                },
                waybill_item_vat_amount: function () {
                    var x = parseFloat(this.waybill_item_vat_rate / 100) * (parseFloat(this.waybill_item_quantity) *
                        parseFloat(this.waybill_item_price) + parseFloat(this.waybill_add_amount))
                    return x.toFixed(2)
                },
                waybill_total_amount: function () {
                    var y = parseFloat(this.waybill_item_vat_amount)
                        + (parseFloat(this.waybill_item_quantity) * parseFloat(this.waybill_item_price))
                        + parseFloat(this.waybill_add_amount)
                    return y.toFixed(2)
                },
                waybill_sub_total_amount: function () {
                    var z = parseFloat(this.waybill_item_quantity) * parseFloat(this.waybill_item_price)
                        + parseFloat(this.waybill_add_amount)
                    return z.toFixed(2)
                },
                waybill_fees_total: function () {
                    var h = parseFloat(this.distance_fees) + parseFloat(this.cost_fees)
                    return h.toFixed(2)
                },
                count_loc_from: function () {
                    return this.waybill_loc_from.length
                },
                count_loc_to: function () {
                    return this.waybill_loc_to.length
                },
                dis_button: function () {
                    if (this.waybill_total_amount > 0) {
                        return false
                    } else {
                        return true;
                    }
                }


            }
        })
    </script>

@endsection

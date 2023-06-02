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

    <div class="container-fluid">

        <div class="section-body mt-3" id="app">

            <div class="container-fluid">

                @include('Includes.form-errors')

                <div class="card">

                    <div class="card-body">

                        <div class="row clearfix">

                            {{--محجوزه--}}
                            <div class="col-lg-2 col-md-6">
                                <form action="">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6>محجوزه </h6>
                                            <h3 class="pt-2">
                                                <span class="counter">{{$reserved_waybills_count}}</span>
                                            </h3>
                                            <a href="{{route('WaybillCar')}}?waybill_status_filter={{
                            \App\Models\SystemCode::where('system_code',41001)
                            ->where('company_group_id',$company->company_group_id)->first()
                            ->system_code}}" class="text-danger">
                            <span><span class="text-danger mr-2">
                            <i class="fa fa-car"></i> {{$reserved_waybills_cars_count}}</span>  </span>
                                            </a>

                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{--بوليصه عميل--}}
                            <div class="col-lg-2 col-md-6">
                                <div class="card">
                                    <div class="card-body" onclick="this.form.submit()">
                                        <h6> بوليصه</h6>
                                        <h3 class="pt-2"><span class="counter">{{ $way_waybills_count }}</span></h3>
                                        <a href="{{route('WaybillCar')}}?waybill_status_filter={{
                            \App\Models\SystemCode::where('system_code',41004)
                            ->where('company_group_id',$company->company_group_id)->first()
                            ->system_code}}" class="text-danger">
                            <span><span class="text-danger mr-2"><i
                                            class="fa fa-gears"></i>{{$way_waybills_cars_count}}</span> </span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{--متاخره--}}
                            <div class="col-lg-2 col-md-6">
                                <form action="">
                                    <div class="card">
                                        <div class="card-body" onclick="this.form.submit()">
                                            <h6>متاخره </h6>
                                            <h3 class="pt-2">
                                                <span class="counter">{{$late_waybills_count}}</span>
                                            </h3>

                                            <a href="{{route('WaybillCar')}}?waybill_status_filter=late"
                                               class="text-danger">
                            <span><span class="text-danger mr-2">
                            <i class="fa fa-car"></i> {{ $late_waybills_cars_count}}</span>  </span>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>


                            {{--في الطريق--}}
                            <div class="col-lg-2 col-md-6">
                                <form action="">
                                    <div class="card">
                                        <div class="card-body" onclick="this.form.submit()">
                                            <h6> في الطريق</h6>
                                            <h3 class="pt-2">
                                                <span class="counter">{{$transit_waybills_count}}</span>
                                            </h3>

                                            <a href="{{route('WaybillCar')}}?waybill_status_filter={{
                            \App\Models\SystemCode::where('system_code',41006)
                            ->where('company_group_id',$company->company_group_id)->first()
                            ->system_code}}" class="text-danger">
                            <span><span class="text-success mr-2"><i
                                            class="fa fa-paper-plane-o"></i>
                                    {{ $transit_waybills_cars_count}}</span> </span>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>


                            <div class="col-lg-2 col-md-6">
                                <form action="">
                                    <div class="card">
                                        <div class="card-body" onclick="this.form.submit()">
                                            <h6> وصلت</h6>
                                            <h3 class="pt-2"><span class="counter">{{$arrived_waybills_count}}</span>
                                            </h3>
                                            <a href="{{route('WaybillCar')}}?waybill_status_filter={{
                            \App\Models\SystemCode::where('system_code',41007)
                            ->where('company_group_id',$company->company_group_id)->first()
                            ->system_code}}" class="text-danger">
                            <span><span class="text-danger mr-2"><i
                                            class="fa fa-gears"></i>
                                    {{ $arrived_waybills_cars_count}}</span> </span>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>


                            <div class="col-lg-2 col-md-6">
                                <form action="">
                                    <div class="card">
                                        <div class="card-body" onclick="this.form.submit()">
                                            <h6> سلمت</h6>
                                            <h3 class="pt-2"><span class="counter">{{$delivered_waybills_count}}</span>
                                            </h3>
                                            <a href="{{route('WaybillCar')}}?waybill_status_filter={{
                            \App\Models\SystemCode::where('system_code',41008)
                            ->where('company_group_id',$company->company_group_id)->first()
                            ->system_code}}" class="text-danger">
                            <span><span class="text-danger mr-2"><i class="fa fa-gears"></i>
                                    {{ $delivered_waybills_cars_count}}</span> </span>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>


                        </div>


                        <form action="">
                            <div class="row">
                                {{--الفروع--}}
                                <div class="col-md-2">
                                    <label class="form-label">@lang('home.from')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="branch_id[]" data-actions-box="true" required>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->branch_id}}"
                                                    @if(!request()->branch_id) @if(session('branch')['branch_id'] == $branch->branch_id)
                                                    selected @endif @endif
                                                    @if(request()->branch_id) @foreach(request()->branch_id as
                                                     $branch_id) @if($branch_id == $branch->branch_id)
                                                    selected @endif @endforeach @endif>
                                                {{app()->getLocale()=='ar' ? $branch->branch_name_ar :
                                                $branch->branch_name_en}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">@lang('home.to')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="branch_to[]" data-actions-box="true">
                                        @foreach($sys_codes_loc_to as $branch_too)
                                            <option value="{{$branch_too->system_code_id}}"
                                                    @if(request()->branch_to) @foreach(request()->branch_to as $branch_to)
                                                    @if($branch_to == $branch_too->system_code_id)
                                                    selected @endif @endforeach @endif>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$branch_too->system_code_name_ar}}
                                                @else
                                                    {{$branch_too->system_code_name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="recipient-name"
                                           class="form-label"> @lang('waybill.transport_type') </label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="waybill_item_id[]" data-actions-box="true">
                                        @foreach($sys_codes_shipping as $sys_code_shipping)
                                            <option value="{{$sys_code_shipping->system_code_id}}"
                                                    @if(request()->waybill_item_id) @foreach(request()->waybill_item_id as $waybill_item_id)
                                                    @if($waybill_item_id == $sys_code_shipping->system_code_id)
                                                    selected @endif @endforeach @endif>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$sys_code_shipping->system_code_name_ar}}
                                                @else
                                                    {{$sys_code_shipping->system_code_name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                                {{--العملاء--}}
                                <div class="col-md-2">
                                    {{-- customers  --}}
                                    <label class="form-label">@lang('home.customers')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="customers_id[]">
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->customer_id }}"
                                                    @if(request()->customers_id) @foreach(request()->customers_id as
                                                     $customer_id) @if($customer->customer_id == $customer_id) selected @endif @endforeach @endif>
                                                {{app()->getLocale()=='ar' ? $customer->customer_name_full_ar
                                             : $customer->customer_name_full_en }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    {{-- حاللات البوليصه  --}}
                                    <label class="form-label">@lang('home.statuses')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="statuses_id[]" data-actions-box="true">
                                        @foreach($sys_codes_waybill_status as $status)
                                            <option value="{{ $status->system_code_id }}"
                                                    @if(request()->statuses_id) @foreach(request()->statuses_id as
                                                     $status_id) @if($status->system_code_id == $status_id)
                                                    selected @endif @endforeach @endif>
                                                {{app()->getLocale()=='ar' ? $status->system_code_name_ar
                                             : $status->system_code_name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    {{-- حالات البوليصه  --}}
                                    <label class="form-label">@lang('home.invoiced')</label>
                                    <select class="form-control" name="invoice_id">
                                        <option value="">@lang('home.choose')</option>
                                        <option value="1"
                                                @if(request()->invoice_id && request()->invoice_id == 1) selected
                                                @endif>@lang('home.invoiced')</option>

                                        <option value="2"
                                                @if(request()->invoice_id && request()->invoice_id == 2) selected
                                                @endif>@lang('home.not_invoiced')</option>
                                    </select>
                                </div>


                            </div>

                            <div class="row">


                                {{--تاريخ اانشاء من والي--}}
                                <div class="col-md-2">
                                    <label>@lang('home.created_date_from')</label>
                                    <input type="date" class="form-control" name="created_date_from"
                                           @if(request()->created_date_from) value="{{request()->created_date_from}}"
                                            @endif>
                                </div>

                                <div class="col-md-2">
                                    <label>@lang('home.created_date_to')</label>
                                    <input type="date" class="form-control" name="created_date_to"
                                           @if(request()->created_date_to) value="{{request()->created_date_to}}" @endif>
                                </div>

                                {{--تاريخ التوصيل المتوقع من والي--}}

                                <div class="col-md-2">
                                    <label class="form-label">@lang('waybill.waybill_no')</label>
                                    <input type="text" class="form-control" name="waybill_code"
                                           @if(request()->waybill_code) value="{{request()->waybill_code}}" @endif>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">@lang('waybill.car_plate')</label>
                                    <input type="text" class="form-control" name="expected_date_to"
                                           @if(request()->expected_date_to) value="{{request()->expected_date_to}}" @endif>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">@lang('waybill.receiver_p_mobile')</label>
                                    <input type="text" class="form-control" name="expected_date_from"
                                           @if(request()->expected_date_from) value="{{request()->expected_date_from}}"
                                            @endif>
                                </div>


                                <div class="col-md-2">
                                    <button class="btn btn-primary mt-4"
                                            type="submit">@lang('home.search')
                                        <i class="fa fa-search"></i></button>
                                </div>


                                <div class="col-md-4" hidden>
                                    @if(session('company_group'))
                                        <input type="text" class="form-control"
                                               value="@if(app()->getLocale()=='ar')
                                               {{ session('company_group')['company_group_ar'] }} @else
                                               {{ session('company_group')['company_group_en']}} @endif"
                                               readonly>
                                    @else
                                        <input type="text" class="form-control"
                                               value="@if(app()->getLocale()=='ar')
                                               {{ auth()->user()->companyGroup->company_group_ar }} @else
                                               {{ auth()->user()->companyGroup->company_group_en }} @endif"
                                               readonly>
                                    @endif
                                </div>


                            </div>

                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">


                        <div class="row mb-12">


                            <div class="col-md-3">
                                @if(auth()->user()->user_type_id != 1)
                                    @foreach(session('job')->permissions as $job_permission)
                                        @if($job_permission->app_menu_id == 88 && $job_permission->permission_add)
                                            <button type="button" class="btn btn-primary"
                                                    data-toggle="modal"
                                                    data-target="#exampleModal">

                                                <a href="{{route('Waybill.create_car')}}"
                                                   class="btn btn-primary">
                                                    <i class="fe fe-plus mr-2"></i>@lang('waybill.add_new_waybillcar')
                                                </a>
                                            </button>
                                        @endif
                                    @endforeach
                                @else
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModal">

                                        <a href="{{route('Waybill.create_car')}}" class="btn btn-primary">
                                            <i class="fe fe-plus mr-2"></i>@lang('waybill.add_new_waybillcar')
                                        </a>
                                    </button>
                                @endif

                            </div>
                            <div class="col-md-1">

                            </div>

                            <div class="col-md-3">
                                <button type="button" class="btn btn-primary">
                                    <a href="{{route('WaybillCarDeliver.create')}}"
                                       class="btn btn-primary">
                                        <i class="fe fe-plus mr-2"></i>{{__('cars deliver')}}
                                    </a>
                                </button>
                            </div>

                           

                            <div class="col-md-3">

                            </div>

                            <div class="col-md-2">
                                <form action="{{route('waybill-car-export') }}">
                                    @if(request()->company_id)
                                        @foreach(request()->company_id as $company_id)
                                            <input type="hidden" name="company_id[]"
                                                   value="{{ $company_id }}">
                                        @endforeach
                                    @endif

                                    @if (request()->created_date_from && request()->created_date_to)
                                        <input type="hidden" name="created_date_from"
                                               value="{{request()->created_date_from}}">
                                        <input type="hidden" name="created_date_to"
                                               value="{{request()->created_date_to}}">
                                    @endif

                                    @if (request()->customers_id)
                                        @foreach(request()->customers_id as $customer_id)
                                            <input type="hidden" name="customers_id[]"
                                                   value="{{$customer_id}}">
                                        @endforeach
                                    @endif

                                    @if (request()->statuses_id)
                                        @foreach(request()->statuses_id as $status_id)
                                            <input type="hidden" name="statuses_id[]"
                                                   value="{{$status_id}}">
                                        @endforeach
                                    @endif



                                    @if (request()->expected_date_from && request()->expected_date_to)
                                        <input type="hidden" name="expected_date_from"
                                               value="{{request()->expected_date_from}}">
                                        <input type="hidden" name="expected_date_to"
                                               value="{{request()->expected_date_to}}">
                                    @endif

                                    <button href="{{ route('waybill-car-export') }}" type="submit"
                                            class="btn btn-primary">@lang('home.export')
                                        <i class="fa fa-file-excel-o"></i></button>


                                </form>

                            </div>


                            <div class="table-responsive">

                                <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                    <thead style="background-color: #ece5e7">
                                    <tr class="red">
                                        <th>
                                            @if(app()->getLocale()=='en')
                                                @sortablelink('waybill_code','waybill code')
                                            @else
                                                @sortablelink('waybill_code','رقم البوليصه')
                                            @endif
                                        </th>
                                        <th style="font-size: 12px ;font-weight: bold">@if(app()->getLocale()=='en')
                                                @sortablelink('created_date','date')
                                            @else
                                                @sortablelink('created_date','التاريخ')
                                            @endif
                                        </th>
                                        <th style="font-weight: bold">
                                            @if(app()->getLocale()=='en')
                                                @sortablelink('customer.customer_name_full_en','customer
                                                name')
                                            @else
                                                @sortablelink('customer.customer_name_full_ar','اسم المالك')
                                            @endif
                                        </th>
                                        <th>@lang('waybill.transport_type')</th>
                                        <th style="font-size: 16px ;font-weight: bold">@lang('waybill.car_plate')</th>
                                        <th style="color: blue">@lang('home.from')</th>
                                        <th style="color: blue">@lang('home.to')</th>
                                        <th>@lang('waybill.receiver_p_mobile')</th>
                                        <th>@lang('waybill.total')</th>

                                        <th style="color: red">@lang('waybill.net_amount')</th>
                                        <th>@lang('waybill.waybill_trip_no')</th>
                                        <th>{{__('Cars Count')}}</th>
                                        <th colspan="2"></th>
                                    </tr>
                                    </thead>
                                    <tbody>


                                    @foreach($way_pills as $way_pill)
                                        <tr>
                                            <td>
                                                <a href="{{ route('Waybill.edit_car',$way_pill->waybill_id) }}"
                                                   class="btn btn-primary btn-sm">
                                                    {{ $way_pill->waybill_code }}
                                                </a>
                                            </td>
                                            <td> {{ date('d-m-y', strtotime($way_pill->waybill_load_date)) }} </td>
                                            <td>
                                                {{ $way_pill->waybill_sender_name }}
                                            </td>

                                            <td>
                                                {{ $way_pill->detailsCar
                                                    ? $way_pill->detailsCar->item->system_code_name_ar
                                                    : ''}}
                                            </td>
                                            <td style="font-size: 16px ;font-weight: bold">
                                                {{ $way_pill->detailsCar
                                                    ? $way_pill->detailsCar->waybill_car_plate
                                                    : '' }}
                                            </td>
                                            <td style="color: blue">
                                                {{$way_pill->locfrom
                                                    ? $way_pill->locfrom->system_code_name_ar
                                                    :' '}}
                                            </td>
                                            <td style="color: blue">
                                                {{ $way_pill->locTo
                                                    ? $way_pill->locTo->system_code_name_ar
                                                    : '' }}</td>

                                            <td>
                                                {{ $way_pill->waybill_receiver_mobile }}
                                            </td>
                                            <td>
                                                {{  number_format($way_pill->waybill_total_amount,2) }}
                                            </td>
                                            <td style="color: red">
                                                {{  number_format($way_pill->waybill_total_amount - $way_pill->waybill_paid_amount,2)  }}
                                            </td>

                                            <td style="font-size: 10px ;font-weight: bold">

                                                @if($way_pill->trip)
                                                    <a href="{{ route('Trips.edit',$way_pill->waybill_trip_id) }}"
                                                       class=" btn btn-primary btn-sm btn-info"
                                                       style="font-size: 10px ;font-weight: bold"
                                                       target="_blank">
                                                        {{$way_pill->trip->trip_hd_code}}
                                                    </a>

                                                @else
                                                    لا يوجد
                                                @endif
                                            </td>
                                            <td> @if($way_pill->detailsCar)
                                                {{$way_pill->detailsCar->waybill_qut_received_customer}}
                                                @endif
                                            </td>
                                            <td>
                                            <span class="tag tag-success"> 
                                                @if($way_pill->status)
                                                    {{app()->getLocale()=='ar'
                                                    ?  $way_pill->status->system_code_name_ar
                                                    : $way_pill->status->system_code_name_en }}
                                                @endif
                                                

                                                                            
                                                </span>

                                            </td>

                                            <td colspan="2">
                                            @if($way_pill->detailsCar)
                                                @if($way_pill->detailsCar->waybill_item_quantity > 1)
                                                    <a href="{{config('app.telerik_server')}}?rpt={{$way_pill->report_url_waybill_co->report_url}}&id={{$way_pill->waybill_id}}&lang=ar&skinName=bootstrap"
                                                       class="btn btn-primary btn-sm" target="_blank">
                                                        <i class="fa fa-print"></i></a>
                                                @else
                                                    <a href="{{config('app.telerik_server')}}?rpt={{$way_pill->report_url_waybill->report_url}}&id={{$way_pill->waybill_id}}&lang=ar&skinName=bootstrap"
                                                       class="btn btn-primary btn-sm" target="_blank">
                                                        <i class="fa fa-print"></i>

                                                    </a>
                                                @endif
                                                @endif
                                                @if($way_pill->invoiceno)
                                                    <a href="{{config('app.telerik_server')}}?rpt={{$way_pill->invoiceno->report_url_car->report_url}}&id={{$way_pill->invoiceno->invoice_id}}&lang=ar&skinName=bootstrap"
                                                       class="btn btn-primary btn-sm" target="_blank">
                                                        <i class="fa fa-file-o"></i>
                                                    </a>
                                                @endif


                                            </td>
                                        </tr>
                                    @endforeach


                                    <tr>
                                        <td colspan="6">@lang('home.total') : {{ $total }}</td>
                                        <td colspan="6">@lang('home.total_vat') : {{ $total_vat }}</td>
                                    </tr>

                                    </tbody>

                                </table>

                            </div>

                            <div class="row w-100 mt-3">
                                <div class="col-12">
                                    {{ $way_pills->appends($data)->links() }}
                                </div>
                            </div>


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
@endsection


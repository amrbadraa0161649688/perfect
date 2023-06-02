@extends('Layouts.master')
@section('style')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/plugins/toastr/toastr.min.css')}}">
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
                <div class="row clearfix">
                    <div class="col-6 col-md">
                        <div class="card">
                            <div class="card-body">
                                <h6>بوليصه نقل محروقات </h6>
                                <h3 class="pt-2">
                                    <span class="counter">{{$query1 + $query2 + $query0 + $query3}}</span>
                                </h3>
                                <span><span class="text-danger mr-2"><i
                                            class="fa fa-car"></i> </span>  </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md">
                        <div class="card">
                            <div class="card-body">
                                <h6>امر تحميل </h6>
                                <h3 class="pt-2">
                                    <span class="counter">{{$query1}}</span>
                                </h3>
                                <span><span class="text-danger mr-2"><i
                                            class="fa fa-car"></i> {{$query1_p}} %</span>  </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md">
                        <div class="card">
                            <div class="card-body">
                                <h6>فاتورة شراء </h6>
                                <h3 class="pt-2">
                                    <span class="counter">{{$query2}}</span>
                                </h3>
                                <span><span class="text-danger mr-2">
                                                <i class="fa fa-car"></i> {{$query2_p}} %</span>  </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md">
                        <div class="card">
                            <div class="card-body">
                                <h6>فاتورة مبيعات </h6>
                                <h3 class="pt-2">
                                    <span class="counter">{{$query0}}</span>
                                </h3>
                                <span><span class="text-danger mr-2">
                                                <i class="fa fa-car"></i> {{$query0_p}} %</span>  </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md">
                        <div class="card">
                            <div class="card-body">
                                <h6> تم التسليم</h6>
                                <h3 class="pt-2"><span class="counter">{{$query3}}</span></h3>
                                <span><span class="text-success mr-2"><i class="fa fa-paper-plane-o"></i>
                                        {{$query3_p}} %</span> </span>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                {{--الشركات--}}
                                <div class="col-md-4">
                                    <label>@lang('home.companies')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="company_id[]" data-actions-box="true">
                                        @foreach($companies as $company_s)
                                            <option value="{{$company_s->company_id}}"
                                                    @if(request()->company_id) @foreach(request()->company_id as
                                                     $company_id) @if($company_s->company_id == $company_id) selected
                                                    @endif @endforeach @endif
                                                    @if(!request()->company_id) @if($company->company_id == $company_s->company_id) selected @endif @endif>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$company_s->company_name_ar}}
                                                @else
                                                    {{$company_s->company_name_en}}
                                                @endif
                                            </option>

                                        @endforeach
                                    </select>
                                </div>

                                {{--العملاء--}}
                                <div class="col-md-4">
                                    {{-- customers  --}}
                                    <label>@lang('home.customers')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="customers_id[]" data-actions-box="true">
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->customer_id }}"
                                                    @if(!request()->customer_id) selected @endif
                                                    @if(request()->customers_id) @foreach(request()->customers_id as
                                                     $customer_id) @if($customer->customer_id == $customer_id) selected @endif @endforeach @endif>
                                                {{app()->getLocale()=='ar' ? $customer->customer_name_full_ar
                                             : $customer->customer_name_full_en }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-4">
                                    {{-- حاللات البوليصه  --}}
                                    <label>@lang('home.statuses')</label>
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
                                <div hidden class="col-md-2">
                                    <label>@lang('home.expected_date_from')</label>
                                    <input type="date" class="form-control" name="expected_date_from"
                                           @if(request()->expected_date_from) value="{{request()->expected_date_from}}"
                                        @endif>
                                </div>
                                <div hidden class="col-md-2">
                                    <label>@lang('home.expected_date_to')</label>
                                    <input type="date" class="form-control" name="expected_date_to"
                                           @if(request()->expected_date_to) value="{{request()->expected_date_to}}" @endif>
                                </div>
                                <div class="col-md-2">
                                    <label>@lang('waybill.waybill_ticket_no')</label>
                                    <input type="text" class="form-control" name="waybill_ref"
                                           @if(request()->waybill_ref) value="{{request()->waybill_ref}}" @endif>
                                </div>
                                <div class="col-md-3">
                                    {{-- الشاحنات --}}
                                    <label>@lang('waybill.waybill_truck')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="trucks_id[]" data-actions-box="true">
                                        @foreach($trucks as $truck)
                                            <option value="{{$truck->truck_code }}" @if(request()->trucks_id) @foreach(request()->trucks_id as
                                                     $truck_id) @if($truck->truck_code == $truck_id)
                                                selected @endif @endforeach @endif>
                                                {{ $truck->truck_code}} //
                                                {{ $truck->truck_name}}

                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button class="btn btn-primary mt-4" type="submit">@lang('home.search')
                                        <i class="fa fa-search"></i></button>
                                </div>


                                <div class="col-md-4" hidden>
                                    @if(session('company_group'))
                                        <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                                        {{ session('company_group')['company_group_ar'] }} @else
                                        {{ session('company_group')['company_group_en']}} @endif" readonly>
                                    @else
                                        <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                                        {{ auth()->user()->companyGroup->company_group_ar }} @else
                                        {{ auth()->user()->companyGroup->company_group_en }} @endif" readonly>
                                    @endif
                                </div>


                            </div>


                        </div>
                    </div>
            </div>
            </form>
        </div>

        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <div class="">
                    @if(auth()->user()->user_type_id != 1)
                        @foreach(session('job')->permissions as $job_permission)
                            @if($job_permission->app_menu_id == 70 && $job_permission->permission_add)
                                <button type="button" class="btn btn-primary mx-1" data-toggle="modal"
                                        data-target="#exampleModal">

                                    <a href="{{route('Waybill.create')}}" class="btn btn-primary">
                                        <i class="fe fe-plus mr-2"></i>@lang('waybill.add_new_waybill')
                                    </a>
                                </button>
                            @endif
                        @endforeach
                    @else
                        <button type="button" class="btn btn-primary  mx-1" data-toggle="modal"
                                data-target="#exampleModal">

                            <a href="{{route('Waybill.create')}}" class="btn btn-primary">
                                <i class="fe fe-plus mr-2"></i>@lang('waybill.add_new_waybill')
                            </a>
                        </button>
                    @endif
                </div>

                <div class="">
                    <form action="{{route('waybill-export') }}">
                        @if(request()->company_id)
                            @foreach(request()->company_id as $company_id)
                                <input type="hidden" name="company_id[]" value="{{ $company_id }}">
                            @endforeach
                        @endif
                        @if (request()->created_date_from && request()->created_date_to)
                            <input type="hidden" name="created_date_from" value="{{request()->created_date_from}}">
                            <input type="hidden" name="created_date_to" value="{{request()->created_date_to}}">
                        @endif
                        @if (request()->customers_id)
                            @foreach(request()->customers_id as $customer_id)
                                <input type="hidden" name="customers_id[]" value="{{$customer_id}}">
                            @endforeach
                        @endif
                        @if (request()->statuses_id)
                            @foreach(request()->statuses_id as $status_id)
                                <input type="hidden" name="statuses_id[]" value="{{$status_id}}">
                    @endforeach
                    @endif
                </div>

                <div class="">
                    <form action="{{route('waybill-export') }}">
                        @if(request()->company_id)
                            @foreach(request()->company_id as $company_id)
                                <input type="hidden" name="company_id[]" value="{{ $company_id }}">
                            @endforeach
                        @endif
                        @if (request()->created_date_from && request()->created_date_to)
                            <input type="hidden" name="created_date_from" value="{{request()->created_date_from}}">
                            <input type="hidden" name="created_date_to" value="{{request()->created_date_to}}">
                        @endif
                        @if (request()->customers_id)
                            @foreach(request()->customers_id as $customer_id)
                                <input type="hidden" name="customers_id[]" value="{{$customer_id}}">
                            @endforeach
                        @endif
                        @if (request()->statuses_id)
                            @foreach(request()->statuses_id as $status_id)
                                <input type="hidden" name="statuses_id[]" value="{{$status_id}}">
                            @endforeach
                        @endif

                        @if (request()->expected_date_from && request()->expected_date_to)
                            <input type="hidden" name="expected_date_from" value="{{request()->expected_date_from}}">
                            <input type="hidden" name="expected_date_to" value="{{request()->expected_date_to}}">
                        @endif

                        <a
                            href="{{config('app.telerik_server')}}?rpt=perfect/waybill_daily_report&from_date={{request()->created_date_from}}&to_date={{request()->created_date_to}}&lang=ar&skinName=bootstrap"
                            title="{{trans('PRINT')}}" class="btn btn-primary" id="showReport" target="_blank">
                            @lang('waybill.report_waybil_dt')
                        </a>
                        <a
                            href="{{config('app.telerik_server')}}?rpt=perfect/waybill_customer_report&&from_date={{request()->created_date_from}}&to_date={{request()->created_date_to}}&lang=ar&skinName=bootstrap"
                            title="{{trans('PRINT')}}" class="btn btn-primary" id="showReport" target="_blank">
                            @lang('waybill.report_customer')
                        </a>

                        <a
                            href="{{config('app.telerik_server')}}?rpt=perfect/waybill_truck_report&from_date={{request()->created_date_from}}&to_date={{request()->created_date_to}}&trucks_id={{implode(',',request()->input('trucks_id',[]))}}&lang=ar&skinName=bootstrap"
                            title="{{trans('PRINT')}}" class="btn btn-primary" id="showReport" target="_blank">
                            @lang('waybill.report_truck')
                        </a>

                        <a
                            href="{{config('app.telerik_server')}}?rpt=perfect/all_truck_waqoodi_1&from_date={{request()->created_date_from}}&to_date={{request()->created_date_to}}&trucks_id={{implode(',',request()->input('trucks_id',[]))}}&lang=ar&skinName=bootstrap"
                            title="{{trans('PRINT')}}" class="btn btn-primary" id="showReport" target="_blank">
                            @lang('waybill.profit_truck')
                        </a>

                        <button href="{{ route('waybill-export') }}" type="submit"
                                class="btn btn-primary">@lang('home.export')
                            <i class="fa fa-file-excel-o"></i></button>


                    </form>
                </div>

            </div>
        </div>


        <div class="card-body">


            <div class="table-responsive">

                <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                    <thead style="background-color: #ece5e7">
                    <tr class="red">
                        <th>@if(app()->getLocale()=='en')
                                @sortablelink('waybill_code','waybill code')
                            @else
                                @sortablelink('waybill_code','رقم البوليصه')
                            @endif
                        </th>
                        <th>@lang('waybill.company_name')</th>
                        <th>
                            @if(app()->getLocale()=='en')
                                @sortablelink('customer.customer_name_full_en','customer name')
                            @else
                                @sortablelink('customer.customer_name_full_ar','اسم العميل')
                            @endif
                        </th>
                        <th>  @if(app()->getLocale()=='en')
                                @sortablelink('waybill_ticket_no','waybill ticket no')
                            @else
                                @sortablelink('waybill_ticket_no','رقم التذكره')
                            @endif</th>

                        <th>@if(app()->getLocale()=='en')
                                @sortablelink('created_date','date')
                            @else
                                @sortablelink('created_date','التاريخ')
                            @endif</th>
                        <th>@if(app()->getLocale()=='en')
                                @sortablelink('truck_code','Truck')
                            @else
                                @sortablelink('truck_code','الشاحنه')
                            @endif</th>
                        <th>@lang('waybill.waybill_amount')</th>
                        <th> @lang('home.trip_id_naql')</th>
                        <th>@lang('waybill.waybill_status')</th>
                        <th colspan="4"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($way_pills as $way_pill)
                        <tr>
                            <td>
                                <a href="{{ route('Waybill.edit',$way_pill->waybill_id) }}"
                                   class="btn btn-primary btn-sm">
                                    {{ $way_pill->waybill_code }}
                                </a>
                            </td>
                            <td>{{app()->getLocale()=='ar' ? $way_pill->company->company_name_ar
                                : $way_pill->company->company_name_en}}</td>

                            <td>
                                @if($way_pill->customer)
                                    {{app()->getLocale()=='ar' ? $way_pill->customer->customer_name_full_ar
                                : $way_pill->customer->customer_name_full_en }}
                                @endif
                            </td>
                            <td>{{ $way_pill->waybill_ticket_no }}</td>

                            <td>{{ $way_pill->created_date }}</td>
                            <td style="color: blue;font-weight: bold">{{ $way_pill->truck ? $way_pill->truck->truck_code : '' }}</td>
                            {{--<td>{{ $way_pill->waybill_delivery_expected }}</td>--}}
                            <td>{{ $way_pill->waybill_total_amount }}</td>
                            <td>{{ $way_pill->trip_id }}</td>
                            <td>
                                <span class="tag tag-success">
                                @if($way_pill->status)
                                    {{app()->getLocale()=='ar' ?  $way_pill->status->system_code_name_ar :
                                 $way_pill->status->system_code_name_en }}
                                @endif
                            </td>
                            <td colspan="2">
                                {{--<a href="{{ url('bonds-add/capture/create?waybill_id='.$way_pill->waybill_id) }}"--}}
                                {{--class="btn btn-primary btn-sm">--}}
                                {{--@lang('home.add_bond')</a>--}}
                                @if(auth()->user()->user_type_id != 1)
                                    @foreach(session('job')->permissions as $job_permission)
                                        @if($job_permission->app_menu_id == 70 && $job_permission->permission_add)

                                            <a hidden href="{{ route('Waybill.edit',$way_pill->waybill_id) }}"
                                               class="btn btn-primary btn-sm">
                                                <i class="fa fa-edit"></i></a>

                                            <button type="button" class="btn btn-primary btn-sm"
                                                    onclick="printWaybill('{{$way_pill->waybill_id}}')">
                                                <i class="fa fa-print"></i>
                                            </button>

                                        @endif
                                    @endforeach
                                @else

                                    <a hidden href="{{ route('Waybill.edit',$way_pill->waybill_id) }}"
                                       class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i></a>



                                    <button type="button" class="btn btn-primary btn-sm"
                                            onclick="printWaybill('{{$way_pill->waybill_id}}')">
                                        <i class="fa fa-print"></i>
                                    </button>
                                @endif
                            </td>

                            <td>
                                @if($way_pill->http_status != 200)
                                    <button type="button" class="btn btn-primary"
                                            id="waybill{{$way_pill->waybill_id}}"
                                            onclick="createTrip1('{{$way_pill->waybill_id}}')">
                                        توثيق الحمولة
                                    </button>
                                @else
                                    تم توثيق الحمولة
                                @endif
                            </td>
                        </tr>
                    @endforeach


                    <tr>
                        <td colspan="5">@lang('home.total') : {{ $total }}</td>
                        <td colspan="4">@lang('home.total_vat') : {{ $total_vat }}</td>
                    </tr>
                    </tbody>

                </table>

            </div>

            <div class="row">
                <div class="col-md-6">
                    {{ $way_pills->appends($data)->links() }}
                </div>
            </div>

            <tr>
                <td colspan="5">@lang('home.total') : {{ $total_all }}</td>
                <td colspan="4">@lang('home.total_vat') : {{ $total_vat_all }}</td>
            </tr>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="{{asset('assets/plugins/toastr/toastr.min.js')}}"></script>
    <script>


    </script>
    <script>
        new Vue({
            el: '#app',
            data: {

                show_model: false,
                error_message: '',
                confirm_message: '',
                success_message: ''
            },
            methods: {}
        });

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
                } else {
                    toastr.warning(data.msg);
                }
            });
        }

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
                if (data.success) {
                    toastr.success(data.msg);
                    $('#waybill' + tripId).removeAttr('disabled')
                    location.reload();
                } else {
                    $('#waybill' + tripId).removeAttr('disabled')
                    toastr.warning(data.msg);
                }
            });
        }

        //
        // function stopSubmit(id) {
        //     alert(id);
        //
        // }
    </script>
@endsection


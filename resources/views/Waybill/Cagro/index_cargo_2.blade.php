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
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6>بوليصة نقل بضائع</h6>
                                <h3 class="pt-2">
                                    <span class="counter">{{$query1+ $query0 + $query3}} </span>
                                </h3>
                                <span><span class="text-danger mr-2"><i
                                                class="fa fa-car"></i> </span>  </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6>امر تحميل </h6>
                                <h3 class="pt-2">
                                    <span class="counter">{{$query1}}</span>
                                </h3>
                                <span><span class="text-danger mr-2">
                                                <i class="fa fa-car"></i> {{$query1_p}} %</span>  </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
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

                    <div class="col-lg-3 col-md-6">
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

            </div>

            <form action="">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            {{--الشركات--}}
                            <div class="col-md-3">
                                <label>@lang('home.companies')</label>
                                <select class="selectpicker" multiple data-live-search="true"
                                        name="company_id[]" data-actions-box="true">
                                    @foreach($companies as $company_s)
                                        <option value="{{$company->company_id}}"
                                                @if(!request()->company_id) @if($company->company_id == $company_s->company_id)
                                                selected @endif @endif
                                                @if(request()->company_id) @foreach(request()->company_id as
                                                     $company_id) @if($company_s->company_id == $company_id) selected
                                                @endif @endforeach @endif>
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
                            <div class="col-md-3">
                                {{-- customers  --}}
                                <label>@lang('home.customers')</label>
                                <select class="selectpicker" multiple data-live-search="true"
                                        name="customers_id[]" data-actions-box="true">
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->customer_id }}"
                                                @if(!request()->customers_id) selected @endif
                                                @if(request()->customers_id) @foreach(request()->customers_id as
                                                     $customer_id) @if($customer->customer_id == $customer_id) selected @endif @endforeach @endif>
                                            {{app()->getLocale()=='ar' ? $customer->customer_name_full_ar
                                         : $customer->customer_name_full_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{--الموظف--}}
                            <div class="col-md-3">
                                {{-- customers  --}}
                                <label>@lang('home.employee')</label>
                                <select class="selectpicker" multiple data-live-search="true"
                                        name="employee_id[]" data-actions-box="true">
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->user_id }}"
                                                @if(!request()->employee_id) selected @endif
                                                @if(request()->employee_id) @foreach(request()->employee_id as
                                                     $employee_id) @if($employee->user_id == $employee_id) selected @endif @endforeach @endif>
                                            {{app()->getLocale()=='ar' ? $employee->user_name_ar
                                         : $employee->user_name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                {{-- حاللات البوليصه  --}}
                                <label>@lang('home.statuses')</label>
                                <select class="selectpicker" multiple data-live-search="true"
                                        name="statuses_id[]" data-actions-box="true">
                                    @foreach($sys_codes_waybill_status as $status)
                                        <option value="{{ $status->system_code_id }}"
                                                @if(request()->statuses_id) @foreach(request()->statuses_id as
                                                     $status_id) @if($status->system_code_id == $status_id)
                                                selected @endif @endforeach @endif
                                        >
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
                            <div class="col-md-2">
                                <label>@lang('waybill.waybill_no')</label>
                                <input type="text" class="form-control" name="waybill_waybill_no"
                                       @if(request()->waybill_waybill_no) value="{{request()->waybill_waybill_no}}"
                                        @endif>
                            </div>
                            <div class="col-md-2">
                                <label>@lang('waybill.waybill_waybill_no')</label>
                                <input type="text" class="form-control" name="waybill_ref"
                                       @if(request()->waybill_ref) value="{{request()->waybill_ref}}" @endif>
                            </div>
                            <div class="col-md-3">
                                {{-- الشاحنات --}}
                                <label>@lang('waybill.waybill_truck')</label>
                                <select class="selectpicker" multiple data-live-search="true"
                                        name="trucks_id[]" data-actions-box="true">
                                    @foreach($trucks as $truck)
                                        <option value="{{ $truck->truck_id }}"
                                                @if(!request()->trucks_id) selected @endif
                                                @if(request()->trucks_id) @foreach(request()->trucks_id as
                                                     $trucks_id) @if($truck->truck_id == $trucks_id)
                                                selected @endif @endforeach @endif>
                                            {{  $truck->truck_code }} -- {{$truck->truck_name }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-md-1">
                                <button class="btn btn-primary mt-3" style="min-width:100px"
                                        type="submit">@lang('home.search')
                                    <i class="fa fa-search fa-fw"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="row mb-12">
            <div class="col col-md-2 my-1">
                @if(auth()->user()->user_type_id != 1)
                    @foreach(session('job')->permissions as $job_permission)
                        @if($job_permission->app_menu_id == 90 && $job_permission->permission_add)
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#exampleModal">

                                <a href="{{route('Waybillcargo2.create')}}" class="btn btn-primary">
                                    <i class="fe fe-plus mr-2"></i>@lang('waybill.add_new_waybill')
                                </a>
                            </button>
                        @endif
                    @endforeach

                @else
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#exampleModal">

                        <a href="{{route('Waybillcargo2.create')}}" class="btn btn-primary">
                            <i class="fe fe-plus mr-2"></i>@lang('waybill.add_new_waybill')
                        </a>
                    </button>
                @endif
            </div>
            <div class="col col-md-2 my-1">
                @if(auth()->user()->user_type_id != 1)
                    @foreach(session('job')->permissions as $job_permission)
                        @if($job_permission->app_menu_id == 90 && $job_permission->permission_add)
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#exampleModal">

                                <a href="{{route('Waybillcargo2.createrent')}}" class="btn btn-primary">
                                    <i class="fe fe-plus mr-2"></i>@lang('waybill.add_new_waybill_rent')
                                </a>
                            </button>
                        @endif
                    @endforeach

                @else
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#exampleModal">

                        <a href="{{route('Waybillcargo2.createrent')}}" class="btn btn-primary">
                            <i class="fe fe-plus mr-2"></i>@lang('waybill.add_new_waybill_rent')
                        </a>
                    </button>
                @endif


            </div>

            <div class="col-md-3">

            </div>
            <div class="col-md-5">
                <form action="{{route('waybill-cargo2-export') }}">
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




                    @foreach($waybill_cargo_daily_report as $waybill_cargo_daily_reports)
                        <a
                                href="{{config('app.telerik_server')}}?rpt={{$waybill_cargo_daily_reports->report_url}}&from_date={{request()->created_date_from}}&to_date={{request()->created_date_to}}&customer_id={{implode(',',request()->input('customers_id',[]))}}&trucks_id={{implode(',',request()->input('trucks_id',[]))}}&employee_id={{implode(',',request()->input('employee_id',[]))}}&lang=ar&skinName=bootstrap"
                                title="{{trans('PRINT')}}" class="btn btn-primary  m-1" id="showReport" target="_blank">
                            @lang('waybill.report_waybil_dt')
                        </a>
                    @endforeach

                    @foreach($waybill_cargo_customer_report as $waybill_cargo_customer_reports)
                        <a
                                href="{{config('app.telerik_server')}}?rpt={{$waybill_cargo_customer_reports->report_url}}&from_date={{request()->created_date_from}}&to_date={{request()->created_date_to}}&lang=ar&skinName=bootstrap"
                                title="{{trans('PRINT')}}" class="btn btn-primary m-1" id="showReport" target="_blank">
                            @lang('waybill.report_customer')
                        </a>
                    @endforeach

                    @foreach($waybill_cargo_truck_report as $waybill_cargo_truck_reports)
                        <a
                                href="{{config('app.telerik_server')}}?rpt={{$waybill_cargo_truck_reports->report_url}}&from_date={{request()->created_date_from}}&to_date={{request()->created_date_to}}&trucks_id={{implode(',',request()->input('trucks_id',[]))}}&lang=ar&skinName=bootstrap"
                                title="{{trans('PRINT')}}" class="btn btn-primary m-1" id="showReport" target="_blank">
                            @lang('waybill.report_truck')
                        </a>
                    @endforeach

                    <a
                            href="{{config('app.telerik_server')}}?rpt=perfect/all_truck_waqoodi&from_date={{request()->created_date_from}}&to_date={{request()->created_date_to}}&trucks_id={{implode(',',request()->input('trucks_id',[]))}}&lang=ar&skinName=bootstrap"
                            title="{{trans('PRINT')}}" class="btn btn-primary" id="showReport" target="_blank">
                        @lang('waybill.profit_truck')
                    </a>


                    <button href="{{ route('waybill-cargo2-export') }}" type="submit"
                            class="btn btn-primary m-1">@lang('home.export')
                        <i class="fa fa-file-excel-o"></i></button>


                </form>
            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                    <thead style="background-color: #ece5e7">
                    <tr class="red">
                        <th> @if(app()->getLocale()=='en') @sortablelink('waybill_ticket_no','waybill ticket no')
                            @else @sortablelink('waybill_ticket_no','رقم التذكره') @endif
                        </th>
                        <th>@if(app()->getLocale()=='en')
                                @sortablelink('waybill_code','waybill code') @else
                                @sortablelink('waybill_code','امر التحميل ') @endif</th>
                        <th>   @if(app()->getLocale()=='en')
                                @sortablelink('customer.customer_name_full_en','customer name')
                            @else
                                @sortablelink('customer.customer_name_full_ar','اسم العميل')
                            @endif</th>
                        <th>@if(app()->getLocale()=='en') @sortablelink('created_date','date')
                            @else @sortablelink('created_date','التاريخ') @endif</th>
                        <th>@if(app()->getLocale()=='en') @sortablelink('truck_code','Truck')
                            @else @sortablelink('truck_code','الشاحنه') @endif</th>

                        <th>@lang('waybill.waybill_amount')</th>
                        <th>@lang('waybill.waybill_vat_amount')</th>
                        <th>@lang('waybill.waybill_total')</th>

                        <th>@lang('waybill.waybill_fees_road')</th>
                        <th>@lang('invoice.invoice_no')</th>
                        <th>@lang('waybill.waybill_status')</th>
                        <th></th>
                      
                        <th> الديزل</th>
                        <th> التوثيق</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($way_pills as $way_pill)
                        <tr>
                            <td>
                                <a href="{{ route('Waybillcargo2.edit',$way_pill->waybill_id) }}"
                                   class="btn btn-primary btn-sm">
                                    {{ $way_pill->waybill_code }}
                                </a>
                            </td>
                            <td>{{ $way_pill->waybill_ticket_no }}</td>


                            <td style="font-size: 10px ;font-weight: bold">>
                                @if($way_pill->customer)
                                    {{app()->getLocale()=='ar' ? $way_pill->customer->customer_name_full_ar
                                : $way_pill->customer->customer_name_full_en }}
                                @endif
                            </td>

                            <td>{{ date('d-m-y', strtotime($way_pill->waybill_load_date))  }}</td>
                            <td style="color: blue;font-weight: bold">{{ $way_pill->truck ? $way_pill->truck->truck_code : '' }}</td>
                            <td> {{ number_format($way_pill->waybill_total_amount  - $way_pill->waybill_vat_amount ,2) }} </td>
                            <td>{{  number_format($way_pill->waybill_vat_amount,2) }}</td>
                            <td style="color: blue">{{  number_format($way_pill->waybill_total_amount,2) }}</td>

                            <td style="color: red">{{  number_format($way_pill->waybill_fees_total,2) }}</td>

                            <td style="font-size: 10px ;font-weight: bold">

                                @if($way_pill->invoiceno)

                                    <a href="{{config('app.telerik_server')}}?rpt={{$way_pill->report_url_cargo_smal_dt->report_url}}&id={{$way_pill->invoiceno->invoice_id}}&lang=ar&skinName=bootstrap"
                                       class=" btn btn-primary btn-sm btn-info"
                                       style="font-size: 10px ;font-weight: bold" target="_blank">


                                        {{$way_pill->invoiceno->invoice_no}}
                                    </a>

                                @else
                                    لا يوجد

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

                            <td>

                                <a href="{{config('app.telerik_server')}}?rpt={{$way_pill->report_url_cargo_print->report_url}}&id={{$way_pill->waybill_id}}&lang=ar&skinName=bootstrap"
                                   class="btn btn-primary btn-sm" target="_blank">
                                    <i class="fa fa-print"></i>
                                </a>
                                <a href="{{config('app.telerik_server')}}?rpt={{$way_pill->report_url_cargo_print_rent->report_url}}&id={{$way_pill->waybill_id}}&lang=ar&skinName=bootstrap"
                                   class="btn btn-primary btn-sm" target="_blank">
                                    <i class="fa fa-copy"></i>
                                </a>
                                <a hidden href="{{ route('Waybillcargo2.edit',$way_pill->waybill_id) }}"
                                   class="btn btn-primary btn-sm">
                                    <i class="fa fa-edit"></i></a>
                                @if($way_pill->invoiceno)
                                @else
                                    @if(auth()->user()->user_type_id == 1)
                                    

                                        <form action="{{route('Waybillcargo2.delete',$way_pill->waybill_id)}}"
                                              method="post" style="display: inline-block"
                                              id="form{{$way_pill->waybill_id}}">
                                            @csrf
                                            @method('delete')
                                            <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    id="submit{{$way_pill->waybill_id}}"
                                                    onclick="deleteEmp('{{$way_pill->waybill_id}}')"><i
                                                        class="fa fa-trash-o"></i>
                                            </button>
                                        </form>
                                    @endif

                                    

                                    @if(auth()->user()->user_type_id != 1)

                                        @foreach(session('job')->permissions as $job_permission)
                                            @if($job_permission->app_menu_id == 90 && $job_permission->permission_delete)
                                                <form action="{{route('Waybillcargo2.delete',$way_pill->waybill_id)}}"
                                                      method="post" style="display: inline-block"
                                                      id="form{{$way_pill->waybill_id}}">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="button" id="submit{{$way_pill->waybill_id}}"
                                                            onclick="deleteEmp('{{$way_pill->waybill_id}}')"
                                                            class="btn btn-danger btn-sm"><i
                                                                class="fa fa-trash-o"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endforeach
                                    @endif
                                @endif
                                  
                               
                            </td>
                            <td >
                            <form action="">
                                            @csrf
                                            <button type="button" 
                                                    onclick="petroRquest('{{$way_pill->waybill_id}}')"
                                                    class="btn btn-success btn-sm">
                                                        
                                                    <i class="fa fa-clipboard"></i>
                                            </button>
                                        </form>

                            </td>

                            <td>
                                @if($way_pill->http_status != 200)
                                    <button type="button" class="btn btn-primary"
                                            id="waybill{{$way_pill->waybill_id}}"
                                            onclick="createTrip1('{{$way_pill->waybill_id}}')">
                                        توثيق الحمولة
                                    </button>
                                @else
                                    <button type="button" class="btn btn-primary btn-sm"
                                            onclick="printWaybill('{{$way_pill->waybill_id}}')">
                                        طباعه التوثيق
                                    </button>
                                @endif
                            </td>

                        </tr>
                    @endforeach

                    <tr>
                        <td colspan="5">@lang('home.total') : {{ $total }}</td>
                        <td colspan="7">@lang('home.total_vat') : {{ $total_vat }}</td>
                    </tr>
                    </tbody>

                    <div class="row">
                        <div class="col-md-8">
                            {{ $way_pills->appends($data)->links() }}
                        </div>
                    </div>


                </table>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script>

        function printWaybill(waybill_id) {
            //window.open("https://www.google.com");
            url = '{{ route('api.Waybillcargo2.printWaybill') }}';
            $.ajax({
                type: 'GET',
                url: url,
                data:
                    {
                        "_token": "{{ csrf_token() }}",
                        'id': waybill_id,
                    },

            }).done(function (data) {
                console.log(data)
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

        function createTrip1(tripId) {
            $('#waybill' + tripId).prop('disabled', 'true')


            url = '{{ route('api.Waybillcargo2.createTrip') }}';
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
                }
                else {
                    $('#waybill' + tripId).removeAttr('disabled')
                    toastr.warning(data.msg);
                }
            });
        }

        function deleteEmp(id) {

            var proceed = confirm("هل انت متاكد من الحذف");
            if (proceed) {
                $('#form' + id).submit();
            }
        }
        function petroRquest(id)
        {
            url = '{{ route('waybill.petro-app.send.request') }}'
            $.ajax({
                type: 'POST',
                url : url,
                data:{
                    _token : "{{ csrf_token() }}",
                    waybill_id : id,
                    action : 'insert'
                },
                beforeSend: function () {
                    //App.startPageLoading({animate: true});
                }
                
            }).done(function(data){
                if(data.success==true)
                {
                    toastr.success(data.msg);
                }
                else
                {
                    toastr.warning(data.msg);
                }
            });
        }

    </script>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/multiple-select@1.3.1/dist/multiple-select.min.js"></script>
    <script src="{{asset('jquery-datetime\jquery.datetimepicker.full.min.js')}}"></script>

    <script>

        $(function () {
            $('.datepicker').datetimepicker({
                format: 'Y/m/d H:i',
            });


            // var select_report = $('#select_report').find(':selected');
            //   var fromSelect = $('#from_date');
            //  var toSelect = $('#to_date');
            //   var href = originalHref;
            // var employee = $('#employee');
            // var branch = $('#branchSelect');
            //href = href.replace('$R$',$('#select_report').val());
            //href = href.replace('$FM$',fromSelect.val());
            //href = href.replace('$TD$',toSelect.val());
            href = href.replace('$BR$', $((request()
        ->
            customers_id
        )).
            val().join(',')
        )
            ;
            //  href = href.replace('$USR$',$('#user').val().join(','));
            // href = href.replace('$contractType$',$('#contractType').val().join(','));
            // href = href.replace('$TY$',toSelect.data('y'));
            // href = href.replace('$FILTER$',"branch="+$('#branchSelect').val().join(','));
            showReportBtn.prop('href', href);
            return true;

        });

        // });
    </script>
@endpush

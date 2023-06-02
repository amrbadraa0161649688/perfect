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

    @php
        if(session('job')){
          $job_p=session('job')->permissions()->where('app_menu_id', '=', 104)->first();
        }

    @endphp

    <div class="section-body mt-3">
        <div class="container-fluid">

            @include('Includes.form-errors')

            <div class="row clearfix">
                <div class="col-lg-2 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6>اجمالي عدد الرحلات </h6>
                            <h3 class="pt-2"><span class="counter">{{$ready_trip + $go_trip}}</span></h3>
                            {{--<span><span class="text-danger mr-2"><i--}}
                            {{--class="fa fa-yelp"></i> 100 % </span> رحلة </span>--}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6>رحلات جاهزة </h6>
                            <h3 class="pt-2"><span class="counter">{{$ready_trip}}</span></h3>
                            {{--<span><span class="text-success mr-2"><i--}}
                            {{--class="fa fa-thumbs-o-up"></i>--}}
                            {{--% </span> رحلة</span>--}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6> انطلقت من</h6>
                            <h3 class="pt-2"><span class="counter">{{$go_trip}}</span></h3>
                            {{--<span><span class="text-success mr-2"><i--}}
                            {{--class="fa fa-paper-plane-o"></i> --}}
                            {{--% </span> رحلة</span>--}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6> انطلقت الي </h6>
                            <h3 class="pt-2"><span class="counter">{{$go_trip_to}}</span></h3>
                            {{--<span><span class="text-danger mr-2"><i--}}
                            {{--class="fa fa-share-alt"></i>--}}
                            {{--% </span> رحلة</span>--}}
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <div class="card" style="background: #B0352F;">
                        <div class="card-body">
                            <h6  style="color: white"> شاحنات جاهزه بدون رحلات</h6>
                            <h3  class="pt-2"  style="color: white"><span class="counter">{{ $ready_truck - ($ready_trip )}}</span>
                            
                        </h3>
 
                        </div>
                    </div>

                </div>

                <div class="col-lg-2 col-md-6">
                    <div class="card" style="background: #B0352F;">
                        <div class="card-body">
                            <h6  style="color: white">اجمالي الشاحنات</h6>
                            <h3  class="pt-2"  style="color: white"><span class="counter">{{ $ready_truck  + $go_trip}}</span> </h3>

                           
                        </div>
                    </div>

            </div>
        </div>
    </div>


    <div class="section-body mt-3" id="app">
        <div class="container-fluid">

            <div class="card">
                <div class="card-body">
                    {{--{{dd(session('branch'))}}--}}
                    @include('Includes.flash-messages')
                    {{--الفلاتر--}}
                    <form action="">
                        <div class="row">

                            {{--الشركة الرئيسيه --}}
                            <div hidden class="col-md-6">
                            </div>


                            {{--الفروع--}}
                            <div class="col-md-4">
                                <label>@lang('home.branches')</label>
                                <select class="selectpicker" multiple data-live-search="true"
                                        name="branch_id[]" required data-actions-box="true">
                                    @foreach($company->branches as $branch)
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


                            {{--رقم الرحله--}}

                            <div class="col-md-4">
                                <label>@lang('trucks.')</label>
                                <input type="text" class="form-control" name="trip_hd_code"
                                       @if(request()->trip_hd_code) value="{{request()->trip_hd_code}}" @endif>
                            </div>


                            {{-- حالة الرحلة --}}
                            <div class="col-md-4">
                                {{-- trips--}}
                                <label>@lang('trucks.trip_status')</label>
                                <select class="selectpicker" multiple data-live-search="true"
                                        name="trip_hd_status[]">
                                    @foreach($sys_codes_status as $trip_status)
                                        <option value="{{ $trip_status->system_code_id }}"
                                                @if(request()->trip_hd_status)
                                                @foreach(request()->trip_hd_status as $trip_status_2)
                                                @if($trip_status->system_code_id == $trip_status_2) selected @endif
                                                @endforeach
                                                @endif> {{app()->getLocale()=='ar'
                                            ? $trip_status->system_code_name_ar
                                            : $trip_status->system_code_name_en }} </option>
                                    @endforeach
                                </select>
                            </div>

                            {{--من فرع--}}
                            <div class="col-md-2">
                                {{-- loc_from  --}}
                                <label>@lang('home.from')</label>
                                <select class="selectpicker" multiple data-live-search="true"
                                        name="loc_from[]" data-actions-box="true">
                                    @foreach($sys_codes_location as $loc_from)
                                        <option value="{{ $loc_from->system_code_id }}"
                                                @if(request()->loc_from)
                                                @foreach(request()->loc_from as $loc_from_1)
                                                @if($loc_from->system_code_id == $loc_from_1) selected @endif
                                                @endforeach
                                                @endif>
                                            {{app()->getLocale()=='ar'
                                            ? $loc_from->system_code_name_ar
                                            : $loc_from->system_code_name_en }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{--الى فرع--}}
                            <div class="col-md-2">
                                {{-- loc_  to--}}
                                <label>@lang('home.to')</label>
                                <select class="selectpicker" multiple data-live-search="true"
                                        name="loc_to[]" data-actions-box="true">
                                    @foreach($sys_codes_location as $loc_to)
                                        <option value="{{ $loc_to->system_code_id }}"
                                                @if(request()->loc_to)
                                                @foreach(request()->loc_to as $loc_to_1)
                                                @if($loc_to->system_code_id == $loc_to_1) selected @endif
                                                @endforeach
                                                @endif>
                                            {{app()->getLocale()=='ar'
                                            ? $loc_to->system_code_name_ar
                                            : $loc_to->system_code_name_en }}</option>
                                    @endforeach
                                </select>
                            </div>

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

                            <div class="col-md-2">
                                <label>@lang('home.truck')</label>
                                <input type="text" class="form-control" name="truck_code"
                                       @if(request()->truck_code) value="{{request()->truck_code}}" @endif>
                            </div>

                            <div class="col-md-2">

                                <button type="submit" class="btn btn-primary mt-4">@lang('home.search')</button>

                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                @if(session('job') && $job_p->permission_add == 1 || auth()->user()->user_type_id == 1)
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary mb-3">

                            <a href="{{route('Trips.create')}}" class="btn btn-primary">
                                <i class="fe fe-plus mr-2"></i>@lang('home.add_trips')
                            </a>


                        </button>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="card bline" style="color:red">
                </div>
            </div>
            <div class="card">

                <div class="alert alert-danger" v-if="error_message">
                    @{{ error_message }}
                </div>

                <div class="alert alert-success" v-if="success_message">
                    @{{ success_message }}
                </div>
                <div class="table-responsive">

                    <table class="table table-hover table-vcenter table-bordered table_custom text-nowrap spacing5 text-nowrap mb-0"
                           style="overflow-x: initial;">
                        <thead>
                        <tr class="red"
                            style="background-color: #ece5e7 ; font-size: 16px ; font-style: inherit">
                            <th>@lang('home.trip_number')</th>
                            <th>@lang('home.branch')</th>
                            <th>@lang('home.trip_start_date')</th>
                            <th>@lang('home.truck')</th>
                            <th>@lang('home.driver_name')</th>
                            <th style="width: 50%">@lang('home.trip_line')</th>
                            <th>@lang('home.lunch_date')</th>
                            <th>@lang('home.arrival_date')</th>
                            <th>@lang('home.status')</th>
                            <th style="color: red">@lang('home.waybills_number')</th>
                            @if( isset($job_p) && $job_p->permission_add == 1 || auth()->user()->user_type_id == 1)
                                <th>@lang('home.edit')</th>
                            @endif
                            <th>التوثيق</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{--{{dd(session('job')->permissions())}}--}}
                        @if( isset($job_p) && $job_p->permission_view == 1 || auth()->user()->user_type_id == 1)
                            @foreach($trips as $trip)
                                <tr>
                                    <td>
                                        <a href="{{ route('Trips.edit',$trip->trip_hd_id) }}"
                                           class="btn btn-primary btn-sm">
                                            {{ $trip->trip_hd_code }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($trip->tripLine->locFrom->branch)
                                            {{app()->getLocale() == 'ar'
                                            ? $trip->tripLine->locFrom->branch->branch_name_ar
                                            : $trip->tripLine->locFrom->branch->branch_name_en}}
                                        @endif
                                    </td>

                                    <td style="font-size: 14px ;font-weight: bold">{{ date('d-m-y H:I', strtotime($trip->trip_hd_start_date)) }}</td>

                                    <td style="font-size: 14px ;font-weight: bold">@if($trip->truck){{$trip->truck->truck_name}}@endif</td>

                                    <td style="font-size: 14px ;font-weight: bold">@if($trip->driver) {{app()->getLocale() == 'ar'
                                        ? $trip->driver->emp_name_full_ar
                                        : $trip->driver->emp_name_full_en}} @endif</td>

                                    <td>
                                        {{ $trip->tripLine ? $trip->tripLine->trip_line_desc : '' }}
                                    </td>

                                    <td style="font-size: 14px ;font-weight: bold">@if($trip->trip_hd_started_date){{ date('d-m-y H:I', strtotime($trip->trip_hd_started_date)) }}@endif</td>

                                    <td>@if($trip->trip_hd_ended_date){{ date('d-m-y H:I', strtotime($trip->trip_hd_ended_date)) }}@endif</td>

                                    <td>
                                <span class="tag tag-success"> 
                                                @if($trip->status)
                                        {{ app()->getLocale() == 'ar'
                                   ? $trip->status->system_code_name_ar
                                   : $trip->status->system_code_name_en}}
                                    @endif
                                                </span>

                                    </td>

                                    <td style="font-size: 16px ;color: red">{{$trip->tripdts->count()}}
                                        <a href="{{config('app.telerik_server')}}?rpt={{$report_url_trips}}&trip_id={{$trip->trip_hd_id}}&lang=ar&skinName=bootstrap"
                                           class="btn btn-primary"
                                           style="display: inline-block; !important;" id="print"
                                           target="_blank">
                                            @lang('home.print')</a>
                                    </td>

                                    <td>
                                        @if(isset($job_p) && $job_p->permission_update == 1 || auth()->user()->user_type_id == 1)

                                            @if($trip->status)
                                                @if($trip->status->system_code == 39001 || $trip->status->system_code == 39002  || $trip->status->system_code == 39003)

                                                    @if(\App\Models\SystemCode::where('system_code_id',$trip->trip_hd_status)->first()->system_code == 39001)
                                                        {{--جاهزه تتحدث لانطلقت--}}
                                                        <button type="button" class="btn btn-primary"
                                                                data-toggle="modal"
                                                                data-target="#myModal{{$trip->trip_hd_id}}"
                                                                id="launch{{$trip->trip_hd_id}}"
                                                                onclick="disableLaunchedButton({{$trip->trip_hd_id}})">
                                                            @lang('home.launched')
                                                        </button>

                                                    @elseif((\App\Models\SystemCode::where('system_code_id',$trip->trip_hd_status)->first()->system_code == 39002
                                                    || \App\Models\SystemCode::where('system_code_id',$trip->trip_hd_status)->first()->system_code == 39003 )
                                                    && $trip->trip_loc_transit != $trip->tripLine->trip_line_loc_to)

                                                        <button type="button" class="btn btn-primary"
                                                                data-toggle="modal"
                                                                data-target="#myModal_a{{$trip->trip_hd_id}}"
                                                                id="arrived{{$trip->trip_hd_id}}"
                                                                onclick="disableArrivedButton({{$trip->trip_hd_id}})">
                                                            @lang('home.arrived')
                                                        </button>
                                                    @elseif($trip->trip_loc_transit == $trip->tripLine->trip_line_loc_to)
                                                        <button type="button" class="btn btn-primary"
                                                                onclick="disableEndedButton({{$trip->trip_hd_id}})"
                                                                data-toggle="modal" id="ended{{$trip->trip_hd_id}}"
                                                                data-target="#myModal_e{{$trip->trip_hd_id}}">
                                                            @lang('home.ended')
                                                        </button>
                                                    @endif
                                                @else
                                                    {{app()->getLocale() == 'ar' ? $trip->status->system_code_name_ar : $trip->status->system_code_name_en}}
                                                @endif
                                            @endif

                                        @endif
                                    </td>
                                    <td>
                                        @if($trip->http_status != 200)
                                            <button type="button" id="trip{{$trip->trip_hd_id}}" class="btn btn-primary"
                                                    onclick="createTrip('{{$trip->trip_hd_id}}')">
                                                توثيق الحموله
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-primary mr-1 ml-1"
                                                    onclick="printTrip('{{ $trip->trip_hd_id }}')">
                                                تم التوثيق طباعه
                                            </button>
                                        @endif
                                    </td>
                                </tr>


                                {{--انطلقت--}}
                                <div class="modal fade" id="myModal{{$trip->trip_hd_id }}"
                                     role="dialog">

                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header"
                                                 style="background-color: firebrick">
                                                <button type="button" class="close"
                                                        data-dismiss="modal">
                                                    &times;
                                                </button>
                                                <h3 class="modal-title" align="center"
                                                    style="color: whitesmoke;"><i
                                                            class="fa fa-warning"
                                                            style="color: yellow;"></i> @lang('home.confirm_update')

                                                </h3>
                                            </div>

                                            <div class="modal-body">

                                                @if(auth()->user()->additionRols()->where('rols_id',10)->first() ||
                                                session('branch')['branch_id'] == $trip->tripLine->locFrom->branch_id || auth()->user()->user_type_id == 1)

                                                    <div class="form-group">
                                                        <input type="hidden" name="trip_hd_id"
                                                               value="{{ $trip->trip_hd_id}}">
                                                    </div>

                                                    <div class="form-group">
                                                        <p>هل انت متاكد من انطلاق الرحله رقم</p>
                                                        {{ $trip->trip_hd_code }}

                                                    </div>
                                                    <button class="btn btn-primary mr-2 ml-2" type="button"
                                                            @click="updateStatus({!! $trip->trip_hd_id !!})"
                                                            :disabled="disable_status">
                                                        @lang('home.save')</button>
                                                    <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">@lang('home.no')</button>

                                                @else
                                                    <div class="form-group">
                                                        <p>ليس لديك صلاحيه لعمل انطلاق للرحله لان فرع الانطلاق مختلف
                                                            عن
                                                            الفرع الحالي</p>
                                                    </div>
                                                @endif

                                            </div>

                                        </div>
                                    </div>

                                </div>

                                {{--في الطريق--}}
                                <div class="modal fade" id="myModal_a{{$trip->trip_hd_id }}"
                                     role="dialog">

                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header"
                                                 style="background-color: firebrick">
                                                <button type="button" class="close"
                                                        data-dismiss="modal">
                                                    &times;
                                                </button>
                                                <h3 class="modal-title" align="center"
                                                    style="color: whitesmoke;"><i
                                                            class="fa fa-warning"
                                                            style="color: yellow;"></i> @lang('home.confirm_update')

                                                </h3>
                                            </div>

                                            @if(session('branch')['branch_id'] == $trip->tripLine->locTo->branch_id || auth()->user()->user_type_id == 1 || auth()->user()->additionRols()->where('rols_id',11)->first())

                                                <div class="modal-body">

                                                    <div class="form-group">

                                                        <input type="hidden" name="trip_hd_id"
                                                               value="{{ $trip->trip_hd_id}}">
                                                    </div>

                                                    <div class="form-group">
                                                        <span>هل انت متاكد من وصول الرحله رقم</span>
                                                        <span> {{ $trip->trip_hd_code }}</span>
                                                        <span>الي فرع </span>
                                                        <span>{{$trip->loc_transit ? $trip->loc_transit->system_code_name_ar :'' }}</span>

                                                    </div>

                                                    <button class="btn btn-primary mr-2 ml-2" type="button"
                                                            @click="updateStatus({!! $trip->trip_hd_id !!})"
                                                            :disabled="disable_status">
                                                        @lang('home.save')</button>
                                                    <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">@lang('home.no')</button>


                                                </div>
                                            @else
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <p>ليس لديك صلاحيه لعمل وصول للرحله لان فرع الوصول النهائي مختلف
                                                            عن
                                                            الفرع الحالي</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                {{-- انتهت--}}
                                <div class="modal fade" id="myModal_e{{$trip->trip_hd_id }}"
                                     role="dialog">

                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header"
                                                 style="background-color: firebrick">
                                                <button type="button" class="close"
                                                        data-dismiss="modal">
                                                    &times;
                                                </button>
                                                <h3 class="modal-title" align="center"
                                                    style="color: whitesmoke;"><i
                                                            class="fa fa-warning"
                                                            style="color: yellow;"></i> @lang('home.confirm_update')

                                                </h3>
                                            </div>
                                            @if(session('branch')['branch_id'] == $trip->tripLine->locTo->branch_id || auth()->user()->user_type_id == 1 || auth()->user()->additionRols()->where('rols_id',11)->first())
                                                <div class="modal-body">

                                                    <div class="form-group">

                                                        <input type="hidden" name="trip_hd_id"
                                                               value="{{ $trip->trip_hd_id}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <span>هل انت متاكد من انتهاء الرحله رقم</span>
                                                        <span> {{ $trip->trip_hd_code }}</span>
                                                    </div>

                                                    <button class="btn btn-primary mr-2 ml-2" type="button"
                                                            @click="updateStatus({!! $trip->trip_hd_id !!})"
                                                            :disabled="disable_status">
                                                        @lang('home.save')</button>
                                                    <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">@lang('home.no')</button>


                                                </div>
                                            @else
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <p>ليس لديك صلاحيه لعمل انتهاء للرحله لان فرع الوصول النهائي
                                                            مختلف
                                                            عن
                                                            الفرع الحالي</p>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>

                                </div>

                            @endforeach
                        @else
                            ليس لديك صلاحيه
                        @endif
                        </tbody>
                    </table>


                    {{ $trips->appends($data)->links() }}
                </div>
            </div>


        </div>
    </div>

@endsection

@section('scripts')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script>

        function disableLaunchedButton(id) {
            $('#launch' + id).attr('disabled', true)
            $('#myModal' + id).modal('show')
        }

        function disableArrivedButton(id) {
            $('#arrived' + id).attr('disabled', true)
            $('#myModal_a' + id).modal('show')
        }

        function disableEndedButton(id) {
            $('#ended' + id).attr('disabled', true)
            $('#myModal_e' + id).modal('show')
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="{{asset('assets/plugins/toastr/toastr.min.js')}}"></script>
    <script>
        new Vue({
            el: '#app',
            data: {

                show_model: false,
                error_message: '',
                confirm_message: '',
                success_message: '',
                disable_status: false
            },
            methods: {
                updateStatus(id) {
                    this.show_model = false
                    this.confirm_message = ''
                    this.disable_status = true
                    this.error_message = ''
                    $.ajax({
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            trip_hd_id: id,
                        },
                        url: '{{ route("Trips.updateStatus") }}'
                    }).then(response => {
                        $('#myModal' + id).modal('hide')
                        $('#myModal_a' + id).modal('hide')
                        $('#myModal_e' + id).modal('hide')
                        this.disable_status = false
                        if (response.status == 200) {
                            this.success_message = response.message
                            window.location.reload()

                        }

                    })
                },
                confirmUpdate(id) {
                    this.success_message = ''
                    $.ajax({
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            trip_hd_id: id,
                        },
                        url: '{{ route("Trips.confirmUpdate") }}'
                    }).then(response => {
                        if (response.status == 200) {
                            this.success_message = response.message
                            $('#myModal' + id).modal('hide')
                            // window.location.reload();
                        }

                    })
                },

            }
        })

        function createTrip(tripId) {
            $('#trip' + tripId).prop('disabled', 'true')
            url = '{{ route('api.Trips.createTrip') }}';
            $.ajax({
                type: 'POST',
                url: url,
                data:
                    {
                        "_token": "{{ csrf_token() }}",
                        'id': tripId,
                    },

            }).done(function (data) {
                $('#trip' + tripId).removeAttr('disabled')
                if (data.success) {
                    toastr.success(data.msg);
                    location.reload();
                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }

        function printTrip(tripId) {

            url = '{{ route('api.Trips.printTrip') }}';
            $.ajax({
                type: 'GET',
                url: url,
                data:
                    {
                        "_token": "{{ csrf_token() }}",
                        'id': tripId,
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
    </script>
@endsection



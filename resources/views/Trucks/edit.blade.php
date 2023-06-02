@extends('Layouts.master')

@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css"/>

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
                <ul class="nav nav-tabs page-header-tab">
                    <li class="nav-item @if(!request()->qr) active @endif ">
                        <a class="nav-link" href="#data-grid" data-toggle="tab">@lang('home.basic_information')</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="tab">@lang('customer.customer_account')</a>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="#attachments-grid"
                                            data-toggle="tab">@lang('home.files')</a></li>
                    <li class="nav-item"><a class="nav-link" href="#notes-grid"
                                            data-toggle="tab">@lang('home.notes')</a></li>
                </ul>
                <div class="header-action">

                </div>
            </div>
        </div>
    </div>



    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">

                {{-- Basic information --}}
                <div class="tab-pane fade show active " id="data-grid" role="tabpanel">

                    {{-- Form To Create Customer--}}
                    <form class="card" id="validate-form" action="{{ route('Trucks.update',$truck->truck_id) }}"
                          method="post"
                          enctype="multipart/form-data" id="submit_user_form">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            {{--inputs and photo--}}
                            <div class="font-25">
                                @lang('trucks.changed_truck')
                            </div>
                            <div class="row">
                                <div class="col-md-9">

                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('trucks.truck_no') </label>
                                                <input type="text" class="form-control" name="truck_code"
                                                       id="truck_code" value="{{$truck->truck_code}}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label "
                                                       readonly> @lang('trucks.truck_name')  </label>
                                                <input type="text" class="form-control" name="truck_name"
                                                       id="truck_name" value="{{$truck->truck_name}}">

                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('trucks.truck_type') </label>
                                                <select class="form-select form-control " name="truck_type"
                                                        id="truck_type" required>
                                                    <option value="" selected></option>
                                                    @foreach($sys_codes_type as $sys_code_type)
                                                        <option value="{{ $sys_code_type->system_code_id }}"
                                                                @if($sys_code_type->system_code_id == $truck->truck_type)
                                                                selected
                                                                @endif
                                                        >
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$sys_code_type->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_type->system_code_name_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('trucks.truck_status') </label>
                                                <select class="form-select form-control"
                                                        name="truck_status"
                                                        id="truck_status" required>
                                                    <option value="" selected></option>
                                                    @foreach($sys_codes_status as $sys_code_status)
                                                        <option value="{{ $sys_code_status->system_code_id }}"
                                                                @if($sys_code_status->system_code_id == $truck->truck_status)
                                                                selected
                                                                @endif
                                                        >
                                                            @if(app()->getLocale()=='ar')
                                                                {{$sys_code_status->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_status->system_code_name_en}}
                                                            @endif

                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </div>


                                    <div class="mb-3">
                                        <div class="row">


                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.rightLetter') </label>

                                                <select class="form-control plate_ar_1" name="rightLetter"
                                                        required v-model="rightLetter">
                                                    @foreach(config('global.car_letters_ar') as $letter )
                                                        <option value="{{$letter}}">{{$letter}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.middleLetter') </label>
                                                <select class="form-control plate_ar_2" name="middleLetter"
                                                        required v-model="middleLetter">
                                                    @foreach(config('global.car_letters_ar') as $letter )
                                                        <option value="{{$letter}}">{{$letter}}</option>
                                                    @endforeach
                                                </select>

                                            </div>

                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.leftLetter') </label>
                                                <select type="text" class="form-control plate_ar_3" required
                                                        name="leftLetter" v-model="leftLetter">
                                                    @foreach(config('global.car_letters_ar') as $letter )
                                                        <option value="{{$letter}}">{{$letter}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.plate_number') </label>
                                                <input type="number" class="form-control car_plate_number1"
                                                       name="plate_number" required v-model="plate_number">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.plateType') </label>
                                                <select class="form-control" name="plateTypeId" required>
                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($plate_types as $plate_type)
                                                        <option value="{{$plate_type->system_code_id}}"
                                                                @if($truck->plateTypeId == $plate_type->system_code_id) selected @endif>
                                                            {{app()->getLocale()=='ar' ? $plate_type->system_code_name_ar :
                                                            $plate_type->system_code_name_en}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('trucks.truck_plate') </label>
                                                <input type="text" class="form-control " name="truck_plate_no"
                                                       id="truck_plate_no" v-model="truck_plate_no" required>
                                            </div>


                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('trucks.truck_chasi') </label>
                                                <input type="text" class="form-control" name="truck_chassis_no"
                                                       id="truck_chassis_no" value="{{$truck->truck_chassis_no}}"
                                                       required>
                                            </div>


                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('trucks.truck_manufactuer_company') </label>
                                                <select class="form-select form-control"
                                                        name="truck_manufactuer_company"
                                                        aria-label="Default select example"
                                                        id="truck_manufactuer_company" required>
                                                    <option value="" selected></option>
                                                    @foreach($sys_codes_manufactuer as $sys_code_manufactuer)
                                                        <option value="{{$sys_code_manufactuer->system_code_id}}"
                                                                @if($sys_code_manufactuer->system_code_id == $truck->truck_manufactuer_company)
                                                                selected
                                                                @endif
                                                        >
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$sys_code_manufactuer->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_manufactuer->system_code_name_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('trucks.truck_model') </label>
                                                <input type="number" class="form-control" name="truck_model"
                                                       id="truck_model" value="{{$truck->truck_model}}">

                                            </div>

                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('trucks.truck_ownership_status') </label>
                                                <select class="form-select form-control" name="truck_ownership_status"
                                                        aria-label="Default select example" id="truck_ownership_status"
                                                        required>
                                                    <option value="" selected></option>
                                                    @foreach($sys_codes_ownership_status as $sys_code_ownership_status)
                                                        <option value="{{$sys_code_ownership_status->system_code_id}}"
                                                                @if($sys_code_ownership_status->system_code_id == $truck->truck_ownership_status)
                                                                selected
                                                                @endif
                                                        >
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$sys_code_ownership_status->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_ownership_status->system_code_name_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('trucks.truck_rent_amount') </label>
                                                <input type="number" class="form-control" name="truck_rent_amount"
                                                       id="truck_rent_amount" value="{{$truck->truck_rent_amount}}">

                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('trucks.truck_ownership') </label>
                                                <select class="selectpicker" data-live-search="true"
                                                        name="truck_ownership_id"
                                                        id="truck_ownership_id" required>
                                                    <option value="" selected>choose</option>
                                                    @foreach($suppliers as $supplier)
                                                        <option value="{{$supplier->customer_id}}"
                                                                @if($supplier->customer_id == $truck->truck_ownership_id)
                                                                selected
                                                                @endif>
                                                            @if(app()->getLocale() == 'ar')
                                                                {{ $supplier->customer_name_full_ar }}
                                                            @else
                                                                {{ $supplier->customer_name_full_en }}
                                                            @endif

                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('trucks.sub_company') </label>
                                                <select class="form-select form-control"
                                                        name="company_id" id="company_id" required>
                                                    <option value="" selected>Choose</option>
                                                    @foreach($companies as $company)
                                                        <option value="{{ $company->company_id }}"
                                                                @if($company->company_id == $truck->company_id)
                                                                selected
                                                                @endif
                                                        >
                                                            @if(app()->getLocale()=='ar')
                                                                {{ $company->company_name_ar }}
                                                            @else
                                                                {{ $company->company_name_en }}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="mb-3">

                                        <div class="card">
                                            <div class="card-header">
                                                <img src="{{ $truck->truck_photo }}">
                                            </div>
                                            <div class="card-body">
                                                <input type="file" id="dropify-event"
                                                       name="truck_photo"
                                                       value="{{$truck->truck_photo ? $truck->truck_photo : ''}}">
                                            </div>
                                        </div>

                                    </div>
                                </div>


                            </div>


                            <div class="mb-3">
                                <div class="row">

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.truck_supplier') </label>
                                        <select class="selectpicker" data-live-search="true" name="truck_supplier"
                                                id="truck_supplier">
                                            <option value="" selected>choose</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{$supplier->customer_id}}"
                                                        @if($supplier->customer_id == $truck->truck_supplier)
                                                        selected
                                                        @endif>
                                                    @if(app()->getLocale() == 'ar')
                                                        {{ $supplier->customer_name_full_ar }}
                                                    @else
                                                        {{ $supplier->customer_name_full_en }}
                                                    @endif

                                                </option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('trucks.truck_purchase_date') </label>
                                        <input type="date" class="form-control" name="truck_purchase_date"
                                               id="truck_purchase_date" value="{{$truck->truck_purchase_date}}"
                                               onchange="getDateBirthday()">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.truck_purchase_value') </label>
                                        <input type="number" class="form-control" name="truck_purchase_value"
                                               id="truck_purchase_value" value="{{$truck->truck_purchase_value}}"
                                               step="0.01">

                                    </div>

                                    <div class="col-md-2">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.truck_depreciation_ratio') </label>
                                        <input type="number" class="form-control" name="truck_depreciation_ratio"
                                               id="truck_depreciation_ratio"
                                               value="{{$truck->truck_depreciation_ratio}}"
                                               step="0.01">

                                    </div>

                                    <div class="col-md-1">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.truck_depreciation_years') </label>
                                        <input type="text" class="form-control" name="truck_depreciation_years"
                                               id="truck_depreciation_years"
                                               value="{{$truck->truck_depreciation_years}}">

                                    </div>

                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.truck_seller') </label>
                                        <select class="selectpicker" data-live-search="true" name="truck_seller"
                                                id="truck_seller">
                                            <option value="" selected>choose</option>
                                            @foreach($customers as $customer)
                                                <option value="{{$customer->customer_id }}"
                                                        @if($customer->customer_id == $truck->truck_seller)
                                                        selected
                                                        @endif
                                                >
                                                    @if(app()->getLocale() == 'ar')
                                                        {{ $customer->customer_name_full_ar }}
                                                    @else
                                                        {{ $customer->customer_name_full_en }}
                                                    @endif

                                                </option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('trucks.truck_sales_date') </label>
                                        <input type="date" class="form-control" name="truck_sales_date"
                                               id="truck_sales_date" value="{{$truck->truck_sales_date}}"
                                               onchange="getDateBirthday()">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.truck_sales_value') </label>
                                        <input type="number" class="form-control" name="truck_sales_value"
                                               id="truck_sales_value" value="{{$truck->truck_sales_value}}" step="0.01">

                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.trucker_status') </label>
                                        <select class="form-select form-control" name="trucker_status"
                                                id="trucker_status">
                                            <option value="" selected></option>
                                            @foreach($sys_codes_tracker_status as $sys_code_tracker_status)
                                                <option value="{{$sys_code_tracker_status->system_code_id}}"
                                                        @if($sys_code_tracker_status->system_code_id == $truck->trucker_status )
                                                        selected
                                                        @endif
                                                >
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_code_tracker_status->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_tracker_status->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            </div>


                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.truck_driver') </label>
                                        <select class="selectpicker" data-live-search="true" name="truck_driver_id"
                                                id="truck_driver_id">
                                            <option value="" selected>choose</option>


                                            @foreach($employees as $employee)
                                                <option value="{{$employee->emp_id }}"
                                                        @if($employee->emp_id == $truck->truck_driver_id)
                                                        selected
                                                        @endif>
                                                    @if(app()->getLocale() == 'ar')
                                                        {{ $employee->emp_name_full_ar }}
                                                    @else
                                                        {{ $employee->emp_name_full_en }}
                                                    @endif

                                                </option>
                                            @endforeach


                                        </select>

                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('trucks.truck_driver_eceived') </label>
                                        <input type="date" class="form-control" name="truck_driver_eceived"
                                               id="truck_driver_eceived" value="{{$truck->truck_driver_eceived}}"
                                               onchange="getDateBirthday()">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('trucks.truck_driver_delivery') </label>
                                        <input type="date" class="form-control" name="truck_driver_delivery"
                                               id="truck_driver_delivery" value="{{$truck->truck_driver_delivery}}"
                                               onchange="getDateBirthday()">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.trucker_ref_no') </label>
                                        <input type="text" class="form-control" name="trucker_ref_no"
                                               id="trucker_ref_no" value="{{$truck->trucker_ref_no}}">

                                    </div>

                                    <div class="col-md-1">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.truck_load_weight') </label>
                                        <input type="number" class="form-control" name="truck_load_weight"
                                               id="truck_load_weight" value="{{$truck->truck_load_weight}}">

                                    </div>


                                </div>
                            </div>


                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary mr-2"
                                        data-bs-dismiss="modal" id="create_emp">@lang('trucks.save')</button>
                            </div>
                        </div>
                    </form>
                </div>

                {{------------Practical_attachment_grid---------------------------------------------------------------}}
                <div class="tab-pane fade " id="attachments-grid" role="tabpanel">
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">

                                <div class="card-header">
                                    <div class="card-body">

                                        <div class="md-3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h6 class="text-center mt-4">@lang('home.files')</h6>
                                                </div>

                                                {{--readonly--}}
                                                <div class="col-md-4">
                                                    <label for="recipient-name"
                                                           class="col-form-label "> @lang('home.name') </label>
                                                    <input type="text" class="form-control" name="emp_name_full_ar"
                                                           id="emp_name_full_ar"
                                                           value=" {{$truck->truck_name}}"
                                                           readonly>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="recipient-name"
                                                           class="col-form-label "> @lang('trucks.truck_no') </label>
                                                    <input type="text" class="form-control" name="emp_code"
                                                           id="emp_code" value=" {{$truck->truck_code}}" readonly>
                                                </div>
                                                {{--readonly--}}
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-md-12">


                            <x-files.form>
                                <input type="hidden" name="transaction_id" value="{{$truck->truck_id}}">
                                <input type="hidden" name="app_menu_id" value="34">

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
                                        <td>{{ $attachment->copy_no }}</td>
                                        <td>
                                            <a href="{{ url('/attachments/download-pdf?name=' . $attachment->attachment_file_url) }}">
                                                <i class="fa fa-download fa-2x"></i></a>
                                            <a href="{{ asset('Files/'.$attachment->attachment_file_url) }}"
                                               target="_blank" class="mr-1 ml-1"><i
                                                        class="fa fa-eye text-info mr-3 ml-3"
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


                {{------------Practical_notes_grid---------------------------------------------------------------}}
                <div class="tab-pane fade " id="notes-grid" role="tabpanel">
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">

                                <div class="card-header">
                                    <div class="card-body">

                                        <div class="md-3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h6 class="text-center mt-4">@lang('home.notes')</h6>
                                                </div>

                                                {{--readonly--}}
                                                <div class="col-md-4">
                                                    <label for="recipient-name"
                                                           class="col-form-label "> @lang('home.name') </label>
                                                    <input type="text" class="form-control" name="emp_name_full_ar"
                                                           id="emp_name_full_ar"
                                                           value=" {{$truck->truck_name}}"
                                                           readonly>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="recipient-name"
                                                           class="col-form-label "> @lang('trucks.truck_no') </label>
                                                    <input type="text" class="form-control" name="emp_code"
                                                           id="emp_code" value=" {{$truck->truck_code}}" readonly>
                                                </div>
                                                {{--readonly--}}
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-md-12">

                            <x-files.form-notes>

                                <input type="hidden" name="transaction_id" value="{{$truck->truck_id}}">
                                <input type="hidden" name="app_menu_id" value="34">


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


            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script src="{{asset('assets/plugins/dropify/js/dropify.min.js')}}"></script>
    <script src="{{asset('assets/js/form/form-advanced.js')}}"></script>
    <script>

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


        })


    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">


        $(function () {

            $("#issue_date_hijri").hijriDatePicker();
            $("#expire_date_hijri").hijriDatePicker();
            $("#emp_hijri_start_date").hijriDatePicker();
            $("#emp_hijri_end_date").hijriDatePicker();
            $("#emp_birthday_hijiri").hijriDatePicker();
        });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>

        new Vue({
            el: '#app',
            data: {
                expire_date: '',
                issue_date: '',
                issue_date_hijri: '',
                expire_date_hijri: '',

                truck: {},
                truck_id: '',
                rightLetter: '',
                middleLetter: '',
                leftLetter: '',
                plate_number: '',
            },
            mounted() {
                this.truck_id = {!! $id !!}
                    this.getTruck()

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
                getTruck() {
                    if (this.truck_id) {
                        $.ajax({
                            type: 'GET',
                            data: {truck_id: this.truck_id},
                            url: ''
                        }).then(response => {
                            this.truck = response.data
                            this.rightLetter = this.truck.rightLetter
                            this.middleLetter = this.truck.middleLetter
                            this.leftLetter = this.truck.leftLetter
                            this.plate_number = this.truck.plate_number

                        })
                    }
                },
                getIssueDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.issue_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.issue_date_hijri = response.data
                    })
                },
                getExpireDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.expire_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.expire_date_hijri = response.data
                    })
                },

                getGeorgianDate() {
                    if (this.issue_date_hijri) {
                        $.ajax({
                            type: 'GET',
                            data: {date: this.issue_date_hijri},
                            url: '{{ route("test-date") }}'
                        }).then(response => {
                            this.issue_date = response.data
                        })
                    }
                },

                getGeorgianDate2() {
                    if (this.expire_date_hijri) {
                        $.ajax({
                            type: 'GET',
                            data: {date: this.expire_date_hijri},
                            url: '{{ route("test-date") }}'
                        }).then(response => {
                            this.expire_date = response.data
                        })
                    }
                },

            },
            computed: {
                truck_plate_no: function () {
                    return this.rightLetter + ' ' + this.middleLetter + ' ' + this.leftLetter + ' ' + this.plate_number
                }
            }
        });
    </script>

@endsection

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
                <ul class="nav nav-tabs page-header-tab">

                    <li class="nav-item">
                        <a href="#data-grid" data-toggle="tab"
                           class="nav-link active">@lang('home.data')</a>
                    </li>

                    <li class="nav-item">
                        <a href="#attachments-grid" data-toggle="tab" class="nav-link">@lang('home.attachments')</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#notes-grid" data-toggle="tab">@lang('home.notes')</a>
                    </li>

                    <li class="nav-item">
                        <a href="#receipt-grid" data-toggle="tab" class="nav-link">@lang('home.receipt')</a>
                    </li>


                </ul>
            </div>
        </div>
    </div>

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">

            <div class="tab-content mt-3">

                <div class="tab-pane fade show active" id="data-grid" role="tabpanel">
                    <div class="col-lg-12">

                        <form action="{{route('truck-accident.update',$car_accident->car_accident_id)}}"
                              enctype="multipart/form-data" method="post">
                            @csrf
                            @method('put')
                            <div class="card">


                                <div class="card-body">
                                    <h3 class="card-title"></h3>

                                    <input type="hidden" name="path" value="{{$path}}">
                                    @if($car_accident->contract)
                                        <div class="row">

                                            <input type="hidden" name="contract_id"
                                                   value="{{$car_accident->contract->contract_id}}">
                                            {{-- بيانات المستأجر --}}
                                            <div class="col-md-6 "
                                                 style="border: #0E9BE2 solid 1px; border-radius: 5px; padding: 10px;
                                                 padding-right: 2px;padding-left: 2px;">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control text-center" readonly
                                                               value="بيانات المستأجر \ المسؤول">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.name')</label>
                                                        <input type="text" class="form-control text-center" readonly

                                                               @if(app()->getLocale() == 'ar')
                                                               value="{{$car_accident->contract->customer->customer_name_full_ar}}"
                                                               @else
                                                               value="{{$car_accident->contract->customer->customer_name_full_en}}"
                                                               @endif
                                                               style="background-color:transparent">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.id_number')</label>
                                                        <input type="text" class="form-control text-center" readonly
                                                               value="{{$car_accident->contract->c_idNumber}}"
                                                               style="background-color:transparent">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.phone_number')</label>
                                                        <input type="text" class="form-control text-center" readonly
                                                               value="{{$car_accident->contract->c_mobile}}"
                                                               style="background-color:transparent">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.nationality')</label>
                                                        <input type="text" class="form-control text-center" readonly
                                                               value="{{$car_accident->contract->customer->customer_nationality}}"
                                                               style="background-color:transparent">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.birthday')</label>
                                                        <input type="date" class="form-control text-center" readonly
                                                               value="{{$car_accident->contract->customer->customer_birthday}}"
                                                               style="background-color:transparent">
                                                    </div>
                                                </div>


                                            </div>
                                            {{-- بيانات السياره --}}
                                            <div class="col-md-6 "
                                                 style="border: #0E9BE2 solid 1px; border-radius: 5px; padding: 10px;">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control text-center" readonly
                                                               value="بيانات السياره">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.car_number')</label>
                                                        <input type="text" class="form-control text-center" readonly
                                                               style="background-color:transparent"
                                                               value="{{$car_accident->contract->car->full_car_plate}}">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.car_type')</label>
                                                        <input type="text" class="form-control text-center" readonly
                                                               style="background-color:transparent" value="{{app()->getLocale() =='ar' ?
                                                       $car_accident->contract->car->brand->brand_name_ar :
                                                       $car_accident->contract->car->brand->brand_name_en}}">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('carrent.car_model')</label>
                                                        <input type="text" class="form-control text-center" readonly
                                                               style="background-color:transparent"
                                                               value="{{$car_accident->contract->car->car_model_year}}">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('carrent.car_color')</label>
                                                        <input type="text" class="form-control text-center" readonly
                                                               style="background-color:transparent"
                                                               value="{{$car_accident->contract->car->car_color}}">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('carrent.car_ownership')</label>
                                                        <input type="text" class="form-control text-center" readonly
                                                               style="background-color:transparent"
                                                               value="{{$car_accident->contract->car->owner_name}}">
                                                    </div>
                                                </div>


                                            </div>

                                        </div>
                                    @endif

                                    {{--,وصف الحادث--}}

                                    <div class="row mt-3 mb-3"
                                         style="border: #0E9BE2 solid 1px; border-radius: 5px; padding: 10px; ">
                                        {{--تاريخ التسجيل--}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.date_registration')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       name="car_accident_date_open"
                                                       value="{{$car_accident->car_accident_date_open}}">
                                            </div>
                                        </div>

                                        {{--<div class="col-md-4">--}}
                                        {{--<div class="form-group">--}}
                                        {{--<label class="form-label">@lang('home.date_registration_hejri')</label>--}}
                                        {{--<input type="text" class="form-control" disabled="">--}}
                                        {{--</div>--}}
                                        {{--</div>--}}

                                        {{--اليوم--}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.today')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       value="{{ \Carbon\Carbon::parse($car_accident->car_accident_date_open)->translatedFormat('l')}}">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.type')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       @if(isset($contract)) value="@lang('home.contract')" @endif >
                                            </div>
                                        </div>

                                        @if($car_accident->contract)
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.contract_itinerary')</label>
                                                    <input type="text" class="form-control" disabled=""
                                                           value="{{$car_accident->contract->contract_code}}">
                                                </div>
                                            </div>
                                        @endif

                                        {{--المستخدم--}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.user')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       @if(app()->getLocale() == 'ar')
                                                       value="{{$car_accident->user->user_name_ar}}"
                                                       @else value="{{$car_accident->user->user_name_en}}" @endif >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3 mb-3"
                                         style="border: #0E9BE2 solid 1px; border-radius: 5px; padding: 10px; ">

                                        {{--نوع الحادث--}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.accident_type')</label>
                                                <select name="car_accident_type_id" class="form-control">
                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($accident_types as $accident_type)
                                                        <option value="{{$accident_type->system_code_id}}"
                                                                @if($car_accident->car_accident_type_id == $accident_type->system_code_id)
                                                                selected @endif>
                                                            {{app()->getLocale() == 'ar'
                                                            ? $accident_type->system_code_name_ar
                                                            : $accident_type->system_code_name_en}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{--حاله الحادث--}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.accident_status')</label>

                                                <select name="car_accident_status" class="form-control">
                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($accident_statuses as $accident_status)
                                                        <option value="{{$accident_status->system_code_id}}"
                                                                @if($accident_status->system_code_id == $car_accident->car_accident_status)
                                                                selected @endif>
                                                            {{app()->getLocale() == 'ar'
                                                            ? $accident_status->system_code_name_ar
                                                            : $accident_status->system_code_name_en}}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>

                                        

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.actual_accident_date')</label>
                                                <input type="date" class="form-control"
                                                       name="car_accident_date"
                                                       value="{{ $car_accident->car_accident_date }}">
                                            </div>
                                        </div>

                                        {{--مباشر الحادث--}}
                                        <div class="col-md-3">
                                            <label class="form-label">@lang('home.live_accident')</label>
                                            <select name="emp_id" class="selectpicker" multiple
                                                    data-live-search="true" id="">
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($direct_employees as $direct_employee)
                                                    <option value="{{$direct_employee->emp_id}}"
                                                            @if($direct_employee->emp_id == $car_accident->emp_id)
                                                            selected @endif>
                                                        {{app()->getLocale() == 'ar'
                                                        ? $direct_employee->emp_name_full_ar
                                                        : $direct_employee->emp_name_full_en}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{--مباشره الحادث--}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.direct_accident')</label>

                                                <select name="car_accident_directly" class="form-control">
                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($direct_accident as $direct_acc)
                                                        <option value="{{$direct_acc->system_code_id}}"
                                                                @if($direct_acc->system_code_id == $car_accident->car_accident_directly) selected @endif>
                                                            {{app()->getLocale() == 'ar'
                                                            ? $direct_acc->system_code_name_ar
                                                            : $direct_acc->system_code_name_en}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{--رقم الحاله--}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.status_number')</label>
                                                <input type="number" min="0" class="form-control"
                                                       value="{{$car_accident->car_accident_status_no}}"
                                                       name="car_accident_status_no">
                                            </div>
                                        </div>

                                        {{--جهه التقدير--}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.appreciation_body')</label>

                                                <select name="car_accident_appreciate" class="form-control">
                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($appreciation_sides as $appreciation_side)
                                                        <option value="{{$appreciation_side->system_code_id}}"
                                                                @if($car_accident->car_accident_appreciate == $appreciation_side->system_code_id)
                                                                selected @endif>
                                                            {{app()->getLocale() == 'ar'
                                                            ? $appreciation_side->system_code_name_ar
                                                            : $appreciation_side->system_code_name_en}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{--رقم حاله التقدير--}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.appreciation_status_number')</label>
                                                <input type="text" class="form-control"
                                                       value="{{$car_accident->car_accident_appreciate_status}}"
                                                       name="car_accident_appreciate_status">
                                            </div>
                                        </div>

                                        {{--مبلغ التعويض--}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.Compensation_amount_truck')</label>
                                                <input type="text" class="form-control"
                                                       value="{{$car_accident->car_accident_amount}}"
                                                       name="car_accident_amount">
                                            </div>
                                        </div>

                                        <div class="col-md-9">
                                            <div class="form-group mb-0">
                                                <label class="form-label">@lang('home.Description_of_the_accident')</label>
                                                <textarea rows="5" class="form-control"
                                                          name="car_accident_notes">{{$car_accident->car_accident_notes}}</textarea>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row mt-3 mb-3"
                                         style="border: #0E9BE2 solid 1px; border-radius: 5px; padding: 10px; ">

                                        {{--المعقب--}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.al_maqqab')</label>
                                                <select class="selectpicker" data-live-search="true"
                                                        name="car_accident_follower">
                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($accident_followers as $accident_follower)
                                                        <option value="{{$accident_follower->emp_id}}" @if($accident_follower->emp_id
                                                        == $car_accident->car_accident_follower) selected @endif>
                                                            {{app()->getLocale() == 'ar'? $accident_follower->emp_name_full_ar
                                                           : $accident_follower->emp_name_full_en}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        {{--شركات التامين--}}
                                        <div class="col-md-4">
                                            <label class="form-label">@lang('home.insurance_company')</label>
                                            <select name="car_accident_insurance" class="selectpicker"
                                                    data-live-search="true" id="car_accident_insurance">
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($companies_insurance as $company_insurance)
                                                    <option value="{{$company_insurance->system_code_id}}" @if($company_insurance->system_code_id
                                                        == $car_accident->car_accident_insurance) selected @endif>
                                                        {{app()->getLocale() == 'ar'
                                                        ? $company_insurance->system_code_name_ar
                                                        : $company_insurance->system_code_name_en}}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        {{--رقم المطالبه--}}
                                        <div class="col-md-4">
                                            <label class="form-label">@lang('home.claim_no')</label>
                                            <input type="text" class="form-control" name="car_accident_claim_no"
                                                   value="{{$car_accident->car_accident_claim_no}}" >
                                        </div>


                                        {{--تاريخ التسليم--}}
                                        <div class="col-md-4">
                                            <label class="form-label">@lang('home.delivery_date')</label>
                                            <input type="date" class="form-control" name="car_accident_date_close"
                                                   value="{{$car_accident->car_accident_date_close}}" >
                                        </div>


                                        {{--رقم سند المطالبه--}}
                                        <div class="col-md-4">
                                            <label class="form-label">رقم سند المطالبه</label>
                                            <input type="text" class="form-control" name="claim_receive_no"
                                                   value="{{$car_accident->claim_receive_no}}" >
                                        </div>


                                        <div class="col-md-4">
                                            <input type="file" name="car_accident_url_doc">
                                        </div>

                                    </div>

                                    <button class="btn btn-primary" type="submit">@lang('home.save')</button>

                                </div>

                            </div>

                        </form>
                    </div>
                </div>

                {{-- files part --}}
                <div class="tab-pane fade" id="attachments-grid" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-12">

                            <x-files.form>
                                <input type="hidden" name="transaction_id"
                                       value="{{ $car_accident->car_accident_id }}">
                                <input type="hidden" name="app_menu_id" value="47">
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
                                          $attachment->attachmentType_2->system_code_name_en}} @endif</td>
                                        <td>{{ date('d-m-Y', strtotime($attachment->issue_date )) }}</td>
                                        <td>{{ date('d-m-Y', strtotime($attachment->expire_date )) }}</td>
                                        <td>{{ $attachment->issue_date_hijri }}</td>
                                        <td>{{ $attachment->expire_date_hijri }}</td>
                                        <td>{{ $attachment->copy_no }}</td>
                                        <td>
                                            <a href="{{ url('/attachments/download-pdf?name=' . $attachment->attachment_file_url) }}">
                                                <i class="fa fa-download text-blue fa-2x"></i></a>
                                            <a href="{{ asset('Files/'.$attachment->attachment_file_url) }}"
                                               target="_blank" class="mr-1 ml-1"><i class="fa fa-eye text-blue"
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

                {{-- notes part --}}
                <div class="tab-pane fade" id="notes-grid" role="tabpanel">

                    <div class="row">
                        <div class="col-lg-12">
                            <x-files.form-notes>
                                <input type="hidden" name="transaction_id"
                                       value="{{ $car_accident->car_accident_id }}">
                                <input type="hidden" name="app_menu_id" value="47">
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


                {{------------receipt-grid---------سند القبض----------------------------------------------------------}}
                <div class="tab-pane fade" id="receipt-grid" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('home.receipt')</h3>


                            <a href="{{ url('bonds-add/capture/create?car_accident_id=') }}{{$car_accident->car_accident_id}}"
                               class="btn btn-primary btn-sm">
                                <i class="fe fe-plus mr-2"></i> @lang('home.add_new_capture')</a>


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
                                        @foreach($bonds_capture as $bond_capture)
                                            <tr>
                                                <td>{{ $bond_capture->bond_code }}</td>
                                                <td>{{ $bond_capture->created_date }}</td>
                                                <td>{{ app()->getLocale() == 'ar' ?
                                            $bond_capture->company->company_name_ar :
                                            $bond_capture->company->company_name_en }}</td>

                                                <td>{{ app()->getLocale() == 'ar' ?
                                            $bond_capture->branch->branch_name_ar :
                                            $bond_capture->branch->branch_name_en }}</td>
                                                <td>{{ $bond_capture->bond_acc_id }}</td>
                                                <td>{{ app()->getLocale() == 'ar' ? $bond_capture->paymentMethod->system_code_name_ar :
                                              $bond_capture->paymentMethod->system_code_name_en }}</td>
                                                <td>{{ $bond_capture->bond_amount_debit }}</td>
                                                <td>{{ app()->getLocale()=='ar' ? $bond_capture->userCreated->user_name_ar :
                                            $bond_capture->userCreated->user_name_en }}</td>
                                                <td>

                                                    @if($bond_capture->journalCapture)
                                                        <a href="{{ route('journal-entries.show',$bond_capture->journalCapture->journal_hd_id) }}"
                                                           class="btn btn-primary btn-sm">
                                                            @lang('home.journal_details')
                                                            {{$bond_capture->journalCapture->journal_hd_code}}
                                                        </a>
                                                    @endif


                                                </td>
                                                <td>
                                                    <a href="{{config('app.telerik_server')}}?rpt={{$bond_capture->report_url_payment->report_url}}&id={{$bond_capture->bond_id}}&lang=ar&skinName=bootstrap"
                                                       class="btn btn-primary btn-sm"
                                                       title="@lang('home.show')"><i
                                                                class="fa fa-print"></i></a>
                                                    <a href="{{ route('Bonds-cash.show',$bond_capture->bond_id) }}"
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
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $("#issue_date_hijri").hijriDatePicker();
            $("#expire_date_hijri").hijriDatePicker();
        });

    </script>

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
        });
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>

        new Vue({
            el: '#app',
            data: {
                expire_date: '',
                issue_date: '',
                issue_date_hijri: '',
                expire_date_hijri: '',
                date: ''
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
                getIssueDateHijri() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.issue_date_hijri},
                        url: '{{ route("api.getDate2") }}'
                    }).then(response => {
                        this.issue_date = response.data
                    })
                }

            }

        })
    </script>

@endsection
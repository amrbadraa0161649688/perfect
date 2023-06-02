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
                        <a class="nav-link" href="#data-grid{{$employee->emp_id}}"
                           data-toggle="tab">@lang('home.basic_information')</a>
                    </li>
                    <li class="nav-item @if(request()->qr == 'contracts') active @endif ">
                        <a class="nav-link" href="#contracts-grid{{$employee->emp_id}}"
                           data-toggle="tab">@lang('home.contract_data')</a>
                    </li>
                    <li class="nav-item @if(request()->qr == 'certificates') active @endif">
                        <a class="nav-link" href="#certificates-grid{{$employee->emp_id}}"
                           data-toggle="tab">@lang('home.certificates')</a>
                    </li>

                    <li class="nav-item @if(request()->qr == 'experiences') active @endif ">
                        <a class="nav-link" href="#practical-experiences-grid{{$employee->emp_id}}"
                           data-toggle="tab">@lang('home.practical_experiences')</a>
                    </li>

                    <li class="nav-item @if($qr == 'requests') active @endif ">
                        <a class="nav-link" href="#employee-requests-grid{{$employee->emp_id}}"
                           data-toggle="tab">@lang('home.employee_requests')</a>
                    </li>


                    <li class="nav-item"><a class="nav-link" href="#attachments-grid{{$employee->emp_id}}"
                                            data-toggle="tab">@lang('home.files')</a>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="#notes-grid{{$employee->emp_id}}"
                                            data-toggle="tab">@lang('home.notes')</a>
                    </li>

                    {{--<li class="nav-item"><a class="nav-link" href="#" data-toggle="tab">@lang('home.vacations')</a></li>--}}
                    {{--<li class="nav-item"><a class="nav-link" href="#" data-toggle="tab">@lang('home.rewards')</a></li>--}}
                    {{--<li class="nav-item"><a class="nav-link" href="#" data-toggle="tab">@lang('home.sanctions')</a></li>--}}

                </ul>
                <div class="header-action"></div>
            </div>
        </div>
    </div>


    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">

                {{--------Basic information---------------------------------------------------------------------------}}
                <div class="tab-pane fade @if(!request()->qr == 'contracts' && $qr=='') active show @endif "
                     id="data-grid{{$employee->emp_id}}"
                     role="tabpanel">
                    @include('Includes.form-errors')
                    {{-- Form To Create Employee--}}
                    <form class="card" id="validate-form" action="{{ route('employees.update',$employee->emp_id) }}"
                          method="post"
                          enctype="multipart/form-data" id="submit_user_form">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            {{--inputs and photo--}}
                            <div class="row">

                                <div class="col-md-9">

                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('home.emp_code') </label>
                                                <input type="text" class="form-control" name="emp_code"
                                                       value="{{$employee->emp_code}}">
                                            </div>

                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.name_ar') </label>
                                                <input type="text" class="form-control"
                                                       name="emp_name_full_ar" v-model="emp_name_full_ar"
                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,'');" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.name_en') </label>
                                                <input type="text" class="form-control"
                                                       name="emp_name_full_en" v-model="emp_name_full_en"
                                                       id="emp_name_full_en"
                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'');"
                                                       readonly>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.first_name_ar') </label>
                                                <input type="text" class="form-control" name="emp_name_1_ar"
                                                       id="emp_name_1_ar"
                                                       v-model="emp_name_1_ar"
                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.second_name_ar') </label>
                                                <input type="text" class="form-control" name="emp_name_2_ar"
                                                       id="emp_name_2_ar" v-model="emp_name_2_ar"
                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.third_name_ar') </label>
                                                <input type="text" class="form-control" name="emp_name_3_ar"
                                                       id="emp_name_3_ar" v-model="emp_name_3_ar"
                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.fourth_name_ar') </label>
                                                <input type="text" class="form-control" name="emp_name_4_ar"
                                                       id="emp_name_4_ar" v-model="emp_name_4_ar"
                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');">
                                            </div>


                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.first_name_en') </label>
                                                <input type="text" class="form-control" name="emp_name_1_en"
                                                       id="emp_name_1_en" v-model="emp_name_1_en"
                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.second_name_en') </label>
                                                <input type="text" class="form-control" name="emp_name_2_en"
                                                       id="emp_name_2_en" v-model="emp_name_2_en"
                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.third_name_en') </label>
                                                <input type="text" class="form-control" name="emp_name_3_en"
                                                       id="emp_name_3_en" v-model="emp_name_3_en"
                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.fourth_name_en') </label>
                                                <input type="text" class="form-control" name="emp_name_4_en"
                                                       id="emp_name_4_en" v-model="emp_name_4_en"
                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');">
                                            </div>


                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.nationality') </label>
                                                <select class="selectpicker" data-live-search="true"
                                                        name="emp_nationality"
                                                        id="emp_nationality">
                                                    @foreach($sys_codes_nationality_country as $sys_code_nationality_country)
                                                        <option value="{{ $sys_code_nationality_country->system_code }}"
                                                                @if($sys_code_nationality_country->system_code == $employee->emp_nationality)
                                                                selected
                                                                @endif
                                                        >
                                                            @if(app()->getLocale()=='ar')
                                                                {{$sys_code_nationality_country->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_nationality_country->system_code_name_en}}
                                                            @endif

                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('home.identity') </label>
                                                <input type="number" class="form-control" name="emp_identity"
                                                       id="emp_identity" value="{{$employee->emp_identity}}">
                                            </div>

                                            <div class="col-md-2">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('home.copy_no') </label>
                                                <input type="number" class="form-control" name="issueNumber"
                                                       id="issueNumber" value="{{$employee->issueNumber}}">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('home.status') </label>
                                                <select class="form-select form-control" name="emp_status"
                                                        aria-label="Default select example" id="emp_status">
                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($sys_codes_status as $sys_code_status)
                                                        <option value="{{$sys_code_status->system_code_id}}"
                                                                @if($sys_code_status->system_code_id == $employee->emp_status )
                                                                selected @endif>
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$sys_code_status->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_status->system_code_name_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="col-md-4">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('home.sub_company') </label>
                                                <select class="form-select form-control"
                                                        name="emp_default_company_id"
                                                        id="emp_default_company_id"
                                                        @change="getBranches()" v-model="company_id" required>
                                                    <option value="" selected>Choose</option>
                                                    @foreach($companies as $company)
                                                        <option value="{{ $company->company_id }}">
                                                            @if(app()->getLocale()=='ar')
                                                                {{ $company->company_name_ar }}
                                                            @else
                                                                {{ $company->company_name_en }}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('home.branch') </label>
                                                <select class="form-select form-control"
                                                        name="emp_default_branch_id" v-model="emp_default_branch_id"
                                                        id="emp_default_branch_id" required>
                                                    <option value="" selected>Choose</option>
                                                    <option v-for="branch in branches" :value="branch.branch_id"
                                                            :selected="emp_default_branch_id == branch.branch_id">
                                                        @if(app()->getLocale()=='ar')
                                                            @{{ branch.branch_name_ar }}
                                                        @else
                                                            @{{  branch.branch_name_en }}
                                                        @endif
                                                    </option>

                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.manager') </label>
                                                <select class="form-select form-control" name="emp_manager_id"
                                                        id="emp_manager_id">
                                                    <option value="" selected>Choose</option>
                                                    @foreach($employees as $emp)
                                                        <option value="{{ $emp->emp_id }}"

                                                                @if($emp->emp_id == $employee->emp_manager_id) selected @endif >
                                                            @if(app()->getLocale() == 'ar')
                                                                {{ $emp->emp_name_full_ar }}
                                                            @else
                                                                {{ $emp->emp_name_full_en }}
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
                                        <div class="row">
                                            <div class="card">
                                                <div class="card-header">
                                                    <img src="{{ $employee->emp_photo_url }}">
                                                </div>
                                                <div class="card-body">
                                                    <input type="file" id="dropify-event"
                                                           name="emp_photo_url">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.social_status') </label>
                                        <select class="form-select form-control" name="emp_social_status"
                                                id="emp_social_status">
                                            @foreach($sys_codes_social_status as $sys_code_social_status)
                                                <option value="{{$sys_code_social_status->system_code_id}}"
                                                        @if($sys_code_social_status->system_code_id == $employee->emp_social_status)
                                                        selected @endif>
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_code_social_status->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_social_status->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-md-1">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.family_count') </label>
                                        <select class="form-select form-control" name="emp_family_count"
                                                id="emp_family_count">
                                            <option value="{{$employee->emp_family_count}}" selected>
                                                {{$employee->emp_family_count}}
                                            </option>
                                            @for($i=1 ;  $i <= 10 ;$i++ )
                                                <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.work_start_date')
                                            :
                                            <span class="text-success">{{ $employee->emp_work_start_date }} </span></label>
                                        <input type="date" class="form-control" name="emp_work_start_date"
                                               id="emp_work_start_date"
                                               value="{{$employee->emp_work_start_date}}"
                                               @change="getWorkStartDate()" v-model="emp_work_start_date"
                                               autocomplete="off">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.hijri_start_date')
                                            :<span class="text-success"> {{ $employee->emp_hijri_start_date }} </span></label>
                                        <input type="text" class="form-control" name="emp_hijri_start_date"
                                               id="emp_hijri_start_date" v-model="emp_hijri_start_date"
                                               value="{{$employee->emp_hijri_start_date }}" autocomplete="off">
                                    </div>

                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>@lang('home.year')</label>
                                                <input type="text" readonly class="form-control" v-model="year">
                                            </div>

                                            <div class="col-md-4">
                                                <label>@lang('home.month')</label>
                                                <input type="text" readonly class="form-control" v-model="month">
                                            </div>

                                            <div class="col-md-4">
                                                <label>@lang('home.day')</label>
                                                <input type="text" readonly class="form-control" v-model="day">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="mb-3">
                                <div class="row">


                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.gender') </label>
                                        <select class="form-select form-control" name="emp_gender"
                                                id="emp_gender">
                                            <option value="" selected> choose</option>
                                            @foreach($sys_codes_gender as $sys_code_gender)
                                                <option value="{{$sys_code_gender->system_code}}"
                                                        @if($employee->emp_gender == $sys_code_gender->system_code_id) selected @endif >
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_code_gender->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_gender->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.birthday') :
                                            <span class="text-success">{{ $employee->emp_birthday }}</span></label>
                                        <input type="date" class="form-control" name="emp_birthday"
                                               id="emp_birthday" v-model="emp_birthday"
                                               value="{{ $employee->emp_birthday }}"
                                               @change="getBirthdayDate()" autocomplete="off">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.birthday_hijiri')
                                            <span class="text-success">{{ $employee->emp_birthday_hijiri }}</span></label>
                                        <input type="text" class="form-control" name="emp_birthday_hijiri"
                                               id="emp_birthday_hijiri" v-model="emp_birthday_hijiri"
                                               value="{{$employee->emp_birthday_hijiri}}" autocomplete="off">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.previous_vacation_balance') </label>
                                        <input type="number" class="form-control" name="emp_vacation_balance"
                                               id="emp_vacation_balance" value="{{$employee->emp_vacation_balance}}"
                                               readonly>
                                    </div>

                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.religion') </label>
                                        <select class="form-select form-control" name="emp_religion"
                                                id="emp_religion">
                                            <option value="" selected> choose</option>
                                            @foreach($sys_codes_religion as $sys_code_religion)
                                                <option value="{{$sys_code_religion->system_code}}"
                                                        @if($employee->emp_religion == $sys_code_religion->system_code_id) selected @endif >
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_code_religion->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_religion->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.birth_country') </label>
                                        <select class="selectpicker" data-live-search="true" name="emp_birth_country"
                                                id="emp_birth_country">
                                            @foreach($sys_codes_countries as $sys_code_country)
                                                <option value="{{$sys_code_country->system_code}}"
                                                        @if($sys_code_country->system_code == $employee->emp_birth_country )
                                                        selected @endif>
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_code_country->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_country->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.birth_city') </label>
                                        <input type="text" class="form-control" name="emp_birth_city"
                                               id="emp_birth_city" value="{{ $employee->emp_birth_city }}">
                                    </div>


                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.direct_date') </label>
                                        <input type="date" class="form-control" name="emp_direct_date"
                                               id="emp_direct_date" value="{{$employee->emp_direct_date}}">
                                    </div>

                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">


                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.private_mobile') </label>
                                        <input type="number" class="form-control" name="emp_private_mobile"
                                               id="emp_private_mobile" value="{{ $employee->emp_private_mobile }}">

                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.email_private') </label>
                                        <input type="email" class="form-control" name="emp_email_private"
                                               id="emp_email_private" value="{{$employee->emp_email_private}}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.po_box_postal') </label>
                                        <input type="text" class="form-control" name="emp_po_box_postal"
                                               id="emp_po_box_postal" value="{{ $employee->emp_po_box_postal }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.address') </label>
                                        <input type="text" class="form-control" name="emp_current_address"
                                               id="emp_current_address" value="{{ $employee->emp_current_address }}">
                                    </div>

                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.work_mobile') </label>
                                        <input type="number" class="form-control" name="emp_work_mobile"
                                               id="emp_work_mobile" value="{{ $employee->emp_work_mobile }}">

                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.email_work') </label>
                                        <input type="email" class="form-control" name="emp_email_work"
                                               id="emp_email_work" value="{{$employee->emp_email_work}}">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.sponsor_name') </label>
                                        <select class="form-select form-control" name="emp_sponsor_id"
                                                id="emp_sponsor_id">
                                            @foreach($sys_codes_sponsor_names as $sys_c_sponsor_name)
                                                <option value="{{$sys_c_sponsor_name->system_code}}"
                                                        @if($sys_c_sponsor_name->system_code ==
                                                         $employee->emp_sponsor_id )
                                                        selected @endif>
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_c_sponsor_name->system_code_name_ar}}
                                                    @else
                                                        {{$sys_c_sponsor_name->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.previous_sponsor_name') </label>
                                        <input type="text" class="form-control"
                                               name="emp_previous_sponsor_name"
                                               id="emp_previous_sponsor_name"
                                               value="{{$employee->emp_previous_sponsor_name}}">
                                    </div>

                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.job_in_identity') </label>
                                        <select class="selectpicker" data-live-search="true" name="emp_job_in_identity"
                                                id="emp_job_in_identity">
                                            @foreach($sys_codes_job_identity as $sys_code_job_identity)
                                                <option value="{{$sys_code_job_identity->system_code}}"
                                                        @if($sys_code_job_identity->system_code ==
                                                         $employee->emp_job_in_identity )
                                                        selected @endif>
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_code_job_identity->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_job_identity->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('waybill.emp_category') </label>
                                        <select class="form-select form-control" name="emp_category"
                                                id="emp_category">
                                            @foreach($sys_codes_emp_category as $sys_code_emp_category)
                                                <option value="{{$sys_code_emp_category->system_code}}"
                                                        @if($sys_code_emp_category->system_code ==
                                                         $employee->emp_category )
                                                        selected @endif>
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_code_emp_category->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_emp_category->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.previous_sponsor_phone') </label>
                                        <input type="number" class="form-control"
                                               name="emp_previous_sponsor_phone"
                                               id="emp_previous_sponsor_phone"
                                               value="{{ $employee->emp_previous_sponsor_phone }}">
                                    </div>

                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">


                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.bank_list') </label>
                                        <select class="selectpicker" data-live-search="true" name="emp_bank_id"
                                                id="emp_bank_id">
                                            <option value="" selected> choose</option>
                                            @foreach($sys_codes_banks as $sys_codes_bank)
                                                <option value="{{$sys_codes_bank->system_code}}"
                                                        @if($employee->emp_bank_id == $sys_codes_bank->system_code) selected @endif >
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_codes_bank->system_code_name_ar}}
                                                    @else
                                                        {{$sys_codes_bank->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.bank_account') </label>
                                        <input type="text" class="form-control" name="emp_bank_account"
                                               id="emp_bank_account" value="{{ $employee->emp_bank_account }}">

                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-check text-center mt-40">
                                            <input class="form-check-input" type="checkbox"
                                                   style=" margin-right: -1.25rem;"
                                                   @if($employee->emp_is_bank_payment) checked @endif
                                                   name="emp_is_bank_payment" id="defaultCheck2">
                                            <label class="form-check-label"
                                                   for="defaultCheck2"> @lang('home.bank_payment')</label>
                                        </div>

                                    </div>

                                </div>
                            </div>


                            <div class="mb-3">
                                <div class="row">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.work_end_date') : <span
                                                class="text-success">{{ $employee->emp_work_end_date }}</span>
                                    </label>
                                    <input type="date" class="form-control" name="emp_work_end_date"
                                           id="emp_work_end_date" v-model="emp_work_end_date"
                                           value="{{ $employee->emp_work_end_date }}"
                                           @change="getWorkEndDate()" autocomplete="off">
                                </div>
                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('home.hijri_end_date') : <span
                                                class="text-success">{{ $employee->emp_hijri_end_date }}</span>
                                    </label>
                                    <input type="text" class="form-control" name="emp_hijri_end_date"
                                           id="emp_hijri_end_date" v-model="emp_hijri_end_date"
                                           value="{{$employee->emp_hijri_end_date}}" autocomplete="off">
                                </div>
                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('home.reason_leaving') </label>
                                    <select class="form-select form-control" name="emp_reason_leaving"
                                            id="emp_reason_leaving">
                                        <option value="" selected>Choose</option>
                                        @foreach($sys_codes_reasons_leaving as $sys_c_reasons_leaving)
                                            <option value="{{$sys_c_reasons_leaving->system_code}}"
                                                    @if($sys_c_reasons_leaving->system_code ==
                                                     $employee->emp_reason_leaving )
                                                    selected @endif>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$sys_c_reasons_leaving->system_code_name_ar}}
                                                @else
                                                    {{$sys_c_reasons_leaving->system_code_name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>

                                </div>

                                @if($employee->contractActive)
                                    <div class="col-md-3">
                                        <div class="form-check text-center mt-40">
                                            <input class="form-check-input" type="checkbox"
                                                   style=" margin-right: -1.25rem;"
                                                   @if($employee->emp_is_user_application) checked @endif
                                                   name="emp_is_user_application" id="defaultCheck1">
                                            <label class="form-check-label"
                                                   for="defaultCheck1"> @lang('home.add_to_user')</label>
                                        </div>

                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <div class="row">

                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary mr-2"
                                        id="create_emp">@lang('home.save')</button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-------------contracts-grid-------------------------------------------------------------------------}}
                <div class="tab-pane fade @if(request()->qr == 'contracts') active show @endif"
                     id="contracts-grid{{$employee->emp_id}}"
                     role="tabpanel">

                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">@lang('home.contract_data')</h3>
                                </div>

                                <div class="card-body">

                                    <div class="md-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <a href="{{route('employees-contracts-create',
                                                $employee->emp_id)}}" class="btn btn-primary mr-5 mt-3">
                                                    <i class="fe fe-plus mr-2"></i>@lang('home.add_contract')
                                                </a>
                                            </div>
                                            {{--readonly--}}
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('home.name') </label>
                                                <input type="text" class="form-control" name="emp_name_full_ar"
                                                       value=" {{$employee->emp_name_full_ar}}"
                                                       readonly>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('home.emp_code') </label>
                                                <input type="text" class="form-control" name="emp_code"
                                                       value=" {{$employee->emp_code}}" readonly>
                                            </div>
                                            {{--readonly--}}
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-vcenter text-nowrap mb-0">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>@lang('home.contract_type')</th>
                                                <th>@lang('home.sub_company')</th>
                                                <th>@lang('home.branch')</th>
                                                <th>@lang('home.job')</th>
                                                <th>@lang('home.salary_total')</th>
                                                <th>@lang('home.status')</th>
                                                <th>@lang('home.from')</th>
                                                <th>@lang('home.to')</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @if(count($employee->contracts) > 0)
                                                @foreach($employee->contracts as $k=>$contract)
                                                    <tr>
                                                        <td>{{ $k+1 }}</td>
                                                        <td>
                                                            @if(app()->getLocale() == 'ar')
                                                                {{ $contract->contractType->system_code_name_ar }}@else
                                                                {{ $contract->contractType->system_code_name_en }}@endif
                                                        </td>
                                                        <td>
                                                            @if(app()->getLocale() == 'ar')
                                                                {{ $contract->company->company_name_ar }}@else
                                                                {{ $contract->company->company_name_en }}@endif
                                                        </td>
                                                        <td>
                                                            @if(app()->getLocale() == 'ar')
                                                                {{ $contract->branch->branch_name_ar }}@else
                                                                {{ $contract->branch->branch_name_en }}@endif
                                                        </td>
                                                        <td>
                                                            @if(app()->getLocale() == 'ar')
                                                                {{ $contract->job->job_name_ar }}@else
                                                                {{ $contract->job->job_name_en }}@endif
                                                        </td>
                                                        <td>
                                                            {{array_sum($contract->salaries->pluck('emp_salary_credit')->toArray()) -
                                                                       array_sum($contract->salaries->pluck('emp_salary_debit')->toArray()) }}
                                                        </td>
                                                        <td>
                                                            @if($contract->emp_contract_is_active)
                                                                <i class="fa fa-check"></i>
                                                            @else
                                                                <i class="fa fa-remove"></i>
                                                            @endif
                                                        </td>
                                                        <td>{{ $contract->emp_contract_start_date }}</td>
                                                        <td>{{ $contract->emp_contract_end_date }}</td>

                                                        <td>
                                                            <button id="contract{{ $contract->emp_contract_id }}"
                                                                    onclick="show(this)"
                                                                    {{--@click="add({!! $contract->totalSalary !!} ,--}}
                                                                    {{--{!! $contract->creditSalary !!} , {!! $contract->depitSalary !!})"--}}
                                                                    class="btn btn-success btn-sm">
                                                                @lang('home.view')
                                                            </button>
                                                        </td>

                                                        @if($contract->salaries->count() > 0)
                                                            <td>

                                                                <a href="{{route('employees-contracts-edit',$contract->emp_contract_id )}}"
                                                                   class="btn btn-primary btn-sm">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                            </td>
                                                        @endif

                                                        <td>

                                                            @if ($employee->emp_nationality == 25)

                                                                <a
                                                                        href="{{config('app.telerik_server')}}?rpt={{$employee->Report_contract_emp_sa->report_url}}&contract_id={{$contract->emp_contract_id}}&lang=ar&skinName=bootstrap"
                                                                        title="{{trans('Print')}}"
                                                                        class="btn btn-circle btn-default red-flamingo"
                                                                        id="showReport" target="_blank">
                                                                    {{trans('Print')}}
                                                                </a>
                                                            @else
                                                                <a
                                                                        href="{{config('app.telerik_server')}}?rpt={{$employee->Report_contract_emp->report_url}}&contract_id={{$contract->emp_contract_id}}&lang=ar&skinName=bootstrap"
                                                                        title="{{trans('Print')}}"
                                                                        class="btn btn-circle btn-default red-flamingo"
                                                                        id="showReport" target="_blank">
                                                                    {{trans('Print')}}
                                                                </a>
                                                            @endif

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif


                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                                <div>


                                    @foreach($employee->contracts as $k=>$contract)

                                        <div class="card-body" id="cont-contract{{ $contract->emp_contract_id }}"
                                             style="display: none">
                                            <div class="md-3">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="recipient-name"
                                                               class="col-form-label "> @lang('home.number') </label>
                                                        <input type="text" class="form-control" name=""
                                                               id="" value="@lang('home.number') : {{ $k+1 }}"
                                                               readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <form action="{{ route('employee-contract-salary') }}" method="post">
                                                    @csrf
                                                    <table class="table table-vcenter text-nowrap mb-0">
                                                        <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>@lang('home.salary_details')</th>
                                                            <th>@lang('home.credit')</th>
                                                            <th>@lang('home.debit')</th>
                                                            <th>@lang('home.from')</th>
                                                            <th>@lang('home.to')</th>
                                                            <th>@lang('home.created_user')</th>
                                                            <th></th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>

                                                        @foreach($contract->salaries as $k=>$salary)
                                                            <tr>
                                                                <td>{{ $k+1 }}</td>
                                                                <td>{{ optional($salary->salaryItem)->system_code_name_ar }}</td>
                                                                <td>{{ $salary->emp_salary_credit }}</td>
                                                                <td>{{ $salary->emp_salary_debit }}</td>
                                                                <td>{{ $salary->emp_contract_start }}</td>
                                                                <td>{{ $salary->emp_contract_end }}</td>
                                                                <td>{{ $salary->user->user_name_ar }}</td>
                                                                <td></td>
                                                            </tr>
                                                        @endforeach


                                                        <tr v-for="(element,index) in emp_contract_salary">

                                                            <input type="hidden" name="emp_id"
                                                                   value="{{ $employee->emp_id }}">
                                                            <input type="hidden" name="emp_contract_id"
                                                                   value="{{ $contract->emp_contract_id }}">

                                                            <td>@{{ index+1 }}</td>
                                                            <td>
                                                                @foreach($employees as $employee_n)
                                                                    @php $salary_details=\App\Models\SystemCode::where('sys_category_id',25)
                                                                    ->where('company_group_id', $employee_n->company_group_id)->get();
                                                                    @endphp
                                                                @endforeach
                                                                <select class="form-control" required
                                                                        v-model="emp_contract_salary[index]['emp_salary_item_id']"
                                                                        name="emp_salary_item_id[]">
                                                                    <option value="">@lang('home.choose')</option>
                                                                    @foreach($salary_details as $salary_detail)
                                                                        <option value="{{$salary_detail->system_code}}">
                                                                            {{ $salary_detail->system_code_name_ar }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td><input type="number" class="form-control" min="0"
                                                                       required  step="0.01" value="0.00"
                                                                       v-model="emp_contract_salary[index]['emp_salary_credit']"
                                                                       name="emp_salary_credit[]"></td>

                                                            <td><input type="number" class="form-control" min="0"
                                                                       required step="0.01" value="0.00"
                                                                       v-model="emp_contract_salary[index]['emp_salary_debit']"
                                                                       name="emp_salary_debit[]"></td>
                                                            <td><input type="date" class="form-control"
                                                                       value="{{ $contract->emp_contract_start_date }}"
                                                                       readonly></td>
                                                            <td><input type="date" class="form-control"
                                                                       value="{{ $contract->emp_contract_end_date }}"
                                                                       readonly></td>
                                                            <td>
                                                                <button type="button"
                                                                        class="btn btn-success btn-sm mr-1 ml-1"
                                                                        @click="addRow()">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                        class="btn btn-success btn-sm mr-1 ml-1"
                                                                        @click="supRow(index)" v-if="index>0">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>

                                                            </td>
                                                            <td></td>
                                                        </tr>

                                                        <tr>
                                                        <td colspan="2"><span
                                                                        style="font-size:20px;font-weight: bold">@lang('home.credit')
                                                                    : @{{ totalCredit }}</span>
                                                                   
                                                        </td>

                                                            <td colspan="2"><span
                                                                        style="font-size:20px;font-weight: bold">@lang('home.debit')
                                                                    : @{{ totalDepit }}</span></td>

                                                            <td colspan="2"><span
                                                                        style="font-size:20px;font-weight: bold">@lang('home.salary_total')
                                                                    : @{{ total }}</span></td>
                                                        </tr>


                                                        </tbody>
                                                    </table>

                                                    <div class="row">
                                                        <button type="submit"
                                                                class="btn btn-primary mr-3 ml-3">@lang('home.save')</button>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>

                                    @endforeach
                                    {{--{{dd($employee)}}--}}
                                </div>

                            </div>
                        </div>
                    </div>


                </div>


                {{------------certificates-grid------------------------------------------------------------------------}}
                <div class="tab-pane fade @if(request()->qr == 'certificates') active show @endif"
                     id="certificates-grid{{$employee->emp_id}}" role="tabpanel">
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">@lang('home.certificates')</h3>
                                </div>
                                <div class="card-body">

                                    <div class="md-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <a href="{{route('employee-certificates-create',
                                                $employee->emp_id)}}" class="btn btn-primary mr-5 mt-3">
                                                    <i class="fe fe-plus mr-2"></i>@lang('home.add_certificate')
                                                </a>
                                            </div>

                                            {{--readonly--}}
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('home.name') </label>
                                                <input type="text" class="form-control" name="emp_name_full_ar"
                                                       value=" {{$employee->emp_name_full_ar}}"
                                                       readonly>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('home.emp_code') </label>
                                                <input type="text" class="form-control" name="emp_code"
                                                       value=" {{$employee->emp_code}}" readonly>
                                            </div>
                                            {{--readonly--}}
                                        </div>
                                    </div>

                                    <div class="table-responsive">


                                        <table class="table text-nowrap mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>@lang('home.country')</th>
                                                <th>@lang('home.certificate_collage')</th>
                                                <th>@lang('home.certificate_type')</th>
                                                <th>@lang('home.certificate_duration')</th>
                                                <th>@lang('home.from')</th>
                                                <th>@lang('home.to')</th>
                                                <th>@lang('home.download_file')</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($employee->certificates as $k => $certificate)
                                                <tr>
                                                    <td>{{$k+1}}</td>
                                                    <td>
                                                        @if(app()->getLocale() == 'ar')
                                                            {{$certificate->sys_code_country->system_code_name_ar}}
                                                        @else
                                                            {{$certificate->sys_code_country->system_code_name_en}}
                                                        @endif
                                                    </td>
                                                    <td>{{$certificate->emp_certificate_collage}}</td>
                                                    <td>{{$certificate->emp_certificate_type}}</td>
                                                    <td>{{$certificate->emp_certificate_duration}}</td>
                                                    <td>{{$certificate->emp_certificate_start_date}}</td>
                                                    <td>{{$certificate->emp_certificate_end_date}}</td>
                                                    <td>
                                                        <a class="btn btn-link-block"
                                                           href="{{ url('/employee-certificates/download?name=' .
                                                            $certificate->emp_certificate_url) }}">
                                                            <i class="fa fa-download fa-2x"></i>
                                                        </a>

                                                    </td>
                                                    <td>
                                                        <a class="btn btn-link-block"
                                                           href="{{route('employee-certificates-edit' , $certificate->emp_certificate_id)}}">
                                                            <i class="fa fa-edit fa-2x m-1"></i>
                                                        </a>
                                                        <form class="d-inline-block"
                                                              action="{{route('employee-certificates-delete' , $certificate->emp_certificate_id)}}"
                                                              method="post">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="btn btn-link-block">
                                                                <i class="fa fa-trash fa-2x m-1"></i>
                                                            </button>
                                                        </form>
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

                {{------------Practical_Experiences_grid---------------------------------------------------------------}}
                <div class="tab-pane fade @if(request()->qr == 'experiences') active show @endif"
                     id="practical-experiences-grid{{$employee->emp_id}}"
                     role="tabpanel">

                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">@lang('home.practical_experiences')</h3>
                                </div>
                                <div class="card-body">

                                    <div class="md-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <a href="{{route('employee-experience-create',
                                                $employee->emp_id)}}" class="btn btn-primary mr-5 mt-3">
                                                    <i class="fe fe-plus mr-2"></i>@lang('home.add')
                                                </a>
                                            </div>
                                            {{--readonly--}}
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('home.name') </label>
                                                <input type="text" class="form-control" name="emp_name_full_ar"
                                                       value=" {{$employee->emp_name_full_ar}}"
                                                       readonly>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('home.emp_code') </label>
                                                <input type="text" class="form-control" name="emp_code"
                                                       value=" {{$employee->emp_code}}" readonly>
                                            </div>
                                            {{--readonly--}}
                                        </div>
                                    </div>


                                    <div class="table-responsive">


                                        <table class="table text-nowrap mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>@lang('home.job')</th>
                                                <th>@lang('home.experience_company')</th>
                                                <th>@lang('home.country')</th>
                                                <th>@lang('home.period')</th>
                                                <th>@lang('home.salary')</th>
                                                <th>@lang('home.from')</th>
                                                <th>@lang('home.to')</th>
                                                <th>@lang('home.reason_leaving')</th>
                                                <th>@lang('home.download_file')</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($employee->experience as $k=> $experience )
                                                <tr>

                                                    <td>{{$k+1}}</td>
                                                    <td>{{$experience->emp_experience_job}}</td>
                                                    <td>{{$experience->emp_experience_company}}</td>
                                                    <td>{{$experience->emp_experience_country}}</td>
                                                    <td>{{$experience->emp_experience_period}}</td>
                                                    <td>{{$experience->emp_experience_salary}}</td>
                                                    <td>{{$experience->emp_experience_start_date}}</td>
                                                    <td>{{$experience->emp_experience_end_date}}</td>
                                                    <td>{{$experience->emp_experience_leave_reason}}</td>
                                                    <td>
                                                        <a class="btn btn-link-block"
                                                           href="{{ url('/employee-experience/download?name=' .
                                                            $experience->emp_experience_file_url) }}">
                                                            <i class="fa fa-download fa-2x"></i>
                                                        </a>

                                                    </td>
                                                    <td>
                                                        <a class="btn btn-link-block"
                                                           href="{{route('employee-experience-edit' , $experience->emp_experience_id)}}">
                                                            <i class="fa fa-edit fa-2x m-1"></i>
                                                        </a>
                                                        <form class="d-inline-block"
                                                              action="{{route('employee-experience-delete' , $experience->emp_experience_id)}}"
                                                              method="post">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="btn btn-link-block">
                                                                <i class="fa fa-trash fa-2x m-1"></i>
                                                            </button>
                                                        </form>
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

                {{------------Employee_Requests_grid-------------------------------------------------------------------}}
                <div class="tab-pane fade @if($qr == 'requests') active show @endif"
                     id="employee-requests-grid{{$employee->emp_id}}"
                     role="tabpanel">

                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">@lang('home.employee_requests')</h3>
                                </div>

                                <div class="card">
                                    <form action="">

                                        <div class="card-body demo-card">
                                            <div class="row clearfix">
                                                <div class="col-lg-4 col-md-12">
                                                    <label>@lang('home.choose_request_type')</label>
                                                    <div class="form-group multiselect_div">
                                                        <select name="emp_request_type_id" id="request_type"
                                                                class="form-control">
                                                            <option value="">@lang('home.choose')</option>
                                                            @foreach($request_types as $request_type )
                                                                <option value="{{$request_type->system_code_id}}">
                                                                    {{app()->getLocale() == 'ar' ? $request_type->system_code_name_ar
                                                                     : $request_type->system_code_name_en}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-12 ">
                                                    <div class="form-group multiselect_div">
                                                        <button type="submit"
                                                                class="btn btn-primary mt-4 mr-3 ml-3">@lang('home.search')</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>


                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table text-nowrap mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>@lang('home.request_code')</th>
                                                <th>@lang('home.request_type')</th>
                                                <th>@lang('home.employee_name')</th>
                                                <th>@lang('home.request_date')</th>
                                                <th>@lang('home.status')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach( $employee_requests as $k=>$employee_request)
                                                <tr>
                                                    <td>{{$k +1}}</td>
                                                    <td>{{$employee_request->emp_request_code }}</td>
                                                    <td>{{app()->getLocale() == 'ar' ? $employee_request->requestType->system_code_name_ar
                                         : $employee_request->requestType->system_code_name_en}}</td>
                                                    <td>{{app()->getLocale() == 'ar' ? $employee_request->employee->emp_name_full_ar
                                        : $employee_request->employee->emp_name_full_en}}</td>
                                                    <td>{{$employee_request->emp_request_date}}</td>
                                                    <td>
                                                        @if($employee_request->emp_request_approved)
                                                            تم الموافقه علي الطلب
                                                        @endif
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

                {{------------Practical_attachment_grid---------------------------------------------------------------}}
                <div class="tab-pane fade " id="attachments-grid{{$employee->emp_id}}" role="tabpanel">
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
                                                           value=" {{$employee->emp_name_full_ar}}"
                                                           readonly>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="recipient-name"
                                                           class="col-form-label "> @lang('home.emp_code') </label>
                                                    <input type="text" class="form-control" name="emp_code"
                                                           value=" {{$employee->emp_code}}" readonly>
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
                                <input type="hidden" name="transaction_id" value="{{$employee->emp_id}}">
                                <input type="hidden" name="app_menu_id" value="8">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('home.attachment_type')</label>
                                        <select class="form-control" name="attachment_type" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($attachment_types as $attachment_type)
                                                <option value="{{ $attachment_type->system_code }}">{{app()->getLocale()=='ar' ? $attachment_type->system_code_name_ar
                                                     : $attachment_type->system_code_name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </x-files.form>

                            

                            <x-files.attachment>

                                @foreach($attachments as $attachment)

                                    <tr>
                                    <td>{{ $attachment->attachment_id }}</td>
                                        <td>
                                            {{--{{ app()->getLocale()=='ar' ?--}}
                                         {{--$attachment->attachmentType->system_code_name_ar :--}}
                                          {{--$attachment->attachmentType->system_code_name_en}}--}}
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
                                               target="_blank" class="mr-1 ml-1"><i
                                                        class="fa fa-eye text-info mr-3 ml-3"
                                                        style="font-size:20px"></i></a>
                                                        </td>
                                                        <td>            
                                                        @if(auth()->user()->user_type_id != 1)
                                                @foreach(session('job')->permissions as $job_permission)
                                                    @if($job_permission->app_menu_id == 8 && $job_permission->permission_add)
                                                        <a class="btn btn-sm btn-icon on-default m-r-5 button-edit"
                                                           href="{{route('attachments.edit',$attachment->attachment_id)}}"
                                                           title="@lang('home.edit')">
                                                            <i class="icon-pencil"></i>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            @endif

                                            @if(auth()->user()->user_type_id == 1)
                                                <a class="btn btn-sm btn-icon on-default m-r-5 button-edit"
                                                   href="{{route('attachments.edit',$attachment->attachment_id)}}"
                                                   title="@lang('home.edit')">
                                                    <i class="icon-pencil"></i>
                                                </a>
                                            @endif
                                                    
                                                        
                                            <form action="{{ route('employees-attachment.delete',$attachment->attachment_id) }}"
                                                  method="post">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-sm btn-icon on-default button-remove"
                                                         
                                                        type="submit" data-original-title="Remove"><i
                                                            class="icon-trash" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                           

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

                {{------------Practical_notes_grid--------------------------------------------------------------------}}
                <div class="tab-pane fade" id="notes-grid{{$employee->emp_id}}" role="tabpanel">
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
                                                           value=" {{$employee->emp_name_full_ar}}"
                                                           readonly>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="recipient-name"
                                                           class="col-form-label "> @lang('home.emp_code') </label>
                                                    <input type="text" class="form-control" name="emp_code"
                                                           value=" {{$employee->emp_code}}" readonly>
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

                                <input type="hidden" name="transaction_id" value="{{$employee->emp_id}}">
                                <input type="hidden" name="app_menu_id" value="8">


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
    <script src="{{asset('assets/plugins/dropify/js/dropify.min.js')}}"></script>
    <script src="{{asset('assets/js/form/form-advanced.js')}}"></script>
    <script>
        $(function () {
            $("#issue_date_hijri").hijriDatePicker();
            $("#expire_date_hijri").hijriDatePicker();
            $("#emp_hijri_start_date").hijriDatePicker();
            $("#emp_hijri_end_date").hijriDatePicker();
            $("#emp_birthday_hijiri").hijriDatePicker();
        });

        function displayRow(el) {
            $(el).closest('tr').next().removeClass('d-none');
        }

        function RemoveRow(el) {
            $(el).closest('tr').addClass('d-none');
        }

        function show(el) {
            var x = el.id;
            $("#cont-" + x).css("display", "block");
            $("#cont-" + x).siblings().css('display', 'none')
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

            $('#edit_files').click(function () {
                var display = $("#edit_files_form").css("display");
                if (display == 'none') {
                    $('#edit_files_form').css('display', 'block')
                } else {
                    $('#edit_files_form').css('display', 'none')
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

            //    validation to create Employee
            $('#emp_code').keyup(function () {
                if ($('#emp_code').val().length < 3) {
                    $('#emp_code').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_code').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_name_full_ar').change(function () {
                if ($('#emp_name_full_ar').val().length < 3) {
                    $('#emp_name_full_ar').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_name_full_ar').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_name_full_en').change(function () {
                if ($('#emp_name_full_en').val().length < 3) {
                    $('#emp_name_full_en').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_name_full_en').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_name_1_ar').keyup(function () {
                if ($('#emp_name_1_ar').val().length < 3) {
                    $('#emp_name_1_ar').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_name_1_ar').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_name_2_ar').keyup(function () {
                if ($('#emp_name_2_ar').val().length < 3) {
                    $('#emp_name_2_ar').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_name_2_ar').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_name_3_ar').keyup(function () {
                if ($('#emp_name_3_ar').val().length < 0) {
                    $('#emp_name_3_ar').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_name_3_ar').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_name_4_ar').keyup(function () {
                if ($('#emp_name_4_ar').val().length < 0) {
                    $('#emp_name_4_ar').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_name_4_ar').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_name_1_en').keyup(function () {
                if ($('#emp_name_1_en').val().length < 3) {
                    $('#emp_name_1_en').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_name_1_en').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_name_2_en').keyup(function () {
                if ($('#emp_name_2_en').val().length < 3) {
                    $('#emp_name_2_en').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_name_2_en').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_name_3_en').keyup(function () {
                if ($('#emp_name_3_en').val().length < 0) {
                    $('#emp_name_3_en').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_name_3_en').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_name_4_en').keyup(function () {
                if ($('#emp_name_4_en').val().length < 0) {
                    $('#emp_name_4_en').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_name_4_en').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_nationality').change(function () {
                if (!$('#emp_nationality').val()) {
                    $('#emp_nationality').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_nationality').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_identity').keyup(function () {
                if ($('#emp_identity').val().length < 14) {
                    $('#emp_identity').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_identity').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_status').change(function () {
                if (!$('#emp_status').val()) {
                    $('#emp_status').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_status').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_social_status').change(function () {
                if (!$('#emp_social_status').val()) {
                    $('#emp_social_status').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_social_status').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_work_start_date').change(function () {
                $('#emp_work_start_date').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            });

            $('#emp_work_end_date').change(function () {
                $('#emp_work_end_date').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            });

            $('#emp_family_count').change(function () {
                if (!$('#emp_family_count').val()) {
                    $('#emp_family_count').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_family_count').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_hijri_end_date').change(function () {
                $('#emp_hijri_end_date').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            });


            $('#emp_vacation_balance').keyup(function () {
                if ($('#emp_vacation_balance').val().length < 2) {
                    $('#emp_vacation_balance').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_vacation_balance').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_religion').change(function () {
                if (!$('#emp_religion').val()) {
                    $('#emp_religion').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_religion').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_birthday').change(function () {
                $('#emp_birthday').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            });

            $('#emp_birthday_hijiri').change(function () {
                $('#emp_birthday_hijiri').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            });

            $('#emp_direct_date').change(function () {
                $('#emp_direct_date').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            });

            // $('#emp_age').keyup(function () {
            //     if ($('#emp_age').val().length < 2) {
            //         $('#emp_age').addClass('is-invalid')
            //         $('#create_emp').attr('disabled', 'disabled')
            //     } else {
            //         $('#emp_age').removeClass('is-invalid')
            //         $('#create_emp').removeAttr('disabled', 'disabled')
            //     }
            // });

            $('#emp_birth_country').change(function () {
                if (!$('#emp_birth_country').val()) {
                    $('#emp_birth_country').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_birth_country').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_birth_city').keyup(function () {
                if ($('#emp_birth_city').val().length < 3) {
                    $('#emp_birth_city').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_birth_city').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_birth_address').keyup(function () {
                if ($('#emp_birth_address').val().length < 3) {
                    $('#emp_birth_address').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_birth_address').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_private_mobile').keyup(function () {
                if ($('#emp_private_mobile').val().length < 10) {
                    $('#emp_private_mobile').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_private_mobile').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_po_box_postal').keyup(function () {
                if ($('#emp_po_box_postal').val().length < 3) {
                    $('#emp_po_box_postal').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_po_box_postal').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_current_address').keyup(function () {
                if ($('#emp_current_address').val().length < 3) {
                    $('#emp_current_address').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_current_address').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_work_mobile').keyup(function () {
                if ($('#emp_work_mobile').val().length < 10) {
                    $('#emp_work_mobile').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_work_mobile').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_email_work').keyup(function () {
                if (!validEmail($('#emp_email_work').val())) {
                    $('#emp_email_work').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_email_work').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_email_private').keyup(function () {
                if (!validEmail($('#emp_email_private').val())) {
                    $('#emp_email_private').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_email_private').removeClass('is-invalid');
                    $('#create_emp').removeAttr('disabled');
                }
            });

            $('#emp_sponsor_name').change(function () {
                if (!$('#emp_sponsor_name').val()) {
                    $('#emp_sponsor_name').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_sponsor_name').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_previous_sponsor_name').keyup(function () {
                if ($('#emp_previous_sponsor_name').val().length < 3) {
                    $('#emp_previous_sponsor_name').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_previous_sponsor_name').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_previous_sponsor_phone').keyup(function () {
                if ($('#emp_previous_sponsor_phone').val().length < 11) {
                    $('#emp_previous_sponsor_phone').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_previous_sponsor_phone').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_job_in_identity').change(function () {
                if (!$('#emp_job_in_identity').val()) {
                    $('#emp_job_in_identity').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_job_in_identity').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#company_group_id').change(function () {
                if (!$('#company_group_id').val()) {
                    $('#company_group_id').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#company_group_id').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });


            $('#emp_default_company_id').change(function () {
                if (!$('#emp_default_company_id').val()) {
                    $('#emp_default_company_id').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_default_company_id').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_default_branch_id').change(function () {
                if (!$('#emp_default_branch_id').val()) {
                    $('#emp_default_branch_id').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_default_branch_id').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });


            $('#emp_manager_id').change(function () {
                if (!$('#emp_manager_id').val()) {
                    $(this).addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $(this).removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            })

            $('#emp_bank_id').change(function () {
                if (!$('#emp_bank_id').val()) {
                    $('#emp_bank_id').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_bank_id').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_bank_account').keyup(function () {
                if ($('#emp_bank_account').val().length < 12) {
                    $('#emp_bank_account').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_bank_account').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            function validEmail(email) {
                var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }

        })

    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>

        new Vue({
            el: '#app',
            data: {
                employee: {},
                company_id: '',
                branches: {},
                companies: {},
                company_group_id: '',
                expire_date: '',
                issue_date: '',
                issue_date_hijri: '',
                expire_date_hijri: '',
                emp_work_start_date: '',
                emp_hijri_start_date: '',
                emp_work_end_date: '',
                emp_hijri_end_date: '',
                emp_birthday: '',
                emp_birthday_hijiri: '',
                emp_default_branch_id: '',
                emp_name_1_ar: '',
                emp_name_2_ar: '',
                emp_name_3_ar: '',
                emp_name_4_ar: '',
                emp_name_1_en: '',
                emp_name_2_en: '',
                emp_name_3_en: '',
                emp_name_4_en: '',
                emp_contract_salary: [{
                    'emp_salary_item_id': '',
                    'emp_salary_credit': 0.00,
                    'emp_salary_debit': 0.00,
                }],
                contract_depit_salary: '',
                contract_credit_salary: '',
                contract_total_salary: '',
                year: 0,
                month: 0,
                day: 0,
                system_codes: {},
                emp_id: '',
                days_available: ''

            },
            mounted() {
                //  console.log(window.location.pathname.split('/')[2])

                // setTimeout(() => {
                //
                // }, 5000);

                console.log(this.employee)

                this.emp_id = {!! $id !!}

                    this.getWorkStartDate()

                this.getEmployee()

                $('#issue_date_hijri').on("dp.change", (e) => {
                    this.issue_date_hijri = $('#issue_date_hijri').val()
                    this.getGeorgianDate()
                });

                $('#expire_date_hijri').on("dp.change", (e) => {
                    this.expire_date_hijri = $('#expire_date_hijri').val()
                    this.getGeorgianDate2()
                });

                $('#emp_hijri_start_date').on("dp.change", (e) => {
                    this.emp_hijri_start_date = $('#emp_hijri_start_date').val()
                    this.getWorkStartDateGeorgian()
                });

                $('#emp_hijri_end_date').on("dp.change", (e) => {
                    this.emp_hijri_end_date = $('#emp_hijri_end_date').val()
                    this.getWorkEndDateGeorgian()
                });

                $('#emp_birthday_hijiri').on("dp.change", (e) => {
                    this.emp_birthday_hijiri = $('#emp_birthday_hijiri').val()
                    this.getBirthdayDateGeorgian()
                });


            },
            methods: {
                getEmployee() {
                    if (this.emp_id) {
                        $.ajax({
                            type: 'GET',
                            data: {emp_id: this.emp_id},
                            url: '{{ route('get-employees') }}'
                        }).then(response => {

                            this.days_available = response.days_available

                            this.employee = response.employee
                            this.emp_name_1_ar = this.employee.emp_name_1_ar
                            this.emp_name_2_ar = this.employee.emp_name_2_ar
                            this.emp_name_3_ar = this.employee.emp_name_3_ar
                            this.emp_name_4_ar = this.employee.emp_name_4_ar
                            this.emp_name_1_en = this.employee.emp_name_1_en
                            this.emp_name_2_en = this.employee.emp_name_2_en
                            this.emp_name_3_en = this.employee.emp_name_3_en
                            this.emp_name_4_en = this.employee.emp_name_4_en
                            this.company_id = this.employee.emp_default_company_id
                            this.emp_default_branch_id = this.employee.emp_default_branch_id
                            this.emp_work_start_date = this.employee.emp_work_start_date

                            if (this.emp_work_start_date) {

                                this.getWorkStartDate()

                            }

                            this.getBranches()

                        })
                    }
                },

                getSystemCodes() {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route("api.system-codes.get") }}'
                    }).then(response => {
                        this.system_codes = response.data
                    })
                },

                add(contract_total, contract_credit, contract_depit) {
                    this.contract_total_salary = contract_total
                    this.contract_credit_salary = contract_credit
                    this.contract_depit_salary = contract_depit
                },

                getCompanies() {
                    $.ajax({
                        type: 'GET',
                        data: {id: this.company_group_id},
                        url: '{{ route("api.company-group.companies") }}'
                    }).then(response => {
                        this.companies = response.data
                    })

                },

                getBranches() {
                    $.ajax({
                        type: 'GET',
                        data: {company_id: this.company_id},
                        url: '{{ route("api.company.branches") }}'
                    }).then(response => {
                        this.branches = response.data
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
                },

                getstaartDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.emp_work_start_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.emp_hijri_start_date = response.data
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

                getWorkStartDate() {

                    $.ajax({
                        type: 'GET',
                        data: {start_date: this.emp_work_start_date},
                        url: '{{ route("api.getDiffDate") }}'
                    }).then(response => {
                        this.day = response.day
                        this.month = response.month
                        this.year = response.year
                    })
                    if (this.emp_work_start_date) {
                        $.ajax({
                            type: 'GET',
                            data: {date: this.emp_work_start_date},
                            url: '{{ route("api.getDate") }}'
                        }).then(response => {
                            this.emp_hijri_start_date = response.data
                        })
                    }

                },

                getWorkStartDateGeorgian() {
                    if (this.emp_hijri_start_date) {
                        $.ajax({
                            type: 'GET',
                            data: {date: this.emp_hijri_start_date},
                            url: '{{ route("test-date") }}'
                        }).then(response => {
                            this.emp_work_start_date = response.data
                        })
                    }
                },

                getWorkEndDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.emp_work_end_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.emp_hijri_end_date = response.data
                    })
                },

                getWorkEndDateGeorgian() {
                    if (this.emp_hijri_end_date) {
                        $.ajax({
                            type: 'GET',
                            data: {date: this.emp_hijri_end_date},
                            url: '{{ route("test-date") }}'
                        }).then(response => {
                            this.emp_work_end_date = response.data
                        })
                    }
                },

                getBirthdayDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.emp_birthday},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.emp_birthday_hijiri = response.data
                    })
                },

                getBirthdayDateGeorgian() {
                    if (this.emp_birthday_hijiri) {
                        $.ajax({
                            type: 'GET',
                            data: {date: this.emp_birthday_hijiri},
                            url: '{{ route("test-date") }}'
                        }).then(response => {
                            this.emp_birthday = response.data
                        })
                    }
                },

                addRow() {
                    this.emp_contract_salary.push({
                        'emp_salary_item_id': '',
                        'emp_salary_credit': 0.00,
                        'emp_salary_debit': 0.00,
                    })

                },

                supRow(index) {
                    this.emp_contract_salary.splice(index, 1)

                },

                getContractsSalaries() {
                    $.ajax({
                        type: 'GET',
                        url: ''
                    }).then(response => {
                        this.contracts = response.data
                    })
                }

            },
            computed: {

                totalCredit: function () {
                    var sum_credit = this.contract_credit_salary;
                    Object.entries(this.emp_contract_salary).forEach(([key, val]) => {
                        sum_credit += (parseFloat(val.emp_salary_credit))
                    });
                    return sum_credit;
                },

                totalDepit: function () {
                    var sum_depit =this.contract_depit_salary;

                        Object.entries(this.emp_contract_salary).forEach(([key, val]) => {
                            sum_depit += (parseFloat(val.emp_salary_debit))
                    });
                    return sum_depit;
                },

                total: function () {
                    var  totalall =  this.totalCredit - this.totalDepit
                    return totalall.toFixed(2);
                  
                },

                emp_name_full_ar: function () {
                    // `this` points to the vm instance

                    if (this.emp_name_2_ar == null) {
                        this.emp_name_2_ar = ''
                    }
                    if (this.emp_name_3_ar == null) {
                        this.emp_name_3_ar = ''
                    }
                    if (this.emp_name_4_ar == null) {
                        this.emp_name_4_ar = ''
                    }
                    var str = this.emp_name_1_ar + ' ' + this.emp_name_2_ar + ' ' + this.emp_name_3_ar + ' ' + this.emp_name_4_ar
                    if (str.trim().length > 0) {
                        this.full_ar = false;
                    } else {
                        this.full_ar = true
                    }
                    return str;
                },

                emp_name_full_en: function () {
                    // `this` points to the vm instance
                    this.full_en = false
                    if (this.emp_name_2_en == null) {
                        this.emp_name_2_en = ''
                    }
                    if (this.emp_name_3_en == null) {
                        this.emp_name_3_en = ''
                    }
                    if (this.emp_name_4_en == null) {
                        this.emp_name_4_en = ''
                    }
                    var str = this.emp_name_1_en + ' ' + this.emp_name_2_en + ' ' + this.emp_name_3_en + ' ' + this.emp_name_4_en
                    if (str.trim().length > 0) {
                        this.full_en = false;
                    } else {
                        this.full_en = true
                    }
                    return str;
                },

            },


        });


    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
@endsection

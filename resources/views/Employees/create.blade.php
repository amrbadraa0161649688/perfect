@extends('Layouts.master')

@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css"/>
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

    @endsection

@section('content')
    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">

                <div class="header-action"></div>
            </div>
        </div>
    </div>


    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">

                {{-- Basic information --}}
                <div class="tab-pane fade show active " id="data-grid" role="tabpanel">

                    {{-- Form To Create Employee--}}
                    <form class="card" id="validate-form" action="{{ route('employees.store') }}"
                          method="post"
                          enctype="multipart/form-data" id="submit_user_form">
                        @csrf

                        <div class="card-body">
                            {{--inputs and photo--}}
                            <div class="row">
                                <div class="col-md-9">

                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('home.emp_code') </label>
                                                <input type="text" class="form-control is-invalid" name="emp_code"
                                                       id="emp_code" placeholder="@lang('home.emp_code')" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.name_ar') </label>
                                                <input type="text" class="form-control"
                                                       v-bind:class="{ 'is-invalid' : full_ar }"
                                                       name="emp_name_full_ar" v-model="emp_name_full_ar"
                                                       id="emp_name_full_ar" placeholder="@lang('home.name_ar')"
                                                       readonly
                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                                       required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.name_en') </label>
                                                <input type="text" class="form-control"
                                                       v-bind:class="{ 'is-invalid' : full_en }"
                                                       name="emp_name_full_en" v-model="emp_name_full_en"
                                                       id="emp_name_full_en" placeholder="@lang('home.name_en')"
                                                       readonly
                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                                       required>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.first_name_ar') </label>
                                                <input type="text" class="form-control is-invalid" name="emp_name_1_ar"
                                                       id="emp_name_1_ar" placeholder="@lang('home.first_name_ar')"
                                                       v-model="emp_name_1_ar"
                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                                       required>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.second_name_ar') </label>
                                                <input type="text" class="form-control" name="emp_name_2_ar"
                                                       id="emp_name_2_ar" placeholder="@lang('home.second_name_ar')"
                                                       v-model="emp_name_2_ar"
                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                                       >
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.third_name_ar') </label>
                                                <input type="text" class="form-control" name="emp_name_3_ar"
                                                       id="emp_name_3_ar" placeholder="@lang('home.third_name_ar')"
                                                       v-model="emp_name_3_ar"
                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                                       >
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.fourth_name_ar') </label>
                                                <input type="text" class="form-control is-invalid" name="emp_name_4_ar"
                                                       id="emp_name_4_ar" placeholder="@lang('home.fourth_name_ar')"
                                                       v-model="emp_name_4_ar"
                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                                       required>
                                            </div>


                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.first_name_en') </label>
                                                <input type="text" class="form-control is-invalid" name="emp_name_1_en"
                                                       id="emp_name_1_en" placeholder="@lang('home.first_name_en')"
                                                       v-model="emp_name_1_en"
                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                                       required>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.second_name_en') </label>
                                                <input type="text" class="form-control" name="emp_name_2_en"
                                                       id="emp_name_2_en" placeholder="@lang('home.second_name_en')"
                                                       v-model="emp_name_2_en"
                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                                       >
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.third_name_en') </label>
                                                <input type="text" class="form-control" name="emp_name_3_en"
                                                       id="emp_name_3_en" placeholder="@lang('home.third_name_en')"
                                                       v-model="emp_name_3_en"
                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                                       >
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.fourth_name_en') </label>
                                                <input type="text" class="form-control is-invalid" name="emp_name_4_en"
                                                       id="emp_name_4_en" placeholder="@lang('home.fourth_name_en')"
                                                       v-model="emp_name_4_en"
                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                                       required>
                                            </div>


                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.nationality') </label>
                                                <select class="form-select form-control is-invalid"
                                                        name="emp_nationality"
                                                        id="emp_nationality" required>
                                                    <option value="" selected> choose</option>
                                                    @foreach($sys_codes_nationality_country as $sys_code_nationality_country)
                                                        <option value="{{ $sys_code_nationality_country->system_code_id }}">
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
                                                <input type="number" class="form-control is-invalid" name="emp_identity"
                                                       id="emp_identity" placeholder="@lang('home.identity')" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('home.copy_no') </label>
                                                <input type="number" class="form-control is-invalid" name="issueNumber"
                                                       id="issueNumber" placeholder=" @lang('home.copy_no')"required>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('home.status') </label>
                                                <select class="form-select form-control is-invalid" name="emp_status"
                                                        aria-label="Default select example" id="emp_status" required>
                                                    <option value="" selected>choose</option>
                                                    @foreach($sys_codes_status as $sys_code_status)
                                                        <option value="{{$sys_code_status->system_code_id}}">
                                                            @if(app()->getLocale() == 'ar')
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

                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">@lang('home.add_photo')</h3>
                                                </div>
                                                <div class="card-body">
                                                    <input type="file" id="dropify-event"
                                                           name="emp_photo_url">
                                                </div>
                                            </div>
                                        </div>
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
                                               class="col-form-label"> @lang('home.social_status') </label>
                                        <select class="form-select form-control is-invalid" name="emp_social_status"
                                                id="emp_social_status" required>
                                            <option value="" selected>Choose</option>
                                            @foreach($sys_codes_social_status as $sys_code_social_status)
                                                <option value="{{$sys_code_social_status->system_code_id}}">
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_code_social_status->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_social_status->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.work_start_date') </label>
                                        <input type="date" class="form-control is-invalid" name="emp_work_start_date"
                                               id="emp_work_start_date" v-model="emp_work_start_date"
                                               placeholder="@lang('home.work_start_date')"
                                               @change="getWorkStartDate()" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.hijri_start_date') </label>
                                        <input type="text" class="form-control" name="emp_hijri_start_date"
                                               id="emp_hijri_start_date" v-model="emp_hijri_start_date"
                                               placeholder="@lang('home.hijri_start_date')"
                                               required>
                                    </div>
                                    {{--<div class="col-md-3">--}}
                                        {{--<label for="recipient"--}}
                                               {{--class="col-form-label"> @lang('home.previous_vacation_balance') </label>--}}
                                        {{--<input type="number" class="form-control" name="emp_vacation_balance"--}}
                                               {{--id="emp_vacation_balance"--}}
                                               {{--placeholder="@lang('home.previous_vacation_balance')" >--}}
                                    {{--</div>--}}

                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">


                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.family_count') </label>
                                        <select class="form-select form-control is-invalid" name="emp_family_count"
                                                id="emp_family_count" required>
                                            <option value="" selected>choose</option>
                                            @for($i=1 ;  $i <= 10 ;$i++ )
                                                <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.work_end_date')
                                        </label>
                                        <input type="date" class="form-control" name="emp_work_end_date"
                                               id="emp_work_end_date" v-model="emp_work_end_date"
                                               placeholder="@lang('home.work_end_date')"
                                               @change="getWorkEndDate()">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.hijri_end_date') </label>
                                        <input type="text" class="form-control" name="emp_hijri_end_date"
                                               id="emp_hijri_end_date" v-model="emp_hijri_end_date"
                                               placeholder="@lang('home.hijri_end_date')">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.reason_leaving') </label>
                                        <select class="form-select form-control" name="emp_reason_leaving"
                                                id="emp_reason_leaving" >
                                            <option value="" selected>choose</option>
                                            @foreach($sys_codes_reasons_leaving as $sys_code_reasons_leaving)
                                                <option value="{{$sys_code_reasons_leaving->system_code_id}}">
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_code_reasons_leaving->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_reasons_leaving->system_code_name_en}}
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
                                               class="col-form-label"> @lang('home.gender') </label>
                                        <select class="form-select form-control  is-invalid" name="emp_gender"
                                                id="emp_gender" required>
                                            <option value="" selected> choose</option>
                                            @foreach($sys_codes_gender as $sys_code_gender)
                                                <option value="{{$sys_code_gender->system_code_id}}">

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
                                               class="col-form-label"> @lang('home.birthday') </label>
                                        <input type="date" class="form-control is-invalid" name="emp_birthday"
                                               id="emp_birthday" v-model="emp_birthday"
                                               placeholder="@lang('home.birthday')"
                                               @change="getBirthdayDate()" required>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.birthday_hijiri') </label>
                                        <input type="text" class="form-control" name="emp_birthday_hijiri"
                                               id="emp_birthday_hijiri" v-model="emp_birthday_hijiri"
                                               placeholder="@lang('home.birthday_hijiri')"
                                               >
                                    </div>

                                    {{--....vvvvvvvv.......--}}
                                    {{--<div class="col-md-3">--}}
                                    {{--<label for="recipient"--}}
                                    {{--class="col-form-label"> @lang('home.emp_age') </label>--}}
                                    {{--<input type="number" class="form-control is-invalid" name="emp_age"--}}
                                    {{--id="emp_age" placeholder="@lang('home.emp_age')">--}}
                                    {{--</div>--}}
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.previous_sponsor_name') </label>
                                        <input type="text" class="form-control"
                                               name="emp_previous_sponsor_name"
                                               id="emp_previous_sponsor_name"
                                               placeholder="@lang('home.previous_sponsor_name')" >
                                    </div>


                                </div>
                            </div>

                            <div class="mb-3">

                                    {{--....vvvvvvvv.......--}}
                                    {{--<div class="col-md-3">--}}
                                    {{--<label for="recipient"--}}
                                    {{--class="col-form-label"> @lang('home.emp_age') </label>--}}
                                    {{--<input type="number" class="form-control is-invalid" name="emp_age"--}}
                                    {{--id="emp_age" placeholder="@lang('home.emp_age')">--}}
                                    {{--</div>--}}


                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.religion') </label>
                                        <select class="form-select form-control is-invalid" name="emp_religion"
                                                id="emp_religion" required>
                                            <option value="" selected> choose</option>
                                            @foreach($sys_codes_religion as $sys_code_religion)
                                                <option value="{{$sys_code_religion->system_code_id}}">
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
                                               <label for="recipient-name"
                                               class="col-form-label"> @lang('- - - -') </label>
                                        <select style="width: 300px" class="selectpicker"  data-live-search="true"
                                                 name="emp_birth_country">
                                            @foreach($sys_codes_countries as $sys_code_country)
                                                <option value="{{$sys_code_country->system_code_id}}">
                                                    {{app()->getLocale() == 'ar' ? $sys_code_country->system_code_name_ar : $sys_code_country->system_code_name_en}}
                                                </option>

                                            @endforeach
                                        </select>


                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.birth_city') </label>
                                        <input type="text" class="form-control" name="emp_birth_city"
                                               id="emp_birth_city" placeholder="@lang('home.birth_city')" >
                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.previous_sponsor_phone') </label>
                                        <input type="number" class="form-control"
                                               name="emp_previous_sponsor_phone"
                                               id="emp_previous_sponsor_phone"
                                               placeholder="@lang('home.previous_sponsor_phone')" >
                                    </div>
                                </div>
                            </div>


                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.private_mobile') </label>
                                        <input type="number" class="form-control is-invalid" name="emp_private_mobile"
                                               id="emp_private_mobile" placeholder="@lang('home.private_mobile')"
                                               required>

                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.email_private') </label>
                                        <input type="email" class="form-control" name="emp_email_private"
                                               id="emp_email_private" placeholder="@lang('home.email_private')"
                                               >
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.po_box_postal') </label>
                                        <input type="text" class="form-control" name="emp_po_box_postal"
                                               id="emp_po_box_postal" placeholder="@lang('home.po_box_postal')"
                                               >
                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.current_address') </label>
                                        <input type="text" class="form-control" name="emp_current_address"
                                               id="emp_current_address" placeholder="@lang('home.current_address')"
                                               >
                                    </div>

                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.work_mobile') </label>
                                        <input type="number" class="form-control is-invalid" name="emp_work_mobile"
                                               id="emp_work_mobile" placeholder="@lang('home.work_mobile')" required>

                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.email_work') </label>
                                        <input type="email" class="form-control is-invalid" name="emp_email_work"
                                               id="emp_email_work" placeholder="@lang('home.email_work')" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.sponsor_name') </label>
                                        <select class="form-select form-control is-invalid" name="emp_sponsor_id"
                                                id="emp_sponsor_name" required>
                                            <option value="" selected>Choose</option>
                                            @foreach($sys_codes_sponsor_names as $sys_code_sponsor_name)
                                                <option value="{{$sys_code_sponsor_name->system_code_id}}">
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_code_sponsor_name->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_sponsor_name->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.job_in_identity') </label>
                                        <select class="form-select form-control"
                                                name="emp_job_in_identity"
                                                id="emp_job_in_identity" >
                                            <option value="" selected>Choose</option>
                                            @foreach($sys_codes_job_identity as $sys_code_job_identity)
                                                <option value="{{$sys_code_job_identity->system_code_id}}">
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_code_job_identity->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_job_identity->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">

                                    <div class="col-md-4">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.bank_list') </label>
                                        <select class="form-select form-control" name="emp_bank_id"
                                                id="emp_bank_id">
                                            <option value="" selected> choose</option>
                                            @foreach($sys_codes_banks as $sys_codes_bank)
                                                <option value="{{$sys_codes_bank->system_code_id}}">
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_codes_bank->system_code_name_ar}}
                                                    @else
                                                        {{$sys_codes_bank->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="col-md-4">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.bank_account') </label>
                                        <input type="text" class="form-control" name="emp_bank_account"
                                               id="emp_bank_account" placeholder="@lang('home.bank_account')">

                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-check text-center mt-40">
                                            <input class="form-check-input" type="checkbox"
                                                   style=" margin-right: -1.25rem;"
                                                   name="emp_is_bank_payment" id="defaultCheck2">
                                            <label class="form-check-label"
                                                   for="defaultCheck2"> @lang('home.bank_payment')</label>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.sub_company') </label>
                                        <select class="form-select form-control is-invalid"
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

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.branch') </label>
                                        <select class="form-select form-control is-invalid"
                                                name="emp_default_branch_id"
                                                id="emp_default_branch_id" required>
                                            <option value="" selected>Choose</option>
                                            <option v-for="branch in branches" :value="branch.branch_id">
                                                @if(app()->getLocale()=='ar')
                                                    @{{ branch.branch_name_ar }}
                                                @else
                                                    @{{  branch.branch_name_en }}
                                                @endif
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.manager') </label>
                                        <select class="form-select form-control" name="emp_manager_id"
                                                id="emp_manager_id" >
                                            <option value="" selected>choose</option>
                                            @foreach($employees as $employee)
                                                <option value="{{$employee->emp_id }}">

                                                    @if(app()->getLocale() == 'ar')
                                                        {{ $employee->emp_name_full_ar }}
                                                    @else
                                                        {{ $employee->emp_name_full_en }}
                                                    @endif

                                                </option>
                                            @endforeach
                                        </select>

                                    </div>


                                </div>
                            </div>


                            <div class="mb-3">
                                <div class="row">

                                </div>
                            </div>


                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary mr-2"
                                        data-bs-dismiss="modal" id="create_emp">@lang('home.save')</button>
                            </div>
                        </div>
                    </form>
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

            //     //    validation to create Employee
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

            $('#emp_name_4_ar').keyup(function () {
                if ($('#emp_name_4_ar').val().length < 3) {
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


            $('#emp_name_4_en').keyup(function () {
                if ($('#emp_name_4_en').val().length < 3) {
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
                if ($('#emp_identity').val().length < 9) {
                    $('#emp_identity').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_identity').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });
            $('#issueNumber').keyup(function () {
                if ($('#issueNumber').val().length < 1) {
                    $('#issueNumber').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#issueNumber').removeClass('is-invalid')
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



            $('#emp_family_count').change(function () {
                if (!$('#emp_family_count').val()) {
                    $('#emp_family_count').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_family_count').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });



            $('#emp_gender').change(function () {
                if (!$('#emp_gender').val()) {
                    $('#emp_gender').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_gender').removeClass('is-invalid')
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




            $('#emp_private_mobile').keyup(function () {
                if ($('#emp_private_mobile').val().length < 9) {
                    $('#emp_private_mobile').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_private_mobile').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });



            $('#emp_work_mobile').keyup(function () {
                if ($('#emp_work_mobile').val().length < 9) {
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



            $('#emp_sponsor_name').change(function () {
                if (!$('#emp_sponsor_name').val()) {
                    $('#emp_sponsor_name').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_sponsor_name').removeClass('is-invalid')
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





            function validEmail(email) {
                var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }

        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">

        $(function () {

            $("#emp_hijri_start_date").hijriDatePicker();
            $("#emp_hijri_end_date").hijriDatePicker();
            $("#emp_birthday_hijiri").hijriDatePicker();

        });


    </script>
    <script>
        new Vue({
            el: '#app',
            data: {
                emp_name_1_ar: '',
                emp_name_2_ar: '',
                emp_name_3_ar: '',
                emp_name_4_ar: '',

                emp_name_1_en: '',
                emp_name_2_en: '',
                emp_name_3_en: '',
                emp_name_4_en: '',
                full_ar: true,
                full_en: true,
                company_id: '',
                branches: {},
                companies: {},
                company_group_id: '',
                emp_work_start_date: '',
                emp_hijri_start_date: '',
                emp_work_end_date: '',
                emp_hijri_end_date: '',
                emp_birthday: '',
                emp_birthday_hijiri: '',
                day: 0,
                month: 0,
                year: 0,
            },
            methods: {
                getBranches() {
                    $.ajax({
                        type: 'GET',
                        data: {company_id: this.company_id},
                        url: '{{ route("api.company.branches") }}'
                    }).then(response => {
                        this.branches = response.data
                    })
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

                    $.ajax({
                        type: 'GET',
                        data: {date: this.emp_work_start_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.emp_hijri_start_date = response.data
                    })
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

                getBirthdayDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.emp_birthday},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.emp_birthday_hijiri = response.data
                    })
                },
            },
            computed: {
                emp_name_full_ar: function () {
                    // `this` points to the vm instance

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
                    var str = this.emp_name_1_en + ' ' + this.emp_name_2_en + ' ' + this.emp_name_3_en + ' ' + this.emp_name_4_en
                    if (str.trim().length > 0) {
                        this.full_en = false;
                    } else {
                        this.full_en = true
                    }
                    return str;
                },


            }
        })

    </script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>

@endsection

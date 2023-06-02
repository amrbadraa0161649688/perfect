@extends('Layouts.master')

<link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>

@section('content')

    <div class="section-body mt-3">
        <div class="container-fluid">
            <div class="row clearfix">

                @include('Includes.form-errors')
                <form class="card" action="{{ route('system-codes.update', $sys_code->system_code_id) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="card-header bold"> @lang('home.edit_sys_code')
                        @lang('home.in')
                        @if(app()->getLocale()=='ar') {{ $sys_code->systemCodeCategory->sys_category_name_ar }}
                        @else {{ $sys_code->systemCodeCategory->sys_category_name_en }} @endif
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('home.name_ar') </label>
                                    <input type="text" class="form-control"
                                           name="system_code_name_ar" requird
                                           id="sys_code_name_ar"
                                           value="{{$sys_code->system_code_name_ar}}">
                                </div>
                                <div class="col-md-6">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('home.name_en') </label>
                                    <input type="text" class="form-control"
                                           name="system_code_name_en" requird
                                           id="system_code_name_en"
                                           value="{{$sys_code->system_code_name_en}}">
                                </div>

                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="row">

                                <div class="col-md-6">
                                    <label for="recipient-name"
                                           class="col-form-label" hidden > @lang('home.system_code_search') </label>
                                    <input type="text" class="form-control"
                                           name="system_code_search"
                                           hidden id="system_code_search"
                                           value="{{$sys_code->system_code_search}}" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label for="recipient-name"
                                           class="col-form-label" hidden> @lang('home.system_code_filter') </label>
                                    <input type="text" class="form-control"
                                           name="system_code_filter"
                                           id="system_code_filter"
                                           value="{{$sys_code->system_code_filter}}" hidden>
                                </div>

                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="row">

                                <div  class="col-md-6">
                                    <label for="recipient-name"
                                           class="col-form-label" > @lang('home.system_code') </label>
                                    <input type="text" class="form-control" 
                                           name="system_code"
                                           id="system_code"
                                           value="{{$sys_code->system_code}}">
                                </div>


                                <div class="col-md-6">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('home.system_code_acc_id') </label>
                                                    <select class="selectpicker"  data-live-search="true" name="system_code_acc_id"
                                                            id="system_code_acc_id" >
                                                        <option value="" selected>@lang('home.choose')</option>
                                                        @foreach($accountL as $accountLs)
                                                            <option value="{{$accountLs->acc_id}}"
                                                            @if($sys_code->system_code_acc_id == $accountLs->acc_id)
                                                            selected @endif>
                                                                @if(app()->getLocale() == 'ar')
                                                                    {{ $accountLs->acc_name_ar }}
                                                                @else
                                                                    {{ $accountLs->acc_name_en }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>

                                <div class="col-md-6">
                                    <label for="message-text"
                                           class="col-form-label"> @lang('home.system_code_status')</label>
                                    <select class="form-select form-control" name="system_code_status"
                                            aria-label="Default select example">
                                        <option value="1" @if($sys_code->system_code_status) selected @endif>True</option>
                                        <option value="0" @if(!$sys_code->system_code_status) selected @endif>False</option>
                                    </select>
                                </div>


                           
                       
                        <div class="col-md-6">
                                    <label for="recipient-name"
                                           class="form-label"  > @lang('home.system_code_search_byan') </label>

                                           <select class="selectpicker"  data-live-search="true" name="system_code_search"
                                                            id="system_code_search" >
                                                        <option value="" selected>@lang('home.choose')</option>
                                                        @foreach($sys_codes_location_byab as $sys_codes_location_byabs)
                                                            <option value="{{$sys_codes_location_byabs->system_code}}"
                                                            @if($sys_code->system_code_search == $sys_codes_location_byabs->system_code)
                                                            selected @endif>
                                                                @if(app()->getLocale() == 'ar')
                                                                    {{ $sys_codes_location_byabs->system_code_name_ar }}
                                                                @else
                                                                    {{ $sys_codes_location_byabs->system_code_name_en }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>

                                </div>
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="recipient-name"
                                           class="col-form-label" > @lang('home.system_code_filter_branch') </label>
                                           <select class="selectpicker"  data-live-search="true" name="system_code_filter"
                                                            id="system_code_filter" >
                                                        <option value="" selected>@lang('home.choose')</option>
                                                        @foreach($sys_codes_location as $sys_codes_locations)
                                                            <option value="{{$sys_codes_locations->branch_id}}"
                                                            @if($sys_code->system_code_filter == $sys_codes_locations->branch_id)
                                                            selected @endif>
                                                                @if(app()->getLocale() == 'ar')
                                                                    {{ $sys_codes_locations->branch_name_ar }}
                                                                @else
                                                                    {{ $sys_codes_locations->branch_code_name_en }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>


                                </div>

                        <div class="mb-3">
                            <div class="row">

                                <div class="col-md-6">
                                    <label for="message-text"
                                           class="col-form-label" hidden> @lang('home.system_code_posted')</label>
                                    <select class="form-select form-control" name="system_code_posted"
                                            aria-label="Default select example" hidden>
                                        <option value="1" @if($sys_code->system_code_posted) selected @endif>True</option>
                                        <option value="0" @if(!$sys_code->system_code_posted) selected @endif>False</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="recipient-name"
                                           class="col-form-label" hidden> @lang('home.system_code_url') </label>
                                    <input type="text" class="form-control"
                                           name="system_code_url"
                                           id="system_code_url"
                                           value="{{$sys_code->system_code_url}}" hidden>
                                </div>


                            </div>
                        </div>

                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-secondary mr-2">@lang('home.save')</button>
                        <a href="{{ route('systemCodelocations') }}" class="btn btn-primary"
                                       style="display: inline-block; !important;"
                                       id="back">
                                        @lang('home.back')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection()
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script type="text/javascript"></script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>

           


@endsection

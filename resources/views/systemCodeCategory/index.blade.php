@extends('Layouts.master')



@section('content')

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">

            {{--pop up to create--}}
            <div class="modal fade" id="exampleModal" tabindex="-1"
                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="width: 800px">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">@lang('home.add_system_code_category')</h5>
                           {{--  <button type="button" class="close" data-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>--}}
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('systemCodeCategories.store') }}" method="post"
                                  enctype="multipart/form-data" id="submit_sys_code_form">
                                @csrf
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('home.name_ar') </label>
                                            <input type="text" class="form-control is-invalid"
                                                   name="sys_category_name_ar"
                                                   id="sys_category_name_ar"
                                                  
                                                   required
                                                   placeholder="@lang('home.name_ar')">
                                            <div id="sys_category_name_ar_errors"></div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('home.name_en') </label>
                                            <input type="text" class="form-control is-invalid"
                                                   name="sys_category_name_en"
                                                   id="sys_category_name_en"
                                                   
                                                   required
                                                   placeholder="@lang('home.name_en')">
                                            <div id="sys_category_name_en_errors"></div>
                                        </div>

                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-12 col md 6">
                                            <label for="message-text"
                                                   class="col-form-label"> @lang('home.applications')</label>
                                            <select class="form-select form-control is-invalid" name="sys_category_app"
                                                    aria-label="Default select example"
                                                    required
                                                    id="sys_category_app">
                                                <option value="" selected>choose</option>
                                                @foreach($applications as $application)
                                                    <option value="{{ $application->app_id }}">
                                                        {{ app()->getLocale()=='ar' ? $application->app_name_ar : $application->app_name_en }}
                                                    </option>
                                                @endforeach
                                                {{--<option :value="application.app_id" v-for="application in applications">--}}
                                                {{--@if(app()->getLocale() == 'ar') @{{ application.app_name_ar }}--}}
                                                {{--@else @{{ application.app_name_en }} @endif--}}
                                                {{--</option>--}}
                                            </select>
                                        </div>

                                        <div class="col-12 col md 6">
                                            <label for="message-text"
                                                   class="col-form-label"> @lang('home.type')</label>
                                            <input type="text" class="form-control is-invalid" name="sys_category_type"
                                                   id="sys_category_type"
                                                   required
                                                   placeholder="@lang('home.type')">
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary mr-2"
                                            data-bs-dismiss="modal"
                                            id="create_sys_code_category">@lang('home.save')</button>
                                    <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">@lang('home.close')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">


                    <div class="row mb-3">
                        @if(auth()->user()->user_type_id == 1)
                            <div class="col-md-3">
                               {{--  <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#exampleModal">
                                    <i class="fe fe-plus mr-2"></i>@lang('home.add_system_code_category')
                                </button> --}}
                            </div>
                        @endif
                        <div class="col-md-4">
                            @if(session('company_group'))
                                <input type="text" class="form-control" readonly value="{{ app()->getLocale() == 'ar' ?
                             session('company_group')['company_group_ar'] : session('company_group')['company_group_en']  }}">
                            @else
                                <input class="form-control" type="text" readonly value="{{ app()->getLocale() == 'ar' ?
                             auth()->user()->companyGroup->company_group_ar : auth()->user()->companyGroup->company_group_en }}">
                            @endif
                        </div>

                        <div class="col-md-4">
                            <form action="">
                                <select class="form-control" name="company_id"
                                        onchange="this.form.submit()">
                                    <option value="">@lang('home.choose')</option>
                                    @foreach($companies as $company)
                                        <option value="{{$company->company_id}}"
                                                @if(request()->company_id == $company->company_id) selected @endif>
                                            @if(app()->getLocale() == 'ar')
                                                {{$company->company_name_ar}}
                                            @else
                                                {{$company->company_name_en}}
                                            @endif
                                        </option>

                                    @endforeach

                                </select>
                            </form>
                        </div>

                    </div>


                    <div class="card">
                        <div class="card-header">
                            <div class="card-options">
                                <div class="header-action">
                                </div>
                            </div>
                        </div>
                        {{--this div to show system codes category and edit--}}
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table_custom border-style spacing5">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('home.name')</th>
                                        <th>@lang('home.name')</th>
                                       
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($system_codes_category as $k=>$system)
                                        <tr>
                                            <td>{{ $k+1 }}</td>
                                            <td>
                                                <div class="font-15">@if(app()->getLocale() == 'ar')
                                                        {{ $system->sys_category_name_ar }}
                                                    @else
                                                        {{ $system->sys_category_name_en }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $system->sys_category_name_en }}</td>
                                           
                                            <td>
                                                @if(auth()->user()->user_type_id == 1)
                                                    <button class="btn btn-icon" title="@lang('home.edit')"
                                                            data-toggle="modal"
                                                            data-target="#exampleModal{{$system->sys_category_id}}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                @endif

                                                <button type="button" class="btn btn-icon js-sweetalert"
                                                        id="sys_code{{ $system->sys_category_id }}"
                                                        title="@lang('home.show')" data-type="confirm"
                                                        onclick="show(this)">
                                                    <i class="fa fa-eye text-danger"></i>
                                                </button>

                                                {{-- pop up update --}}
                                                <div class="modal fade" id="exampleModal{{$system->sys_category_id}}"
                                                     tabindex="-1"
                                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content" style="width: 800px">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="exampleModalLabel">@lang('home.add_system_code_category')</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('systemCodeCategories.update', $system->sys_category_id) }}"
                                                                      method="post">
                                                                    @csrf
                                                                    @method('put')
                                                                    <div class="mb-3">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <label for="recipient-name"
                                                                                       class="col-form-label"> @lang('home.name_ar') </label>
                                                                                <input type="text"
                                                                                       class="form-control sys_category_name_ar"
                                                                                       name="sys_category_name_ar"
                                                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                                                                       required
                                                                                       value="{{$system->sys_category_name_ar}}">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="recipient-name"
                                                                                       class="col-form-label"> @lang('home.name_en') </label>
                                                                                <input type="text"
                                                                                       class="form-control sys_category_name_en"
                                                                                       name="sys_category_name_en"
                                                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                                                                       required
                                                                                       value="{{$system->sys_category_name_en}}">
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <div class="row">

                                                                            <div class="col-12 col md 6">
                                                                                <label for="message-text"
                                                                                       class="col-form-label"> @lang('home.type')</label>
                                                                                <input type="text"
                                                                                       class="form-control sys_category_type"
                                                                                       name="sys_category_type"
                                                                                       required
                                                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                                                                       value="{{$system->sys_category_type}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                                class="btn btn-primary mr-2"
                                                                                id="update_sys_code">@lang('home.save')</button>
                                                                        <button type="button" class="btn btn-secondary"
                                                                                data-dismiss="modal">@lang('home.close')</button>
                                                                    </div>

                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

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
    {{--this div to show system codes and create --}}
    <div>

        @foreach($system_codes_category as $system_code_category)

            {{-- pop up to add system code --}}
            <div class="modal fade" id="sys_code_modal{{$system_code_category->sys_category_id}}"
                 tabindex="-1"
                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="width: 800px">

                        <div class="modal-header">
                            <h5 class="modal-title"
                                id="exampleModalLabel">@lang('home.add_system_code_category')</h5>
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('system-codes.store') }}" method="post">
                                @csrf
                                <input type="hidden" name="sys_category_id"
                                       value="{{$system_code_category->sys_category_id}}">
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('home.name_ar') </label>
                                            <input type="text" class="form-control is-invalid sys_code_name_ar"
                                                   name="system_code_name_ar"
                                                  
                                                   required
                                                   placeholder="@lang('home.name_ar')">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('home.name_en') </label>
                                            <input type="text" class="form-control is-invalid sys_code_name_en"
                                                   name="system_code_name_en"
                                                  
                                                   required
                                                   placeholder="@lang('home.name_ar')">
                                        </div>

                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <label for="recipient-name"
                                                   class="col-form-label" hidden> @lang('home.system_code_search') </label>
                                            <input type="text" class="form-control is-invalid sys_code_search"
                                                   name="system_code_search"
                                                    hidden
                                                   placeholder="@lang('home.system_code_search')">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="recipient-name"
                                                   class="col-form-label" hidden> @lang('home.system_code_filter') </label>
                                            <input type="text" class="form-control is-invalid sys_code_filter"
                                                   name="system_code_filter"
                                                    hidden
                                                   placeholder="@lang('home.system_code_filter')">
                                        </div>

                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('home.sys_code') </label>
                                            <input type="text" class="form-control is-invalid sys_code"
                                                   name="system_code"
                                                   required
                                                   placeholder="@lang('home.sys_code')">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="message-text"
                                                   class="col-form-label"> @lang('home.status')</label>
                                            <select class="form-select form-control is-invalid sys_code_status"
                                                    name="system_code_status"
                                                    required
                                                    aria-label="Default select example">
                                                <option value="" selected>choose</option>
                                                <option value="1">True</option>
                                                <option value="0">False</option>
                                            </select>
                                        </div>




                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="row">

                                        <div class="col-12 col-md-6">
                                            <label for="message-text"
                                                   class="col-form-label" hidden> @lang('home.system_code_posted')</label>
                                            <select class="form-select form-control is-invalid sys_code_posted"
                                                    name="system_code_posted"
                                                     hidden
                                                    aria-label="Default select example">
                                                <option value="" selected>choose</option>
                                                <option value="1">True</option>
                                                <option value="0">False</option>
                                            </select>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="recipient-name"
                                                   class="col-form-label" hidden> @lang('home.system_code_url') </label>
                                            <input type="text" class="form-control is-invalid sys_code_url"
                                                   name="system_code_url"
                                                    hidden
                                                   placeholder="@lang('home.system_code_url')">
                                        </div>

                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit"
                                            class="btn btn-primary mr-2 create_sys_code">@lang('home.save')</button>
                                    <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">@lang('home.close')</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="section-body section-sub-application mt-3"
                 id="cat_sys_code{{ $system_code_category->sys_category_id }}"
                 style="display:none">
                <div class="container-fluid">
                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    @if(auth()->user()->user_type_id != 1)
                                        @foreach(session('job')->permissions as $job_permission)
                                            @if($job_permission->app_menu_id == 6 && $job_permission->permission_add)
                                                <button class="btn btn-primary"
                                                        data-toggle="modal"
                                                        data-target="#sys_code_modal{{$system_code_category->sys_category_id}}">
                                                    <i class="fe fe-plus mr-2"></i>@lang('home.add_sys_code')</button>
                                            @endif
                                        @endforeach
                                    @endif
                                    @if(auth()->user()->user_type_id == 1)
                                        <button class="btn btn-primary"
                                                data-toggle="modal"
                                                data-target="#sys_code_modal{{$system_code_category->sys_category_id}}">
                                            <i class="fe fe-plus mr-2"></i>@lang('home.add_sys_code')</button>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                                <span style="text-decoration:underline;font-size:20px;">
                                                @if(app()->getLocale()=='ar') {{ $system_code_category->sys_category_name_ar }}
                                                    @else {{ $system_code_category->sys_category_name_en }} @endif
                                            </span>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-vcenter table-hover mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>@lang('home.name')</th>
                                                <th>@lang('home.sys_code')</th>
                                                
                                                <th>@lang('home.company')</th>
                                                <th>@lang('home.system_code_search_byan')</th>
                                                <th>@lang('home.system_code_acc_id')</th>
                                                
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(request()->company_id)
                                                @foreach($system_code_category->systemCodes->where('company_id',request()->company_id)->all() as $k=>$system_code)
                                                    <tr>
                                                        <td>{{ $k+1 }}</td>
                                                        <td>
                                                            <div class="font-15">@if(app()->getLocale() == 'ar')
                                                                    {{ $system_code->system_code_name_ar }}
                                                                @else
                                                                    {{ $system_code->system_code_name_en }}
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>{{ $system_code->system_code }}</td>
                                                        
                                                        <td>{{ app()->getLocale() == 'ar' ?
                                                    $system_code->company->company_name_ar :
                                                     $system_code->company->company_name_en}}</td>

                                                     <td>{{ app()->getLocale() == 'ar' ?
                                                    $system_code->system_code_search :
                                                     $system_code->system_code_search}}</td>


                                                     <td>{{ app()->getLocale() == 'ar' ?
                                                    $system_code->system_code_acc_id :
                                                     $system_code->system_code_acc_id}}</td>

                                                       
                                                        <td>
                                                            <a href="{{route('system-codes.show' , $system_code->system_code_id)}}"
                                                               class="btn btn-icon"
                                                               title="@lang('home.edit')">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @foreach($system_code_category->systemCodes->where('company_id',$company->company_id)->all() as $k=>$system_code)
                                                    <tr>
                                                        <td>{{ $k+1 }}</td>
                                                        <td>
                                                            <div class="font-15">@if(app()->getLocale() == 'ar')
                                                                    {{ $system_code->system_code_name_ar }}
                                                                @else
                                                                    {{ $system_code->system_code_name_en }}
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>{{ $system_code->system_code }}</td>
                                                        <td>{{ app()->getLocale() == 'ar' ?
                                                    $system_code->companyGroup->company_group_ar :
                                                     $system_code->companyGroup->company_group_en}}</td>

                                                        <td>{{ app()->getLocale() == 'ar' ?
                                                    $system_code->company->company_name_ar :
                                                     $system_code->company->company_name_en}}</td>
                                                     <td>{{ app()->getLocale() == 'ar' ?
                                                    $system_code->system_code_acc_id :
                                                     $system_code->system_code_acc_id}}</td>

                                                       
                                                        <td>
                                                        <td>@if($system_code->system_code_status)
                                                        <i class="fa fa-check"></i>
                                                            @else
                                                               
                                                                <i class="fa fa-remove"></i>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{route('system-codes.show' , $system_code->system_code_id)}}"
                                                               class="btn btn-icon"
                                                               title="@lang('home.edit')">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('scripts')
    <script>
        function show(el) {
            var x = el.id;
            $("#cat_" + x).css("display", "block");
            $("#cat_" + x).siblings().css('display', 'none')
        }

        $(document).ready(function () {


            //    validation to create modal to System Code Category
            $('#sys_category_name_ar').keyup(function () {
                if ($('#sys_category_name_ar').val().length < 3) {
                    $('#sys_category_name_ar').addClass('is-invalid')
                    $('#create_sys_code_category').attr('disabled', 'disabled')
                } else {
                    $('#sys_category_name_ar').removeClass('is-invalid');
                    $('#create_sys_code_category').removeAttr('disabled');
                }
            });

            $('#sys_category_name_en').keyup(function () {
                if ($('#sys_category_name_en').val().length < 3) {
                    $('#sys_category_name_en').addClass('is-invalid')
                    $('#create_sys_code_category').attr('disabled', 'disabled')
                } else {
                    $('#sys_category_name_en').removeClass('is-invalid')
                    $('#create_sys_code_category').removeAttr('disabled');
                }
            });

            $('#company_group_id').change(function () {
                if (!$('#company_group_id').val()) {
                    $('#company_group_id').addClass('is-invalid')
                    $('#create_sys_code_category').attr('disabled', 'disabled')
                } else {
                    $('#company_group_id').removeClass('is-invalid');
                    $('#create_sys_code_category').removeAttr('disabled');
                }
            });

            $('#company_id').change(function () {
                if (!$('#company_id').val()) {
                    $('#company_id').addClass('is-invalid')
                    $('#create_sys_code_category').attr('disabled', 'disabled')
                } else {
                    $('#company_id').removeClass('is-invalid');
                    $('#create_sys_code_category').removeAttr('disabled');
                }
            });

            $('#sys_category_app').change(function () {
                if (!$('#sys_category_app').val()) {
                    $('#sys_category_app').addClass('is-invalid')
                    $('#create_sys_code_category').attr('disabled', 'disabled')
                } else {
                    $('#sys_category_app').removeClass('is-invalid');
                    $('#create_sys_code_category').removeAttr('disabled');
                }
            });

            $('#sys_category_type').keyup(function () {
                if ($('#sys_category_type').val().length < 3) {
                    $('#sys_category_type').addClass('is-invalid')
                    $('#create_sys_code_category').attr('disabled', 'disabled')
                } else {
                    $('#sys_category_type').removeClass('is-invalid');
                    $('#create_sys_code_category').removeAttr('disabled');
                }
            });

            //    validation to update modal to System Code Category

            $('.sys_category_name_ar').each(function () {
                $(this).keyup(function () {
                    if ($(this).val().length < 3) {
                        $(this).addClass('is-invalid')
                    } else {
                        $(this).removeClass('is-invalid')
                    }
                });
            })

            $('.sys_category_name_en').each(function () {
                $(this).keyup(function () {
                    if ($(this).val().length < 3) {
                        $(this).addClass('is-invalid')
                    } else {
                        $(this).removeClass('is-invalid')
                    }
                });
            })

            $('.sys_category_type').each(function () {
                $(this).keyup(function () {
                    if ($(this).val().length < 3) {
                        $(this).addClass('is-invalid')
                    } else {
                        $(this).removeClass('is-invalid')
                    }
                });
            })

            // //    validation to create modal to system code
            $('.sys_code_name_ar').each(function () {
                $(this).keyup(function () {
                    if ($(this).val().length < 3) {
                        $(this).addClass('is-invalid')
                    } else {
                        $(this).removeClass('is-invalid')
                    }
                });
            })

            $('.sys_code_name_en').each(function () {
                $(this).keyup(function () {
                    if ($(this).val().length < 3) {
                        $(this).addClass('is-invalid')
                    } else {
                        $(this).removeClass('is-invalid')
                    }
                });
            })

            $('.sys_code_search').each(function () {
                $(this).keyup(function () {
                    if ($(this).val().length < 3) {
                        $(this).addClass('is-invalid')
                    } else {
                        $(this).removeClass('is-invalid')
                    }
                });
            })

            $('.sys_code_filter').each(function () {
                $(this).keyup(function () {
                    if ($(this).val().length < 3) {
                        $(this).addClass('is-invalid')
                    } else {
                        $(this).removeClass('is-invalid')
                    }
                });
            })

            $('.sys_code').each(function () {
                $(this).keyup(function () {
                    if ($(this).val().length < 3) {
                        $(this).addClass('is-invalid')
                    } else {
                        $(this).removeClass('is-invalid')
                    }
                });
            })

            $('.sys_code_url').each(function () {
                $(this).keyup(function () {
                    if ($(this).val().length < 3) {
                        $(this).addClass('is-invalid')
                    } else {
                        $(this).removeClass('is-invalid')
                    }
                });
            })

            $('.sys_code_posted').each(function () {
                $(this).change(function () {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid')
                    } else {
                        $(this).removeClass('is-invalid')
                    }
                });
            })

            $('.sys_code_status').each(function () {
                $(this).change(function () {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid')
                    } else {
                        $(this).removeClass('is-invalid')
                    }
                });
            })

        })

    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>

        new Vue({
            el: '#app',
            data: {
                companies: {},
                company_group_id: "",
                applications: {},
                company_id: "",

            },
            methods: {
                getCompanies() {
                    $.ajax({
                        type: 'GET',
                        data: {id: this.company_group_id},
                        url: '{{ route("api.company-group.companies") }}'
                    }).then(response => {
                        this.companies = response.data
                    })

                },
                getApplications() {
                    $.ajax({
                        type: 'GET',
                        data: {id: this.company_id},
                        url: '{{ route("api.company.applications") }}'
                    }).then(response => {
                        this.applications = response.data
                    })
                }
            }
        })

    </script>
@endsection

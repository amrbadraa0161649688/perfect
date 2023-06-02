@extends('Layouts.master')

@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
@endsection

@section('content')
    @if(\Request::path()=='users-add/'.$user->user_id.'/edit/profile') readonly @endif
    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <ul class="nav nav-tabs page-header-tab">
                    <li class="nav-item">
                        <a class="nav-link" href="#data-grid" data-toggle="tab">@lang('home.data')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="tab">@lang('home.extra_permissions')</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="#branches-grid" data-toggle="tab">@lang('home.branches')</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#attachments-grid"
                                            data-toggle="tab">@lang('home.files')</a></li>
                    <li class="nav-item"><a class="nav-link" href="#notes-grid"
                                            data-toggle="tab">@lang('home.notes')</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" data-toggle="tab">@lang('home.archive')</a></li>

                </ul>

                <div class="header-action"></div>
            </div>
        </div>
    </div>


    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">

                <div class="row">
                    <div class="col-md-6">

                        <p class="text-center" style="font-size: 25px; font-weight:bold;">
                            @lang('home.user_name') :

                            @if(app()->getLocale()== 'ar')
                                {{$user->user_name_ar}}
                            @else
                                {{$user->user_name_en}}
                            @endif

                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-center" style="font-size: 25px; font-weight:bold;">
                            @lang('home.code') :
                            {{$user->user_code}}
                        </p>
                    </div>
                </div>
                {{-- dATA --}}
                <div class="tab-pane fade show active " id="data-grid" role="tabpanel">
                    @include('Includes.form-errors')

                    <form class="card" id="validate-form" action="{{ route('user.update' , $user->user_id) }}"
                          method="post"
                          enctype="multipart/form-data" id="submit_user_form">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="card">
                                            @if(\Request::path()=='users-add/'.$user->user_id.'/edit')
                                                <div class="card-header">
                                                    <h3 class="card-title">@lang('home.photo')</h3>
                                                </div>
                                                <div class="card-body">
                                                    <input type="file" id="dropify-event" name="user_profile_url">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 text-center">
                                        {{--<img id="dropify-event">--}}
                                        <img src="{{ $user->user_profile_url }}" class="rounded " width="250"
                                             height="250">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">

                                    <div class="col-md-6">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.name_ar') </label>
                                        <input type="text" class="form-control" name="user_name_ar"
                                               id="user_name_ar" value="{{$user->user_name_ar}}"
                                               oninput="this.value=this.value.replace( /[^ء-ي/]/g,' ');"
                                               @if(\Request::path()=='users-add/'.$user->user_id.'/edit/profile') readonly @endif>

                                    </div>

                                    <div class="col-md-6">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.name_en') </label>
                                        <input type="text" class="form-control" name="user_name_en"
                                               id="user_name_en" value="{{$user->user_name_en}}"
                                               oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                               @if(\Request::path()=='users-add/'.$user->user_id.'/edit/profile') readonly @endif>

                                    </div>

                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">

                                    <div class="col-md-6">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.email') </label>
                                        <input type="email" class="form-control" name="user_email"
                                               id="user_email" value="{{$user->user_email}}"
                                               @if(\Request::path()=='users-add/'.$user->user_id.'/edit/profile') readonly @endif>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.mobile_number') </label>
                                        <input type="number" class="form-control" name="user_mobile"
                                               value="{{$user->user_mobile}}" id="user_mobile"
                                               @if(\Request::path()=='users-add/'.$user->user_id.'/edit/profile') readonly @endif>
                                    </div>

                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.start_date') </label>
                                        <input type="date" class="form-control" name="user_start_date"
                                               id="user_start_date" value="{{$user->user_start_date}}"
                                               @if(\Request::path()=='users-add/'.$user->user_id.'/edit/profile') readonly @endif>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.end_date')
                                        </label>
                                        <input type="date" class="form-control" name="user_end_date"
                                               value="{{$user->user_end_date}}" id="user_end_date"
                                               @if(\Request::path()=='users-add/'.$user->user_id.'/edit/profile') readonly @endif>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.status')
                                        </label>
                                        @if(\Request::path()=='users-add/'.$user->user_id.'/edit') readonly
                                        <select class="form-control" name="user_status_id">
                                            <option value="1" @if($user->user_status_id == 1) selected @endif>True
                                            </option>
                                            <option value="0" @if($user->user_status_id == 0) selected @endif>False
                                            </option>
                                        </select>
                                        @elseif(\Request::path()=='users-add/'.$user->user_id.'/edit/profile')
                                            <input class="form-control"
                                                   value="{{$user->user_status_id == 1 ? 'active' : 'not Active'}}"
                                                   readonly>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">

                                    <div class="col md 6">
                                        <label for="message-text"
                                               class="col-form-label"> @lang('home.code')</label>
                                        <input type="text" class="form-control " name="user_code"
                                               id="user_code" value="{{$user->user_code}}"
                                               @if(\Request::path()=='users-add/'.$user->user_id.'/edit/profile') readonly @endif>
                                    </div>

                                    <div class="col md 6">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.password')
                                        </label>
                                        <input type="password" class="form-control" name="user_password"
                                               id="user_password">
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary mr-2"
                                        data-bs-dismiss="modal" id="create_user">@lang('home.save')</button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Branches --}}
                <div class="tab-pane fade " id="branches-grid" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                        @if(\Request::path()=='users-add/'.$user->user_id.'/edit')
                            <button  type="button" class="btn btn-primary" id="add_branch_button">
                                <i class="fe fe-plus mr-2"></i>@lang('home.add_branch')
                            </button>
                            @endif
                            
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('home.branch_name')</th>
                                        <th>@lang('home.branch_address')</th>
                                        <th>@lang('home.branch_phone')</th>
                                        <th>@lang('home.code')</th>
                                        <th>@lang('home.job')</th>
                                        <th>@lang('home.user_start_date')</th>
                                        <th>@lang('home.user_end_date')</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($user->branches as $k=>$branch)
                                        <tr>
                                            <td>{{ $k +1 }}</td>
                                            <td>@if (app()->getLocale()== 'ar'){{ $branch->branch_name_ar }}
                                                @else
                                                    {{$branch->branch_name_en}}
                                                @endif
                                            </td>
                                            <td>{{$branch->branch_address}}</td>
                                            <td>{{$branch->branch_phone}}</td>
                                            <td>{{$branch->branch_code}}</td>
                                            <td>
                                                @php
                                                    $user_branch=\App\Models\UserBranch::where('user_id' ,
                                                    $user->user_id)->where('branch_id',$branch->branch_id)->first();
                                                    $job=\App\Models\Job::where('job_id',$user_branch->job_id)->first();
                                                @endphp
                                                @if(isset($job))
                                                    <span class="tag tag-success" data-toggle="modal"
                                                          data-target="#permissionModal{{ $user->user_id }}">
                                                @if(app()->getLocale()=='en')
                                                            {{ $job->job_name_en}}
                                                        @else
                                                            {{ $job->job_name_ar }}
                                                        @endif
                                              </span>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="permissionModal{{ $user->user_id }}"
                                                         tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                         aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content" style="width: 700px">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="exampleModalLabel">@lang('home.job_permissions')</h5>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <ul class="list-group">
                                                                        @foreach($job->permissions as $permission)
                                                                            <li class="list-group-item ">
                                                                                @if(app()->getLocale()=='en')
                                                                                    {{ $permission->permission_name_en }}
                                                                                @else
                                                                                    {{ $permission->permission_name_ar }}
                                                                                @endif
                                                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                                                    <input type="checkbox"
                                                                                           disabled="disabled"
                                                                                           class="custom-control-input"
                                                                                           name="example-checkbox1"
                                                                                           value="option1"
                                                                                           @if($permission->permission_view) checked @endif>
                                                                                    <span class="custom-control-label">@lang('view')</span>
                                                                                </label>
                                                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                                                    <input type="checkbox"
                                                                                           disabled="disabled"
                                                                                           class="custom-control-input"
                                                                                           name="example-checkbox1"
                                                                                           value="option1"
                                                                                           @if($permission->permission_add) checked @endif>
                                                                                    <span class="custom-control-label">@lang('add')</span>
                                                                                </label>
                                                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                                                    <input type="checkbox"
                                                                                           disabled="disabled"
                                                                                           class="custom-control-input"
                                                                                           name="example-checkbox1"
                                                                                           value="option1"
                                                                                           @if($permission->permission_update) checked @endif>
                                                                                    <span class="custom-control-label">@lang('update')</span>
                                                                                </label>
                                                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                                                    <input type="checkbox"
                                                                                           disabled="disabled"
                                                                                           class="custom-control-input"
                                                                                           name="example-checkbox1"
                                                                                           value="option1"
                                                                                           @if($permission->permission_delete) checked @endif>
                                                                                    <span class="custom-control-label">@lang('delete')</span>
                                                                                </label>
                                                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                                                    <input type="checkbox"
                                                                                           class="custom-control-input"
                                                                                           name="example-checkbox1"
                                                                                           value="option1"
                                                                                           disabled="disabled"
                                                                                           @if($permission->permission_print) checked @endif>
                                                                                    <span class="custom-control-label">@lang('print')</span>
                                                                                </label>
                                                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                                                    <input type="checkbox"
                                                                                           disabled="disabled"
                                                                                           class="custom-control-input"
                                                                                           name="example-checkbox1"
                                                                                           value="option1"
                                                                                           @if($permission->permission_approve) checked @endif>
                                                                                    <span class="custom-control-label">@lang('approve')</span>
                                                                                </label>

                                                                            </li>
                                                                        @endforeach
                                                                    </ul>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn"
                                                                            style="background-color: #a6ca16 !important;color:#ffffff"
                                                                            data-dismiss="modal">@lang('home.close')</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{\App\Models\UserBranch::where('user_id',$user->user_id)
                                            ->where('branch_id',$branch->branch_id)->first()->start_date}}</td>

                                            <td>{{\App\Models\UserBranch::where('user_id',$user->user_id)
                                            ->where('branch_id',$branch->branch_id)->first()->end_date}}</td>
                                            <td>
                                            @if(\Request::path()=='users-add/'.$user->user_id.'/edit')
                                                <a class="btn btn-icon"
                                                   href="{{ url('user-branches/' . $user->user_id . '/' . $branch->branch_id . '/edit' ) }}"
                                                   title="@lang('home.edit')">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @endif    
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{--Add Branches--}}
                    <form class="card" action="{{ route('user.branches.store') }}" method="post" style="display:none;"
                          id="add_branch_form">
                        @csrf
                        <input type="hidden" value="{{$user->user_id}}" name="user_id">
                        <div class="card-header bold"> @lang('home.add_branch') </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="row">

                                    <div class="col-md-6">
                                        <label for="message-text"
                                               class="col-form-label"> @lang('home.branches')</label>
                                        <select class="form-select form-control is-invalid" name="branch_id"
                                                aria-label="Default select example" id="branch_id">
                                            <option value="" selected>Choose</option>
                                            @foreach($branch_all as $branch)
                                                <option value="{{$branch->branch_id}}">
                                                    @if(app()->getLocale()== 'ar')
                                                        {{$branch->branch_name_ar}}
                                                    @else
                                                        {{$branch->branch_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="message-text"
                                               class="col-form-label"> @lang('home.jobs')</label>
                                        <select class="form-select form-control is-invalid" name="job_id" id="job_id"
                                                aria-label="Default select example">
                                            <option selected value="">Choose</option>
                                            @foreach($company->jobs as $job)
                                                <option value="{{$job->job_id}}">
                                                    @if(app()->getLocale()== 'ar')
                                                        {{$job->job_name_ar}}
                                                    @else
                                                        {{$job->job_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">

                                    <div class="col-md-6">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.user_start_date') </label>
                                        <input type="date" class="form-control is-invalid" name="start_date"
                                               id="start_date">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.user_end_date') </label>
                                        <input type="date" class="form-control is-invalid" name="end_date"
                                               id="end_date">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.start_time') </label>
                                        <input type="time" class="form-control is-invalid" name="start_time"
                                               id="start_time">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.end_time') </label>
                                        <input type="time" class="form-control is-invalid" name="end_time"
                                               id="end_time">
                                    </div>

                                </div>
                            </div>

                            <div class="mb-3">

                                <label for="message-text"
                                       class="col-form-label"> @lang('home.user_branch_is_defaul')</label>
                                <select class="form-select form-control"
                                        name="user_branch_is_defaul" id="user_branch_is_defaul"
                                        aria-label="Default select example">
                                    <option value="1" selected>True</option>
                                    <option value="0">False</option>
                                </select>

                            </div>

                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" id="submit_create_branch"
                                    class="btn btn-secondary mr-2">@lang('home.save')</button>

                        </div>
                    </form>
                </div>

                {{-- Attachments --}}
                <div class="tab-pane fade " id="attachments-grid" role="tabpanel">

                    <div class="row clearfix">
                        <div class="col-md-12">


                            <x-files.form>
                                <input type="hidden" name="transaction_id" value="{{$user->user_id}}">
                                <input type="hidden" name="app_menu_id" value="525">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('home.attachment_type')</label>
                                        <select class="form-control" name="attachment_type" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($attachment_types as $attachment_type)
                                                <option value="{{ $attachment_type->system_code_id }}">
                                                    {{app()->getLocale()=='ar' ? $attachment_type->system_code_name_ar
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
                                         $attachment->attachmentType->system_code_name_ar :
                                          $attachment->attachmentType->system_code_name_en}}</td>
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

                {{-- notes --}}
                <div class="tab-pane fade " id="notes-grid" role="tabpanel">

                    <div class="row clearfix">
                        <div class="col-md-12">


                            <x-files.form-notes>

                                <input type="hidden" name="transaction_id" value="{{$user->user_id}}">
                                <input type="hidden" name="app_menu_id" value="25">


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

        $(document).ready(function () {
            $('#add_branch_button').click(function () {
                var display = $("#add_branch_form").css("display");
                if (display == 'none') {
                    $('#add_branch_form').css('display', 'block')
                } else {
                    $('#add_branch_form').css('display', 'none')
                }
            });


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


            //validation of add branch

            //    validation to create modal
            $('#branch_id').change(function () {
                if (!$('#branch_id').val()) {
                    $('#branch_id').addClass('is-invalid')
                    $('#submit_create_branch').attr('disabled', 'disabled')
                } else {
                    $('#branch_id').removeClass('is-invalid')
                    $('#submit_create_branch').removeAttr('disabled', 'disabled')
                }
            });

            $('#user_branch_is_defaul').change(function () {
                if (!$('#user_branch_is_defaul').val()) {
                    $('#user_branch_is_defaul').addClass('is-invalid')
                    $('#submit_create_branch').attr('disabled', 'disabled')
                } else {
                    $('#user_branch_is_defaul').removeClass('is-invalid')
                    $('#submit_create_branch').removeAttr('disabled', 'disabled')
                }
            });

            $('#job_id').change(function () {
                if (!$('#job_id').val()) {
                    $('#job_id').addClass('is-invalid')
                    $('#submit_create_branch').attr('disabled', 'disabled')
                } else {
                    $('#job_id').removeClass('is-invalid')
                    $('#submit_create_branch').removeAttr('disabled', 'disabled')
                }
            });

            $('#start_date').change(function () {
                $('#start_date').removeClass('is-invalid')
                $('#submit_create_branch').removeAttr('disabled', 'disabled')
            });

            $('#end_date').change(function () {
                $('#end_date').removeClass('is-invalid')
                $('#submit_create_branch').removeAttr('disabled', 'disabled')
            });

            $('#start_time').change(function () {
                $('#start_time').removeClass('is-invalid')
                $('#submit_create_branch').removeAttr('disabled', 'disabled')
            });

            $('#end_time').change(function () {
                $('#end_time').removeClass('is-invalid')
                $('#submit_create_branch').removeAttr('disabled', 'disabled')
            });

            //    validation to update modal
            $('#user_name_ar').keyup(function () {
                if ($('#user_name_ar').val().length < 3) {
                    $('#user_name_ar').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_name_ar').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#user_name_en').keyup(function () {
                if (!validateEnglish($('#user_name_en').val())) {
                    alert('برجاء ادخال حروف انجليزيه')
                }
                if ($('#user_name_en').val().length < 3) {
                    $('#user_name_en').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_name_en').removeClass('is-invalid')
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#user_email').keyup(function () {
                if (!validEmail($('#user_email').val())) {
                    $('#user_email').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_email').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#user_mobile').keyup(function () {
                if ($('#user_mobile').val().length < 10) {
                    $('#user_mobile').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_mobile').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#user_code').keyup(function () {
                if ($('#user_code').val().length < 3) {
                    $('#user_code').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_code').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#user_password').keyup(function () {
                if ($('#user_password').val().length < 6) {
                    $('#user_password').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_password').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#user_start_date').change(function () {
                $('#user_start_date').removeClass('is-invalid')
                $('#create_user').removeAttr('disabled');
            });

            $('#user_end_date').change(function () {
                $('#user_end_date').removeClass('is-invalid')
                $('#create_user').removeAttr('disabled');
            });

            function validEmail(email) {
                var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }
        })
    </script>




    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>

        new Vue({
            el: '#app',
            data: {

                expire_date: '',
                issue_date: '',
                issue_date_hijri: '',
                expire_date_hijri: ''
            },
            methods: {

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
            },


        });


    </script>
@endsection

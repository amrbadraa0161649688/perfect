@extends('Layouts.master')
@section('content')
    <div class="section-body mt-3">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="">
                                        <label>@lang('home.main_company')</label>
                                        <div class="input-group">
                                            @if(session('company_group'))
                                                <input type="text" class="form-control"
                                                       value="@if(app()->getLocale()=='ar')
                                                       {{ session('company_group')['company_group_ar'] }} @else
                                                       {{ session('company_group')['company_group_en'] }} @endif"
                                                       readonly>
                                            @else
                                                <input type="text" class="form-control"
                                                       value="@if(app()->getLocale()=='ar')
                                                       {{ auth()->user()->companyGroup->company_group_ar }} @else
                                                       {{ auth()->user()->companyGroup->company_group_en }} @endif"
                                                       readonly>
                                            @endif

                                        </div>
                                    </form>
                                </div>

                                <div class="col-md-6">
                                    <form action="">
                                        <label>@lang('home.companies')</label>
                                        <div class="form-group">
                                            <select class="custom-select" name="company_id"
                                                    onchange="this.form.submit()">
                                                <option value="">@lang('home.companies')</option>

                                                @foreach($companies as $company)
                                                    <option value="{{ $company->company_id }}"
                                                            @if($company->company_id == request()->company_id) selected @endif>
                                                        @if(app()->getLocale()=='en')
                                                            {{ $company->company_name_en }}
                                                        @else
                                                            {{ $company->company_name_ar }}
                                                        @endif</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 mb-0">
                            <tbody>
                            <tr>
                                <td class="w60">
                                    <span class="avatar avatar-orange" data-toggle="tooltip" data-placement="top"
                                          title="" data-original-title="Avatar Name">GI</span>
                                </td>
                                <td>
                                    <div class="font-15">@lang('home.job_name')</div>
                                </td>
                                <td><span class="tag tag-success">@lang('home.code')</span></td>
                                <td>@lang('home.status')</td>
                                <td>@lang('home.job_permissions')</td>
                                <td></td>
                            </tr>

                            @foreach($jobs as $job)
                                <tr>
                                    <td class="w60">
                                    <span class="avatar avatar-blue" data-toggle="tooltip" data-placement="top" title=""
                                          data-original-title="Avatar Name">FB</span>
                                    </td>
                                    <td>
                                        <div class="font-15">
                                            @if(app()->getLocale()=='ar')
                                                {{ $job->job_name_ar }}
                                            @else
                                                {{ $job->job_name_en }}
                                            @endif
                                        </div>
                                    </td>
                                    <td><span class="tag tag-success">{{ $job->job_code }}</span></td>
                                    <td>@if($job->job_status == 0) <i class="fa fa-check"></i> @else <i
                                                class="fa fa-remove"></i> @endif</td>
                                    <td>
                                        <span class="tag tag-success" data-toggle="modal"
                                              data-target="#permissionModal{{ $job->job_id }}">
                                        @lang('home.job_permissions')</span>
                                    </td>

                                    <!-- Modal -->
                                    <div class="modal fade" id="permissionModal{{ $job->job_id }}"
                                         tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content" style="width: 700px">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="exampleModalLabel">@lang('home.job_permissions')</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
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
                                                                    <input type="checkbox" disabled="disabled"
                                                                           class="custom-control-input"
                                                                           name="example-checkbox1"
                                                                           value="option1"
                                                                           @if($permission->permission_view) checked @endif>
                                                                    <span class="custom-control-label">@lang('view')</span>
                                                                </label>
                                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                                    <input type="checkbox" disabled="disabled"
                                                                           class="custom-control-input"
                                                                           name="example-checkbox1"
                                                                           value="option1"
                                                                           @if($permission->permission_add) checked @endif>
                                                                    <span class="custom-control-label">@lang('add')</span>
                                                                </label>
                                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                                    <input type="checkbox" disabled="disabled"
                                                                           class="custom-control-input"
                                                                           name="example-checkbox1"
                                                                           value="option1"
                                                                           @if($permission->permission_update) checked @endif>
                                                                    <span class="custom-control-label">@lang('update')</span>
                                                                </label>
                                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                                    <input type="checkbox" disabled="disabled"
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
                                                                           value="option1" disabled="disabled"
                                                                           @if($permission->permission_print) checked @endif>
                                                                    <span class="custom-control-label">@lang('print')</span>
                                                                </label>
                                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                                    <input type="checkbox" disabled="disabled"
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

                                    <td>
                                        <a href="{{route('jobPermissions.show',$job->job_id)}}" class="btn btn-icon"
                                           title="@lang('home.show')">
                                            <i class="fa fa-eye text-danger"></i>
                                        </a>
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

@endsection

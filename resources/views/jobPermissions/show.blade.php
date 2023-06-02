@extends('Layouts.master')
@section('content')
    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    @if(auth()->user()->user_type_id != 1)
                                        @foreach(session('job')->permissions as $job_permission)
                                            @if($job_permission->app_menu_id == 5 && $job_permission->permission_add)
                                                <button class="btn btn-primary mb-15" type="button" data-toggle="modal"
                                                        data-target="#addPermissionModal">
                                                    <i class="icon wb-plus"
                                                       aria-hidden="true"></i> @lang('home.add_permission')
                                                </button>
                                            @endif
                                        @endforeach
                                    @endif
                                    @if(auth()->user()->user_type_id == 1)
                                        <button class="btn btn-primary mb-15" type="button" data-toggle="modal"
                                                data-target="#addPermissionModal">
                                            <i class="icon wb-plus"
                                               aria-hidden="true"></i> @lang('home.add_permission')
                                        </button>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <p style="text-decoration: underline;font-size: 25px">
                                        @if(app()->getLocale()=='ar') {{ $job->job_name_ar }} @else {{ $job->job_name_en }} @endif @lang('home.in')
                                        @if(app()->getLocale()=='ar') {{ $job->companyGroup->company_group_ar }} @else {{ $job->companyGroup->company_group_en }} @endif
                                    </p>
                                </div>
                            </div>


                            {{-- add permission modal --}}
                            <div class="modal fade" id="addPermissionModal" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p style="text-decoration: underline;font-size: 25px">
                                                @if(app()->getLocale()=='ar') {{ $job->job_name_ar }} @else {{ $job->job_name_en }} @endif
                                            </p>
                                            <form action="{{ route('job-permissions.store') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="job_id" value="{{ $job->job_id }}">
                                                <div class="form-group">
                                                    <label for="recipient-name"
                                                           class="col-form-label">@lang('home.companies')</label>
                                                    <select name="company_id" class="form-control is-invalid"
                                                            id="company_id"
                                                            v-model="company_id"
                                                            @change="getCompanyApplications()">
                                                        <option value="">@lang('home.companies')</option>
                                                        @foreach($companies as $company)
                                                            <option value="{{ $company->company_id }}">@if(app()->getLocale() == 'ar') {{ $company->company_name_ar }} @else {{ $company->company_name_en }} @endif</option>
                                                        @endforeach
                                                    </select>

                                                </div>

                                                <div class="form-group">
                                                    <label for="recipient-name"
                                                           class="col-form-label">@lang('home.applications')</label>
                                                    <select name="application_id" class="form-control is-invalid"
                                                            id="application_id"
                                                            v-model="application_id"
                                                            @change="getApplicationsMenu()">
                                                        <option value="">@lang('home.applications')</option>
                                                        <option v-for="application in applications"
                                                                :value="application.app_id">@if(app()->getLocale()=='ar')
                                                                @{{ application.app_name_ar }} @else
                                                                @{{application.app_name_en}} @endif
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="message-text"
                                                           class="col-form-label">@lang('home.applications_menu')</label>
                                                    <select name="app_menu_id" class="form-control is-invalid"
                                                            id="app_menu_id">
                                                        <option>@lang('home.applications_menu')</option>
                                                        <option v-for="application_menu in applications_menu"
                                                                :value="application_menu.app_menu_id">
                                                            @if(app()->getLocale()=='ar') @{{
                                                            application_menu.app_menu_name_ar }}
                                                            @else @{{  application_menu.app_menu_name_en }} @endif
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label class="custom-control custom-checkbox custom-control-inline">
                                                        <input type="checkbox"
                                                               class="custom-control-input"
                                                               name="permission_view">
                                                        <span class="custom-control-label">@lang('view')</span>
                                                    </label>
                                                    <label class="custom-control custom-checkbox custom-control-inline">
                                                        <input type="checkbox"
                                                               class="custom-control-input"
                                                               name="permission_add">
                                                        <span class="custom-control-label">@lang('add')</span>
                                                    </label>
                                                    <label class="custom-control custom-checkbox custom-control-inline">
                                                        <input type="checkbox"
                                                               class="custom-control-input"
                                                               name="permission_update">
                                                        <span class="custom-control-label">@lang('update')</span>
                                                    </label>
                                                    <label class="custom-control custom-checkbox custom-control-inline">
                                                        <input type="checkbox"
                                                               class="custom-control-input"
                                                               name="permission_delete">
                                                        <span class="custom-control-label">@lang('delete')</span>
                                                    </label>
                                                    <label class="custom-control custom-checkbox custom-control-inline">
                                                        <input type="checkbox"
                                                               class="custom-control-input"
                                                               name="permission_print">
                                                        <span class="custom-control-label">@lang('print')</span>
                                                    </label>
                                                    <label class="custom-control custom-checkbox custom-control-inline">
                                                        <input type="checkbox"
                                                               class="custom-control-input"
                                                               name="permission_approve">
                                                        <span class="custom-control-label">@lang('approve')</span>
                                                    </label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">
                                                        @lang('home.close')
                                                    </button>
                                                    <button type="submit" id="submit"
                                                            class="btn btn-primary">@lang('home.save')</button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- all permissions --}}
                            <div class="table-responsive">
                                <table class="table  table-hover table-vcenter table-striped" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>@lang('home.name')</th>
                                        <th>@lang('home.company')</th>
                                        <th>@lang('home.application_menu_name')</th>
                                        <th>@lang('home.view')</th>
                                        <th>@lang('home.add')</th>
                                        <th>@lang('home.update')</th>
                                        <th>@lang('home.delete')</th>
                                        <th>@lang('home.print')</th>
                                        <th>@lang('home.approve')</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($job->permissions as $permission)
                                        <tr class="gradeA">
                                            <td>@if(app()->getLocale()=='en'){{ $permission->permission_name_en }} @else {{ $permission->permission_name_ar }} @endif</td>
                                            <td>@if(app()->getLocale() == 'en') {{ $permission->company->company_name_en }} @else {{ $permission->company->company_name_ar }} @endif</td>
                                            <td>@if(app()->getLocale()=='en') {{ $permission->applicationMenu->app_menu_name_ar }} @else {{ $permission->applicationMenu->app_menu_name_en }} @endif</td>
                                            <td>
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" disabled="disabled"
                                                           class="custom-control-input"
                                                           name="example-checkbox1"
                                                           value="option1"
                                                           @if($permission->permission_view) checked @endif>
                                                    <span class="custom-control-label">@lang('home.view')</span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="custom-control custom-checkbox">
                                                    <input type="checkbox" disabled="disabled"
                                                           class="custom-control-input"
                                                           name="example-checkbox1"
                                                           value="option1"
                                                           @if($permission->permission_add) checked @endif>
                                                    <span class="custom-control-label">@lang('home.add')</span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="custom-control custom-checkbox">
                                                    <input type="checkbox" disabled="disabled"
                                                           class="custom-control-input"
                                                           name="example-checkbox1"
                                                           value="option1"
                                                           @if($permission->permission_update) checked @endif>
                                                    <span class="custom-control-label">@lang('home.update')</span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="custom-control custom-checkbox">
                                                    <input type="checkbox" disabled="disabled"
                                                           class="custom-control-input"
                                                           name="example-checkbox1"
                                                           value="option1"
                                                           @if($permission->permission_delete) checked @endif>
                                                    <span class="custom-control-label">@lang('home.delete')</span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="custom-control custom-checkbox">
                                                    <input type="checkbox" disabled="disabled"
                                                           class="custom-control-input"
                                                           name="example-checkbox1"
                                                           value="option1"
                                                           @if($permission->permission_print) checked @endif>
                                                    <span class="custom-control-label">@lang('home.print')</span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="custom-control custom-checkbox">
                                                    <input type="checkbox" disabled="disabled"
                                                           class="custom-control-input"
                                                           name="example-checkbox1"
                                                           value="option1"
                                                           @if($permission->permission_approve) checked @endif>
                                                    <span class="custom-control-label">@lang('home.approve')</span>
                                                </label>
                                            </td>
                                            <td class="actions">
                                                <button class="btn btn-sm btn-icon on-editing m-r-5 button-save"
                                                        data-toggle="tooltip" data-original-title="Save" hidden><i
                                                            class="icon-drawer" aria-hidden="true"></i></button>
                                                <button class="btn btn-sm btn-icon on-editing button-discard"
                                                        data-toggle="tooltip" data-original-title="Discard" hidden>
                                                    <i class="icon-close" aria-hidden="true"></i></button>

                                                @if(auth()->user()->user_type_id != 1)
                                                    @foreach(session('job')->permissions as $job_permission)
                                                        @if($job_permission->app_menu_id == 5 && $job_permission->permission_update)
                                                            <button class="btn btn-sm btn-icon on-default m-r-5 button-edit"
                                                                    data-toggle="modal"
                                                                    data-target="#updatePermissionModal{{ $permission->permission_id }}">
                                                                <i
                                                                        class="icon-pencil" aria-hidden="true"></i>
                                                            </button>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                @if(auth()->user()->user_type_id == 1)
                                                    <button class="btn btn-sm btn-icon on-default m-r-5 button-edit"
                                                            data-toggle="modal"
                                                            data-target="#updatePermissionModal{{ $permission->permission_id }}">
                                                        <i
                                                                class="icon-pencil" aria-hidden="true"></i>
                                                    </button>
                                                @endif


                                                @if(auth()->user()->user_type_id != 1)
                                                    @foreach(session('job')->permissions as $job_permission)
                                                        @if($job_permission->app_menu_id == 5 && $job_permission->permission_delete)
                                                            <form action="{{ route('job-permission.delete',$permission->permission_id) }}"
                                                                  method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <button class="btn btn-sm btn-icon on-default button-remove"
                                                                        type="submit" data-original-title="Remove"><i
                                                                            class="icon-trash" aria-hidden="true"></i>
                                                                </button>

                                                            </form>
                                                        @endif
                                                    @endforeach
                                                @endif

                                                @if(auth()->user()->user_type_id == 1)
                                                    <form action="{{ route('job-permission.delete',$permission->permission_id) }}"
                                                          method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btn-sm btn-icon on-default button-remove"
                                                                type="submit" data-original-title="Remove"><i
                                                                    class="icon-trash" aria-hidden="true"></i>
                                                        </button>

                                                    </form>
                                                @endif

                                                {{-- update permission modal --}}
                                                <div class="modal fade"
                                                     id="updatePermissionModal{{ $permission->permission_id }}"
                                                     tabindex="-1" role="dialog"
                                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close"
                                                                        data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p style="text-decoration: underline;font-size: 25px">
                                                                    @if(app()->getLocale()=='ar') {{ $job->job_name_ar }} @else {{ $job->job_name_en }} @endif
                                                                    @lang('home.in')   @if(app()->getLocale()=='ar') {{ $permission->company->company_name_ar }} @else {{ $permission->company->company_name_en }} @endif
                                                                </p>
                                                                <form action="{{ route('job-permissions.update',$permission->permission_id) }}"
                                                                      method="post">
                                                                    @csrf
                                                                    @method('put')
                                                                    <p>@if(app()->getLocale()=='en') {{ $permission->permission_name_en }} @else {{ $permission->permission_name_ar }} @endif</p>
                                                                    <div class="form-group">
                                                                        <label class="custom-control custom-checkbox">
                                                                            <input type="checkbox"
                                                                                   class="custom-control-input"
                                                                                   name="permission_view"
                                                                                   @if($permission->permission_view) checked @endif>
                                                                            <span class="custom-control-label">@lang('view')</span>
                                                                        </label>
                                                                        <label class="custom-control custom-checkbox">
                                                                            <input type="checkbox"
                                                                                   @if($permission->permission_add) checked
                                                                                   @endif
                                                                                   class="custom-control-input"
                                                                                   name="permission_add">
                                                                            <span class="custom-control-label">@lang('add')</span>
                                                                        </label>
                                                                        <label class="custom-control custom-checkbox">
                                                                            <input type="checkbox"
                                                                                   @if($permission->permission_update) checked
                                                                                   @endif
                                                                                   class="custom-control-input"
                                                                                   name="permission_update">
                                                                            <span class="custom-control-label">@lang('update')</span>
                                                                        </label>
                                                                        <label class="custom-control custom-checkbox">
                                                                            <input type="checkbox"
                                                                                   @if($permission->permission_delete) checked
                                                                                   @endif
                                                                                   class="custom-control-input"
                                                                                   name="permission_delete">
                                                                            <span class="custom-control-label">@lang('delete')</span>
                                                                        </label>
                                                                        <label class="custom-control custom-checkbox">
                                                                            <input type="checkbox"
                                                                                   @if($permission->permission_print) checked
                                                                                   @endif
                                                                                   class="custom-control-input"
                                                                                   name="permission_print">
                                                                            <span class="custom-control-label">@lang('print')</span>
                                                                        </label>
                                                                        <label class="custom-control custom-checkbox">
                                                                            <input type="checkbox"
                                                                                   @if($permission->permission_approve) checked
                                                                                   @endif
                                                                                   class="custom-control-input"
                                                                                   name="permission_approve">
                                                                            <span class="custom-control-label">@lang('approve')</span>
                                                                        </label>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-dismiss="modal">
                                                                            @lang('home.close')
                                                                        </button>
                                                                        <button type="submit"
                                                                                class="btn btn-primary">@lang('home.save')</button>
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
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#company_id').change(function () {
                if (!$('#company_id')) {
                    $('#company_id').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#company_id').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });
            $('#application_id').change(function () {
                if (!$('#application_id')) {
                    $('#application_id').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#application_id').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });
            $('#app_menu_id').change(function () {
                if (!$('#app_menu_id')) {
                    $('#app_menu_id').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#app_menu_id').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                applications: {},
                applications_menu: {},
                app_menu_id: '',
                application_id: '',
                application_menu_id: '',
                company_id: '',
            },
            methods: {
                getCompanyApplications() {
                    $.ajax({
                        method: 'GET',
                        data: {company_id: this.company_id},
                        url: '{{ route("get-company-apps") }}'
                    }).then(response => {
                        this.applications = response.data
                        console.log(this.applications)
                    })
                },

                getApplicationsMenu() {
                    $.ajax({
                        method: 'GET',
                        data: {application_id: this.application_id},
                        url: '{{ route("get-applications-menu") }}'
                    }).then(response => {
                        this.applications_menu = response.data
                        console.log(this.applications_menu)
                    })
                },
            }
        });
    </script>
@endsection

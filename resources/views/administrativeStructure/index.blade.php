@extends('Layouts.master')

@section('content')
    <div id="app">
        <div class="section-body">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><a class="nav-link @if(!request()->qr) active @endif" id="tree-tab"
                                                data-toggle="tab"
                                                href="#tree-list">@lang('home.administrative_structure_tree')</a>
                        </li>
                        <li class="nav-item"><a
                                    class="nav-link @if(request()->qr == 'department') active @endif"
                                    id="departments-tab" data-toggle="tab"
                                    href="#departments-grid">@lang('home.departments')</a></li>
                        <li class="nav-item"><a class="nav-link @if(request()->qr == 'division') active @endif"
                                                id="division-tab" data-toggle="tab"
                                                href="#divisions-grid">@lang('home.divisions')</a></li>
                        <li class="nav-item"><a class="nav-link @if(request()->qr == 'job') active @endif" id="job-tab"
                                                data-toggle="tab"
                                                href="#jobs-grid">@lang('home.jobs')</a></li>
                    </ul>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <form action="">
                            @if(session('company_group'))
                                <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                                {{ session('company_group')['company_group_ar'] }} @else
                                {{ session('company_group')['company_group_en'] }} @endif" readonly>
                            @else
                                <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                                {{ auth()->user()->companyGroup->company_group_ar }} @else
                                {{ auth()->user()->companyGroup->company_group_en }} @endif" readonly>
                            @endif
                        </form>
                    </div>

                    <div class="col-md-6">
                        <form action="" class="row">
                            <select class="form-control" name="company_id" onchange="this.form.submit()">
                                <option value="">@lang('home.choose_company')</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->company_id }}"
                                            @if(request()->company_id == $company->company_id) selected @endif>
                                        @if(app()->getLocale()=='en') {{ $company->company_name_en }}
                                        @else {{ $company->company_name_ar }} @endif</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <div class="section-body mt-3">
            <div class="container-fluid">
                <div class="tab-content mt-3">

                    {{-- departments section --}}
                    <div class="tab-pane fade @if(request()->qr == 'department') active show @endif"
                         id="departments-grid"
                         role="tabpanel">
                        <div class="card">
                            @include('Includes.form-errors')
                            <div class="card-header">
                                <div class="header-action">
                                    @if(auth()->user()->user_type_id == 1)
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#exampleModal">
                                            <i class="fe fe-plus mr-2"></i>@lang('home.add_department')
                                        </button>
                                    @else
                                        @foreach(session('job')->permissions as $job_permission)
                                            @if($job_permission->app_menu_id == 3 && $job_permission->permission_add)
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#exampleModal">
                                                    <i class="fe fe-plus mr-2"></i>@lang('home.add_department')
                                                </button>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>

                                {{--pop up to create department --}}
                                <div class="modal fade" id="exampleModal" tabindex="-1"
                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="exampleModalLabel">@lang('home.add_department')</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('department.store') }}" method="post"
                                                      id="submit_department_form">
                                                    @csrf
                                                    <p style="font-size:25px;text-decoration: underline">
                                                        @if(session('company_group'))
                                                            @if(app()->getLocale() == 'ar')
                                                                {{ session('company_group')['company_group_ar'] }}
                                                            @else
                                                                {{ session('company_group')['company_group_en'] }}
                                                            @endif
                                                        @else

                                                            @if(app()->getLocale() == 'ar')
                                                                {{ auth()->user()->companyGroup->company_group_ar }}
                                                            @else
                                                                {{ auth()->user()->companyGroup->company_group_en }}
                                                            @endif

                                                        @endif
                                                    </p>

                                                    <div class="mb-3">

                                                        <div class="row">

                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="col-form-label"> @lang('home.name_ar') </label>
                                                                <input type="text" class="form-control is-invalid"
                                                                       name="department_name_ar"
                                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                                                       id="department_name_ar"
                                                                       placeholder="@lang('home.name_ar')">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="col-form-label"> @lang('home.name_en') </label>
                                                                <input type="text" class="form-control is-invalid"
                                                                       name="department_name_en"
                                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                                                       id="department_name_en"
                                                                       placeholder="@lang('home.name_en')">
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <div class="row">
                                                            <label for="message-text"
                                                                   class="col-form-label"> @lang('home.department_code')</label>
                                                            <input type="text" class="form-control is-invalid"
                                                                   name="department_code"
                                                                   id="department_code"
                                                                   placeholder="@lang('home.department_code')">
                                                        </div>

                                                        <div class="row">
                                                            <label for="message-text"
                                                                   class="col-form-label"> @lang('home.companies')</label>
                                                            <select class="form-select form-control" name="company_id[]"
                                                                    aria-label="Default select example" multiple>
                                                                @foreach($companies as $company)
                                                                    <option value="{{ $company->company_id }}">
                                                                        @if(app()->getLocale()== 'ar')
                                                                            {{ $company->company_name_ar }}
                                                                        @else
                                                                            {{ $company->company_name_en }}
                                                                        @endif
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary mr-2"
                                                                data-bs-dismiss="modal"
                                                                id="create_department">@lang('home.save')</button>
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">@lang('home.close')</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- table to show department --}}

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-vcenter table-hover mb-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('home.department_name')</th>
                                            <th>@lang('home.department_code')</th>
                                            <th>@lang('home.companies')</th>
                                            <th>@lang('home.divisions')</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <p style="text-decoration: underline;font-size:25px;">

                                            @if(session('company'))
                                                @if(app()->getLocale()=='ar')
                                                    {{ session('company')['company_name_ar'] }}
                                                @else
                                                    {{ session('company')['company_name_en'] }}
                                                @endif

                                            @else
                                                @if(app()->getLocale()=='ar')
                                                    {{ auth()->user()->company->company_name_ar }}
                                                @else
                                                    {{ auth()->user()->company->company_name_en }}
                                                @endif
                                            @endif
                                        </p>

                                        @foreach($departments as $k=>$department)
                                            <tr>
                                                <td> {{ $k+1 }} </td>
                                                <td>@if(app()->getLocale() == 'ar')
                                                        {{ $department->department_name_ar }}
                                                    @else
                                                        {{ $department->department_name_en }}
                                                    @endif
                                                </td>
                                                <td>{{ $department->department_code }}</td>
                                                <td>
                                                    @foreach(json_decode($department->company_id) as $company_id)
                                                        @if(app()->getLocale()=='ar')
                                                            {{ $company=\App\Models\Company::where('company_id',$company_id)->first()->company_name_ar }}
                                                            ,
                                                        @else
                                                            {{ $company=\App\Models\Company::where('company_id',$company_id)->first()->company_name_en }}
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($department->divisions as $division)
                                                        @if(app()->getLocale()=='en')
                                                            {{ $division->division_name_en .' , ' }}
                                                        @else
                                                            {{ $division->division_name_ar . ' , '}}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if(auth()->user()->user_type_id != 1)
                                                        @foreach(session('job')->permissions as $job_permission)
                                                            @if($job_permission->app_menu_id == 3 && $job_permission->permission_update)
                                                                <a href="{{ route('department.edit',$department->department_id) }}"
                                                                   class="btn btn-icon" title="@lang('home.edit')"><i
                                                                            class="fa fa-edit"></i></a>
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                    @if(auth()->user()->user_type_id == 1)
                                                        <a href="{{ route('department.edit',$department->department_id) }}"
                                                           class="btn btn-icon" title="@lang('home.edit')"><i
                                                                    class="fa fa-edit"></i></a>
                                                    @endif

                                                    @if(auth()->user()->user_type_id != 1)
                                                        @foreach(session('job')->permissions as $job_permission)
                                                            @if($job_permission->app_menu_id == 3 && $job_permission->permission_delete)
                                                                <form action="{{ route('department.delete',$department->department_id) }}"
                                                                      method="post">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <button type="submit"
                                                                            class="btn btn-icon js-sweetalert"
                                                                            title="@lang('home.delete')"
                                                                            data-type="confirm"><i
                                                                                class="fa fa-trash-o text-danger"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                    @if(auth()->user()->user_type_id == 1)
                                                        <form action="{{ route('department.delete',$department->department_id) }}"
                                                              method="post">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit"
                                                                    class="btn btn-icon js-sweetalert"
                                                                    title="@lang('home.delete')"
                                                                    data-type="confirm"><i
                                                                        class="fa fa-trash-o text-danger"></i>
                                                            </button>
                                                        </form>
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


                    {{-- divisions section --}}
                    <div class="tab-pane fade @if(request()->qr == 'division') active show @endif" id="divisions-grid"
                         role="tabpanel">
                        <div class="card">
                            @include('Includes.form-errors')
                            <div class="card-header">
                                <div class="header-action">
                                    @if(auth()->user()->user_type_id == 1)
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#divisionModal">
                                            <i class="fe fe-plus mr-2"></i>@lang('home.add_division')
                                        </button>
                                    @else
                                        @foreach(session('job')->permissions as $job_permission)
                                            @if($job_permission->app_menu_id == 3 && $job_permission->permission_add)
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#divisionModal">
                                                    <i class="fe fe-plus mr-2"></i>@lang('home.add_division')
                                                </button>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>

                                {{--pop up to create division --}}
                                <div class="modal fade" id="divisionModal" tabindex="-1"
                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="exampleModalLabel">@lang('home.add_division')</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="post"
                                                      id="submit_division_form">
                                                    @csrf
                                                    @if(session('company_group'))
                                                        <p style="font-size:25px;text-decoration: underline">
                                                            @if(app()->getLocale()=='ar')
                                                                {{ session('company_group')['company_group_ar'] }}
                                                            @else
                                                                {{ session('company_group')['company_group_en'] }}
                                                            @endif
                                                        </p>
                                                    @else
                                                        <p style="font-size:25px;text-decoration: underline">
                                                            @if(app()->getLocale()=='ar')
                                                                {{ auth()->user()->companyGroup->company_group_ar }}
                                                            @else
                                                                {{ auth()->user()->companyGroup->company_group_en }}
                                                            @endif
                                                        </p>
                                                    @endif

                                                    {{-----------------------------------------------------------------------------------------------}}
                                                    <div class="mb-3">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="col-form-label"> @lang('home.name_ar') </label>
                                                                <input type="text" class="form-control"
                                                                       v-bind:class="{ 'is-invalid' : name_ar_message }"
                                                                       v-model="division_name_ar"
                                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');">
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="col-form-label"> @lang('home.name_en') </label>
                                                                <input type="text" class="form-control"
                                                                       v-bind:class="{ 'is-invalid': name_en_message }"
                                                                       v-model="division_name_en"
                                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');">
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <div class="row">
                                                            <label for="message-text"
                                                                   class="col-form-label"> @lang('home.division_code')</label>
                                                            <input type="text" class="form-control"
                                                                   v-model="division_code"
                                                                   v-bind:class="{'is-invalid' : code_message}">
                                                        </div>

                                                        <div class="row">
                                                            <label for="message-text"
                                                                   class="col-form-label"> @lang('home.departments')</label>
                                                            <select class="form-select form-control"
                                                                    aria-label="Default select example"
                                                                    v-bind:class="{ 'is-invalid' : department_message}"
                                                                    v-model="department_id" @change="getCompanies()">
                                                                @foreach($departments as $department)
                                                                    <option value="{{ $department->department_id }}">@if(app()->getLocale()== 'ar') {{ $department->department_name_ar }} @else {{ $department->department_name_en }} @endif</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="row">
                                                            <label for="message-text"
                                                                   class="col-form-label"> @lang('home.companies')</label>
                                                            <select class="form-select form-control" multiple
                                                                    v-bind:class="{ 'is-invalid' : company_message}"
                                                                    aria-label="Default select example"
                                                                    v-model="company_id">
                                                                @if(app()->getLocale() == 'ar')
                                                                    <option v-for="department_company,index in department_companies"
                                                                            :value="department_company.company_id">@{{
                                                                        department_company.company_name_ar }}
                                                                    </option>
                                                                @else
                                                                    <option v-for="department_company,index in department_companies"
                                                                            :value="department_company.company_id">@{{
                                                                        department_company.company_name_en }}
                                                                    </option>
                                                                @endif

                                                            </select>
                                                        </div>

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary mr-2"
                                                                @click="createDivision"
                                                                :disabled="disabled_division==1"
                                                                id="create_division">@lang('home.save')</button>
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">@lang('home.close')</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="card-body">

                                <div class="alert alert-danger alert-block" v-if="division_error">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>@{{ division_error }}</strong>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-vcenter table-hover mb-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('home.division_name')</th>
                                            <th>@lang('home.division_code')</th>
                                            <th>@lang('home.division_status')</th>
                                            <th>@lang('home.department')</th>
                                            <th>@lang('home.companies')</th>
                                            <th>@lang('home.jobs')</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <p style="text-decoration: underline;font-size:25px;">
                                            @if(auth()->user()->user_type_id != 1)
                                                @if(app()->getLocale()=='ar')
                                                    {{ auth()->user()->company->company_name_ar }}
                                                @else
                                                    {{ auth()->user()->company->company_name_en }}
                                                @endif
                                            @else
                                                @if(app()->getLocale()=='ar')
                                                    {{ session('company')['company_name_ar'] }}
                                                @else
                                                    {{ session('company')['company_name_en'] }}
                                                @endif
                                            @endif
                                        </p>

                                        @foreach($divisions as $k=>$division)
                                            <tr>
                                                <td> {{ $k+1 }} </td>
                                                <td>@if(app()->getLocale() == 'ar') {{ $division->division_name_ar }} @else {{ $division->division_name_en }} @endif</td>
                                                <td>{{ $division->division_code }}</td>
                                                <td>@if($division->division_status) <i
                                                            class="fa fa-check"></i> @else
                                                        <i
                                                                class="fa fa-remove"></i>  @endif</td>
                                                <td>@if(app()->getLocale()=='en') {{ $division->department->department_name_en }} @else {{ $division->department->department_name_ar }} @endif</td>
                                                <td>
                                                    @foreach(json_decode($division->company_id) as $company_id)
                                                        @if(app()->getLocale()=='ar')
                                                            {{ $company=\App\Models\Company::where('company_id',$company_id)->first()->company_name_ar }}
                                                            ,
                                                        @else
                                                            {{ $company=\App\Models\Company::where('company_id',$company_id)->first()->company_name_en }}
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($division->jobs as $job)
                                                        @if(app()->getLocale()=='en')  {{ $job->job_name_en .
                                                    ' , ' }}  @else {{ $job->job_name_ar . ' , '}}  @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if(auth()->user()->user_type_id != 1)
                                                        @foreach(session('job')->permissions as $job_permission)
                                                            @if($job_permission->app_menu_id == 3 && $job_permission->permission_update)
                                                                <a href="{{ route('division.edit',$division->division_id) }}"
                                                                   class="btn btn-icon" title="@lang('home.edit')"><i
                                                                            class="fa fa-edit"></i></a>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    @if(auth()->user()->user_type_id == 1)
                                                        <a href="{{ route('division.edit',$division->division_id) }}"
                                                           class="btn btn-icon" title="@lang('home.edit')"><i
                                                                    class="fa fa-edit"></i></a>

                                                    @endif

                                                    @if(auth()->user()->user_type_id != 1)
                                                        @foreach(session('job')->permissions as $job_permission)
                                                            @if($job_permission->app_menu_id == 3 && $job_permission->permission_delete)
                                                                <form action="{{ route('division.delete',$division->division_id) }}"
                                                                      method="post">
                                                                    @method('delete')
                                                                    @csrf
                                                                    <button type="submit"
                                                                            class="btn btn-icon js-sweetalert"
                                                                            title="Delete"
                                                                            data-type="confirm"><i
                                                                                class="fa fa-trash-o text-danger"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                    @if(auth()->user()->user_type_id == 1)
                                                        <form action="{{ route('division.delete',$division->division_id) }}"
                                                              method="post">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit"
                                                                    class="btn btn-icon js-sweetalert"
                                                                    title="Delete"
                                                                    data-type="confirm"><i
                                                                        class="fa fa-trash-o text-danger"></i>
                                                            </button>
                                                        </form>
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


                    {{-- Jobs section --}}
                    <div class="tab-pane fade @if(request()->qr == 'job') active show @endif" id="jobs-grid"
                         role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="header-action">
                                    @if(auth()->user()->user_type_id == 1)
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#jobModal">
                                            <i class="fe fe-plus mr-2"></i>@lang('home.add_job')
                                        </button>
                                    @else
                                        @foreach(session('job')->permissions as $job_permission)
                                            @if($job_permission->app_menu_id == 3 && $job_permission->permission_add)
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#jobModal">
                                                    <i class="fe fe-plus mr-2"></i>@lang('home.add_job')
                                                </button>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>

                                {{--pop up to create job --}}
                                <div class="modal fade" id="jobModal" tabindex="-1"
                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="exampleModalLabel">@lang('home.add_job')</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="post"
                                                      id="submit_division_form">
                                                    @csrf

                                                    @if(session('company_group'))
                                                        <p style="font-size:25px;text-decoration: underline">
                                                            @if(app()->getLocale()=='ar')
                                                                {{ session('company_group')['company_group_ar'] }}
                                                            @else
                                                                {{ session('company_group')['company_group_en'] }}
                                                            @endif
                                                        </p>
                                                    @else
                                                        <p style="font-size:25px;text-decoration: underline">
                                                            @if(app()->getLocale()=='ar')
                                                                {{ auth()->user()->companyGroup->company_group_ar }}
                                                            @else
                                                                {{ auth()->user()->companyGroup->company_group_en }}
                                                            @endif
                                                        </p>
                                                    @endif

                                                    <div class="mb-3">

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="col-form-label"> @lang('home.name_ar') </label>
                                                                <input type="text" class="form-control"
                                                                       v-bind:class="{ 'is-invalid' : job_name_ar_message }"
                                                                       v-model="job_name_ar"
                                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');">
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="col-form-label"> @lang('home.name_en') </label>
                                                                <input type="text" class="form-control"
                                                                       v-model="job_name_en"
                                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                                                       v-bind:class="{ 'is-invalid' : job_name_en_message }">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <div class="row">
                                                            <label for="message-text"
                                                                   class="col-form-label"> @lang('home.job_code')</label>
                                                            <input type="text" class="form-control"
                                                                   v-model="job_code"
                                                                   v-bind:class="{ 'is-invalid' : job_code_message }">
                                                        </div>

                                                        <div class="row">
                                                            <label for="message-text"
                                                                   class="col-form-label"> @lang('home.divisions')</label>
                                                            <select class="form-select form-control"
                                                                    aria-label="Default select example"
                                                                    v-model="division_id"
                                                                    v-bind:class="{ 'is-invalid' : division_message }"
                                                                    @change="getDivisionCompanies()">
                                                                <option value="">@lang('home.divisions')</option>
                                                                @foreach($divisions as $division)
                                                                    <option value="{{ $division->division_id }}">@if(app()->getLocale()== 'ar') {{ $division->division_name_ar }} @else {{ $division->division_name_en }} @endif</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="row">
                                                            <label for="message-text"
                                                                   class="col-form-label"> @lang('home.companies')</label>
                                                            <select class="form-select form-control" multiple
                                                                    aria-label="Default select example"
                                                                    v-bind:class="{ 'is-invalid' : division_company_message }"
                                                                    v-model="division_company_id">
                                                                {{--<option value="">@lang('home.companies')</option>--}}
                                                                @if(app()->getLocale() == 'ar')
                                                                    <option v-for="division_company,index in division_companies"
                                                                            :value="division_company.company_id">@{{
                                                                        division_company.company_name_ar }}
                                                                    </option>
                                                                @else
                                                                    <option v-for="division_company,index in division_companies"
                                                                            :value="division_company.company_id">@{{
                                                                        division_company.company_name_en }}
                                                                    </option>
                                                                @endif

                                                            </select>
                                                        </div>

                                                        <div class="row">
                                                            <label for="message-text"
                                                                   class="col-form-label"> @lang('home.status')</label>
                                                            <select class="form-select form-control" name="job_status"
                                                                    aria-label="Default select example">
                                                                <option value="1">on</option>
                                                                <option value="0">off</option>
                                                            </select>
                                                        </div>

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary mr-2"
                                                                @click="createJob" :disabled="disabled_job==1"
                                                                id="create_job">@lang('home.save')</button>
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">@lang('home.close')</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-body">

                                <div class="alert alert-danger alert-block" v-if="job_error">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>@{{ job_error }}</strong>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-vcenter table-hover mb-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('home.job_name')</th>
                                            <th>@lang('home.job_code')</th>
                                            <th>@lang('home.job_status')</th>
                                            <th>@lang('home.department')</th>
                                            <th>@lang('home.division')</th>
                                            <th>@lang('home.companies')</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <p style="text-decoration: underline;font-size:25px;">

                                            @if(auth()->user()->user_type_id != 1)
                                                @if(app()->getLocale()=='ar')
                                                    {{ auth()->user()->company->company_name_ar }}
                                                @else
                                                    {{ auth()->user()->company->company_name_en }}
                                                @endif
                                            @else
                                                @if(app()->getLocale()=='ar')
                                                    {{ session('company')['company_name_ar'] }}
                                                @else
                                                    {{ session('company')['company_name_en'] }}
                                                @endif
                                            @endif
                                        </p>


                                        @foreach($jobs as $k=>$job)
                                            <tr>
                                                <td> {{ $k+1 }} </td>
                                                <td>@if(app()->getLocale() == 'ar') {{ $job->job_name_ar }} @else {{ $job->job_name_en }} @endif</td>
                                                <td>{{ $job->job_code }}</td>
                                                <td>@if($job->job_status) <i class="fa fa-check"></i> @else   <i
                                                            class="fa fa-remove"></i>  @endif</td>
                                                <td>@if(app()->getLocale()=='en') {{ $job->department->department_name_en }} @else {{ $job->department->department_name_ar }} @endif</td>
                                                <td>@if(app()->getLocale()=='en') {{ $job->division->division_name_en }} @else {{ $job->division->division_name_ar }} @endif</td>
                                                <td>
                                                    @foreach(json_decode($job->company_id) as $company_id)
                                                        @if(app()->getLocale()=='ar')
                                                            {{ $company=\App\Models\Company::where('company_id',$company_id)->first()->company_name_ar }}
                                                            ,
                                                        @else
                                                            {{ $company=\App\Models\Company::where('company_id',$company_id)->first()->company_name_en }}
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if(auth()->user()->user_type_id != 1)
                                                        @foreach(session('job')->permissions as $job_permission)
                                                            @if($job_permission->app_menu_id == 3 && $job_permission->permission_update)
                                                                <a href="{{ route('job.edit',$job->job_id) }}"
                                                                   class="btn btn-icon"
                                                                   title="@lang('home.edit')"><i
                                                                            class="fa fa-edit"></i></a>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    @if(auth()->user()->user_type_id == 1)
                                                        <a href="{{ route('job.edit',$job->job_id) }}"
                                                           class="btn btn-icon"
                                                           title="@lang('home.edit')"><i
                                                                    class="fa fa-edit"></i></a>
                                                    @endif

                                                    @if(auth()->user()->user_type_id != 1)
                                                        @foreach(session('job')->permissions as $job_permission)
                                                            @if($job_permission->app_menu_id == 3 && $job_permission->permission_delete)
                                                                <form action="{{ route('job.delete',$job->job_id) }}"
                                                                      method="post">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <button type="submit"
                                                                            class="btn btn-icon js-sweetalert"
                                                                            title="@lang('home.delete')"
                                                                            data-type="confirm"><i
                                                                                class="fa fa-trash-o text-danger"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                    @if(auth()->user()->user_type_id == 1)
                                                        <form action="{{ route('job.delete',$job->job_id) }}"
                                                              method="post">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit"
                                                                    class="btn btn-icon js-sweetalert"
                                                                    title="@lang('home.delete')"
                                                                    data-type="confirm"><i
                                                                        class="fa fa-trash-o text-danger"></i>
                                                            </button>
                                                        </form>

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

                    {{--  tree view --}}
                    <div class="tab-pane fade @if(!request()->qr) show active @endif" id="tree-list" role="tabpanel">
                        <div class="row clearfix">

                            <div class="treeview-animated w-20 mx-4 my-4">
                                {{--<h6 class="pt-3 pl-3">@if(app()->getLocale()=='ar') {{ $company->company_name_ar }} @else {{ $company->company_name_en }} @endif</h6>--}}
                                {{--<hr>--}}
                                @foreach($departments as $company_department)
                                    <ul class="treeview-animated-list mb-3">
                                        <li class="treeview-animated-items">
                                            <a class="treeview-animated-items-header">
                                                <i class="fa fa-plus-circle"></i>
                                                <span>@if(app()->getLocale() == 'ar')
                                                        {{ $company_department->department_name_ar }}
                                                    @else {{ $company_department->department_name_en }}
                                                    @endif
                                            </span>
                                            </a>
                                            <ul class="nested">
                                                @foreach($company_department->divisionsJobs as $company_division)
                                                    <li>
                                                        <a class="treeview-animated-items-header">
                                                            <i class="fa fa-plus-circle"></i>
                                                            <span>
                                                                @if(app()->getLocale()=='ar'){{ $company_division->division_name_ar }}
                                                                @else {{ $company_division->division_name_en }} @endif
                                                            </span>
                                                        </a>
                                                        <ul class="nested">
                                                            @foreach($company_division->jobsCompany as $company_job)
                                                                <li>
                                                                    <div class="treeview-animated-element">
                                                                        @if(app()->getLocale()=='ar') {{ $company_job->job_name_ar }} @else {{ $company_job->job_name_en }} @endif
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    </ul>
                                @endforeach
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
            //    validation to create department modal
            $('#department_name_en').keyup(function () {
                if ($('#department_name_en').val().length < 3) {
                    $('#department_name_en').addClass('is-invalid');
                    $('#create_department').attr('disabled', 'disabled');
                } else {
                    $('#department_name_en').removeClass('is-invalid');
                    $('#create_department').removeAttr('disabled');
                }
            });

            $('#department_name_ar').keyup(function () {
                if ($('#department_name_ar').val().length < 3) {
                    $('#department_name_ar').addClass('is-invalid');
                    $('#create_department').attr('disabled', 'disabled');
                } else {
                    $('#department_name_ar').removeClass('is-invalid');
                    $('#create_department').removeAttr('disabled');
                }
            });

            $('#department_code').keyup(function () {
                if ($('#department_code').val().length < 3) {
                    $('#department_code').addClass('is-invalid');
                    $('#create_department').attr('disabled', 'disabled');
                } else {
                    $('#department_code').removeClass('is-invalid');
                    $('#create_department').removeAttr('disabled');
                }
            });

            (function ($) {

                let $allPanels = $('.nested').hide();
                let $elements = $('.treeview-animated-element');

                $('.treeview-animated-items-header').click(function () {
                    $this = $(this);
                    $target = $this.siblings('.nested');
                    $pointerPlus = $this.children('.fa-plus-circle');
                    $pointerMinus = $this.children('.fa-minus-circle');

                    $pointerPlus.removeClass('fa-plus-circle');
                    $pointerPlus.addClass('fa-minus-circle');
                    $pointerMinus.removeClass('fa-minus-circle');
                    $pointerMinus.addClass('fa-plus-circle');
                    $this.toggleClass('open')
                    if (!$target.hasClass('active')) {
                        $target.addClass('active').slideDown();
                    } else {
                        $target.removeClass('active').slideUp();
                    }

                    return false;
                });
                $elements.click(function () {
                    $this = $(this);

                    if ($this.hasClass('opened')) {

                        $elements.removeClass('opened');
                    } else {

                        $elements.removeClass('opened');
                        $this.addClass('opened');
                    }
                })
            })(jQuery);

            $(".modal form input").click(function (event) {
                event.stopPropagation();
            });

            $(".modal form select").click(function (event) {
                event.stopPropagation();
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                division_name_ar: '',
                division_name_en: '',
                division_code: '',
                department_id: '',
                company_id: [],
                department_companies: {},

                job_name_ar: '',
                job_name_en: '',
                job_code: '',
                division_id: '',
                division_company_id: [],
                division_companies: {},
                job_status: '',
                job_error: '',
                company_group_id: '',
                companies_get: {},
                division_error: ''
            },
            mounted() {
                if (this.company_group_id) {
                    this.getCompaniesToCompanyGroup()
                }
            },
            methods: {
                getCompaniesToCompanyGroup() {
                    $.ajax({
                        type: 'GET',
                        data: {id: this.company_group_id},
                        url: '{{ route("api.company-group.companies") }}'
                    }).then(response => {
                        this.companies_get = response.data
                    })
                },
                getCompanies() {
                    $.ajax({
                        type: 'GET',
                        data: {department_id: this.department_id},
                        url: '{{ route("department.get-companies-store") }}'
                    }).then(response => {
                        this.department_companies = response.data
                    })
                },
                createDivision(e) {
                    e.preventDefault()
                    $.ajax({
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            division_name_ar: this.division_name_ar,
                            division_name_en: this.division_name_en,
                            division_code: this.division_code,
                            department_id: this.department_id,
                            company_id: this.company_id,
                        },
                        url: '{{ route("division.store") }}'
                    }).then(response => {
                        console.log(response)
                        if (response.status == 500) {
                            this.division_error = response.data
                        } else {
                            window.location.href = response;
                        }
                    })
                },
                getDivisionCompanies() {
                    $.ajax({
                        type: 'GET',
                        data: {division_id: this.division_id},
                        url: '{{ route("divisions.get-companies-store") }}'
                    }).then(response => {
                        this.division_companies = response.data
                    })
                },
                createJob(e) {
                    e.preventDefault()
                    this.job_error = ''
                    $.ajax({
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            job_name_ar: this.job_name_ar,
                            job_name_en: this.job_name_en,
                            job_code: this.job_code,
                            division_id: this.division_id,
                            division_company_id: this.division_company_id,
                            job_status: this.job_status,
                        },
                        url: '{{ route("job.store") }}'
                    }).then(response => {
                        if (response.status == 500) {
                            this.job_error = response.message
                        } else {
                            console.log(response)
                          //  window.location.href = response;
                        }

                    })
                },
            },
            computed: {
                // a computed getter
                name_en_message: function () {
                    // `this` points to the vm instance
                    if (!this.division_name_en || this.division_name_en.length < 3) {
                        return true;
                    } else {
                        return false;
                    }
                },
                name_ar_message: function () {
                    // `this` points to the vm instance
                    if (!this.division_name_ar || this.division_name_ar.length < 3) {
                        return true;
                    } else {
                        return false;
                    }
                },
                code_message: function () {
                    // `this` points to the vm instance
                    if (!this.division_code || this.division_code.length < 3) {
                        return true;
                    } else {
                        return false;
                    }
                },
                department_message: function () {
                    // `this` points to the vm instance
                    if (!this.department_id) {
                        return true;
                    } else {
                        return false;
                    }
                },
                company_message: function () {
                    // `this` points to the vm instance
                    if (this.company_id.length == 0) {
                        return true;
                    } else {
                        return false;
                    }
                },
                disabled_division: function () {
                    if (this.company_message || this.department_message || this.code_message || this.name_ar_message || this.name_en_message) {
                        return 1;
                    } else {
                        return 0;
                    }
                },
                job_name_en_message: function () {
                    // `this` points to the vm instance
                    if (!this.job_name_en || this.job_name_en.length < 3) {
                        return true;
                    } else {
                        return false;
                    }
                },
                job_name_ar_message: function () {
                    // `this` points to the vm instance
                    if (!this.job_name_ar || this.job_name_ar.length < 3) {
                        return true;
                    } else {
                        return false;
                    }
                },
                job_code_message: function () {
                    // `this` points to the vm instance
                    if (!this.job_code || this.job_code.length < 3) {
                        return true;
                    } else {
                        return false;
                    }
                },
                division_message: function () {
                    if (!this.division_id) {
                        return true;
                    } else {
                        return false;
                    }
                },
                division_company_message: function () {
                    // `this` points to the vm instance
                    if (this.division_company_id.length == 0) {
                        return true;
                    } else {
                        return false;
                    }
                },
                disabled_job: function () {
                    if (this.job_name_en_message || this.job_name_ar_message || this.job_code_message || this.division_message || this.division_company_message) {
                        return 1;
                    } else {
                        return 0;
                    }
                },
            }
        })
    </script>
@endsection
@section('style')
    <style>

        .treeview-animated {
            font-size: 16px;
            font-weight: 400;
        }

        .treeview-animated.w-20 {
            width: 20rem;
        }

        .treeview-animated h6 {
            font-size: 1.4em;
            font-weight: 500;
        }

        .treeview-animated ul {
            position: relative;
            list-style: none;
            padding-left: 0;
        }

        .treeview-animated-list ul {
            padding-left: 1em;
            margin-top: 0.1em;
        }

        .treeview-animated-element {
            padding: 0.2em 0.2em 0.2em 1em;
            cursor: pointer;
            transition: all .1s linear;
            border: 2px solid transparent;
            border-right: 0px solid transparent;
        }

        .treeview-animated-element.opened {
            border-right: 0px solid transparent;
        }

        .treeview-animated-items-header {
            display: block;
            padding: 0.4em;
            margin-right: 0;
            border-bottom: 2px solid transparent;
        }

        .treeview-animated-items-header.open {
            transition: all .1s linear;
        }

        .treeview-animated-items-header .fa-angle-right {
            transition: all .1s linear;
            font-size: .8rem;
        }

        .treeview-animated-items-header .fas {
            position: relative;
            transition: all .2s linear;
            transform: rotate(90deg);
        }

        .treeview-animated-items-header .fa-minus-circle {
            position: relative;
            transform: rotate(180deg);
        }
    </style>
@endsection

@extends('Layouts.master')

@section('content')

    <div id="app">
        <div class="section-body">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item">
                            <a href="#data-grid" data-toggle="tab"
                               class="nav-link @if(!request()->qr == 'applications' || !request()->qr == 'branches' || request()->qr == 'data') active @endif">@lang('home.data')</a>
                        </li>

                        <li class="nav-item">
                            <a href="#applications-grid" data-toggle="tab"
                               class="nav-link @if(request()->qr == 'applications') active @endif">@lang('home.applications')</a>
                        </li>

                        <li class="nav-item">
                            <a href="#branches-grid" data-toggle="tab"
                               class="nav-link @if(request()->qr == 'branches') active @endif">@lang('home.branches')</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#">@lang('home.employees')</a></li>

                        <li class="nav-item"><a class="nav-link" href="#files-grid"
                                                data-toggle="tab">@lang('home.files')</a></li>
                        <li class="nav-item"><a class="nav-link" href="#notes-grid"
                                                data-toggle="tab">@lang('home.notes')</a></li>

                    </ul>
                    <div class="header-action"></div>
                </div>
            </div>
        </div>


        <div class="section-body mt-3">
            <div class="container-fluid">

                <div class="tab-content mt-3">
                    {{-- dATA --}}
                    <div class="tab-pane fade @if(!request()->qr == 'applications' || request()->qr == 'data') active show @endif"
                         id="data-grid" role="tabpanel">
                        @include('Includes.form-errors')
                        <form class="card" action="{{ route('company.update',$company->company_id) }}"
                              enctype="multipart/form-data" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <img src="{{ $company->company_logo }}" width="100" height="100">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" id="dropify-event"
                                               name="company_logo">
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.name_ar')</label>
                                            <input type="text" class="form-control"
                                                   value="{{ $company->company_name_ar }}"
                                                   name="company_name_ar"
                                                   oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                                   id="company_name_ar">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.name_en')</label>
                                            <input type="text" class="form-control"
                                                   value="{{ $company->company_name_en }}"
                                                   name="company_name_en"
                                                   oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                                   id="company_name_en">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.email_address')</label>
                                            <input type="email" name="co_email" class="form-control"
                                                   value="{{ $company->co_email }}" id="co_email">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.responsible_person')</label>
                                            <input type="text" class="form-control" name="co_responsible_person"
                                                   value="{{ $company->co_responsible_person }}"
                                                   id="co_responsible_person">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.mobile_number')</label>
                                            <input type="number" name="co_mobile_number" class="form-control"
                                                   value="{{ $company->co_mobile_number }}" id="co_mobile_number">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.phone_number')</label>
                                            <input type="number" class="form-control" name="co_phone_no"
                                                   value="{{ $company->co_phone_no }}" id="co_phone_no">
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.tax_number')</label>
                                            <input type="number" class="form-control" name="company_tax_no"
                                                   value="{{ $company->company_tax_no }}" id="company_tax_no">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.address')</label>
                                            <input type="text" class="form-control" name="co_address"
                                                   value="{{ $company->co_address }}" id="co_address">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.postal_code')</label>
                                            <input type="text" class="form-control" id="company_postal_code"
                                                   value="{{ $company->company_postal_code }}"
                                                   name="company_postal_code">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.postal_box')</label>
                                            <input type="text" class="form-control" name="company_postal_box"
                                                   value="{{ $company->company_postal_box }}" id="company_postal_box">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.commercial_register')</label>
                                            <input type="text" class="form-control" id="company_register"
                                                   value="{{ $company->company_register }}" name="company_register">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.branches_number')</label>
                                            <input type="text" class="form-control" name="co_branches_no"
                                                   id="co_branches_no"
                                                   value="{{ $company->co_branches_no }}">
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.open_date')</label>
                                            <input type="date" class="form-control"
                                                   value="{{ $company->co_open_date }}" name="co_open_date">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.employees')</label>
                                            <input type="text" class="form-control" name="co_emp_no" id="co_emp_no"
                                                   value="{{ $company->co_emp_no }}">
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.end_date')</label>
                                            <input type="date" class="form-control" name="co_end_date"
                                                   value="{{ $company->co_end_date }}">
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.status')</label>
                                            <select class="form-control" name="co_is_active">
                                                <option value="1">on</option>
                                                <option value="0">off</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" id="submit" class="btn btn-primary">@lang('home.edit')</button>
                            </div>

                        </form>
                    </div>

                    {{-- branches part --}}
                    <div class="tab-pane fade @if(request()->qr == 'branches') active show @endif" id="branches-grid"
                         role="tabpanel">

                        <div class="header-action">

                            {{--pop up to create--}}

                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#createModal">
                                <i class="fe fe-plus mr-2"></i>@lang('home.add_branch')
                            </button>

                            <div class="modal fade bd-example-modal-lg"
                                 id="createModal" tabindex="-1"
                                 aria-labelledby="createModalLabel" aria-hidden="true">
                                <div class="modal-dialog" style="min-width: 50%">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"
                                                id="createModalLabel">@lang('home.add_branch') @lang('home.to') <span
                                                        style="text-decoration:underline"> @if(app()->getLocale()=='en') {{ $company->company_name_en }} @else {{ $company->company_name_ar }} @endif </span>
                                            </h5>

                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>

                                        </div>
                                        <div class="modal-body">
                                            <form id="submit_branch_form" class="card"
                                                  action="{{route('company-branches.store')}}"
                                                  method="post">
                                                @csrf
                                                <input type="hidden" name="company_id"
                                                       value="{{ $company->company_id }}">
                                                <div class="card-body">
                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">@lang('home.name_ar')</label>
                                                                <input type="text" class="form-control is-invalid"
                                                                       placeholder="@lang('home.branch_name_ar')"
                                                                       name="branch_name_ar"
                                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,'');"
                                                                       id="branch_name_ar">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">@lang('home.name_en')</label>
                                                                <input type="text" class="form-control is-invalid"

                                                                       placeholder="@lang('home.branch_name_en')"
                                                                       name="branch_name_en"
                                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'');"
                                                                       id="branch_name_en">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">@lang('home.branch_address')</label>
                                                                <input type="text" name="branch_address"
                                                                       placeholder="@lang('home.branch_address')"
                                                                       class="form-control is-invalid"
                                                                       id="branch_address">

                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">@lang('home.branch_phone')</label>
                                                                <input type="number" class="form-control is-invalid"
                                                                       placeholder="@lang('home.branch_phone')"
                                                                       name="branch_phone" id="branch_phone">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">@lang('home.open_date')</label>
                                                                <input type="date" class="form-control is-invalid"
                                                                       name="branch_start_date" id="branch_start_date">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">@lang('home.end_date')</label>
                                                                <input type="date" class="form-control is-invalid"
                                                                       name="branch_end_date" id="branch_end_date">
                                                            </div>
                                                        </div>


                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">@lang('home.code')</label>
                                                                <input type="number" class="form-control is-invalid"
                                                                       placeholder="@lang('home.code')"
                                                                       name="branch_code" id="branch_code">
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">@lang('home.close')</button>
                                                    <button type="submit" id="submit_create_branch"
                                                            class="btn btn-primary ml-2">@lang('home.add_branch')</button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card">
                            <div class="card-header">
                                <div class="card-options">
                                </div>
                            </div>
                            <div class="card-body">
                                <p
                                        style="text-decoration:underline;font-size:25px"> @if(app()->getLocale()=='en') {{ $company->company_name_en }} @else {{ $company->company_name_ar }} @endif </p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-center table-hover mb-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('home.branch_name')</th>
                                            <th>@lang('home.branch_address')</th>
                                            <th>@lang('home.branch_phone')</th>
                                            <th>@lang('home.start_date')</th>
                                            <th>@lang('home.end_date')</th>
                                            <th>@lang('home.code')</th>
                                            <th>@lang('home.branch_location')</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($branches as $k=>$branch)
                                            <tr>
                                                <td>{{ $k+1 }}</td>
                                                <td>
                                                    <div class="font-15">
                                                        @if(app()->getLocale() == 'ar')
                                                            {{ $branch->branch_name_ar }}
                                                        @else
                                                            {{ $branch->branch_name_en }}
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>{{ $branch->branch_address }}</td>
                                                <td>{{ $branch->branch_phone }}</td>
                                                <td>{{ $branch->branch_start_date }}</td>
                                                <td>{{ $branch->branch_end_date }}</td>
                                                <td>{{ $branch->branch_code }}</td>
                                                <td class="text-center"><a target="_blank"
                                                                           href="{{ route('company-branch-location',$branch->branch_id) }}">
                                                        <i
                                                                class="fa fa-map" style="color:#007bff"></i></a></td>
                                                <td>
                                                    <button type="button" class="btn btn-icon"
                                                            title="@lang('home.edit')"
                                                            data-toggle="modal"
                                                            data-target="#exampleModal{{ $branch->branch_id }}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            {{--pop up to update--}}

                                            <div class="modal fade bd-example-modal-lg"
                                                 id="exampleModal{{ $branch->branch_id }}" tabindex="-1"
                                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" style="min-width: 50%">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="exampleModalLabel">@lang('home.update_branch') <span
                                                                        style="text-decoration:underline"> @if(app()->getLocale()=='en') {{ $company->company_name_en }} @else {{ $company->company_name_ar }} @endif </span>
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form class="card"
                                                                  action="{{ route('company-branch.update',$branch->branch_id) }}"
                                                                  method="post">
                                                                @csrf
                                                                @method('put')
                                                                <input type="hidden" name="company_id"
                                                                       value="{{ $company->company_id }}">
                                                                <div class="card-body">
                                                                    <div class="row">

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.name_ar')</label>
                                                                                <input type="text"
                                                                                       class="form-control branch_name_ar"
                                                                                       placeholder="@lang('home.branch_name_ar')"
                                                                                       name="branch_name_ar"
                                                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,'');"
                                                                                       required
                                                                                       value="{{ $branch->branch_name_ar }}">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.name_en')</label>
                                                                                <input type="text"
                                                                                       class="form-control branch_name_en"
                                                                                       name="branch_name_en"
                                                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'');"
                                                                                       required
                                                                                       value="{{ $branch->branch_name_en }}">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.branch_address')</label>
                                                                                <input type="text" name="branch_address"
                                                                                       class="form-control branch_address"
                                                                                       required
                                                                                       value="{{ $branch->branch_address }}">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.branch_phone')</label>
                                                                                <input type="number"
                                                                                       class="form-control branch_phone"
                                                                                       name="branch_phone" required
                                                                                       value="{{ $branch->branch_phone }}">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.open_date')</label>
                                                                                <input type="date" class="form-control"
                                                                                       value="{{ $branch->branch_start_date }}"
                                                                                       name="branch_start_date"
                                                                                       required>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.code')</label>
                                                                                <input type="number"
                                                                                       class="form-control branch_code"
                                                                                       value="{{ $branch->branch_code }}"
                                                                                       name="branch_code" required>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.end_date')</label>
                                                                                <input type="date" class="form-control"
                                                                                       name="branch_end_date" required
                                                                                       value="{{ $branch->branch_end_date }}">
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">@lang('home.close')</button>
                                                                    <button type="submit"
                                                                            class="btn btn-primary ml-2">@lang('home.update_branch')</button>
                                                                </div>
                                                            </form>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- applications part --}}
                    <div class="tab-pane fade @if(request()->qr == 'applications') show active @endif"
                         id="applications-grid" role="tabpanel">

                        <div class="header-action">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#exampleModal">
                                <i class="fe fe-plus mr-2"></i>@lang('home.add_application')</button>
                        </div>
                        {{--pop up to create--}}
                        <div class="modal fade" id="exampleModal" tabindex="-1" data-toggle="modal"
                             data-target="#exampleModal"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"
                                            id="exampleModalLabel">@lang('home.add_application') @lang('home.to') <span
                                                    style="text-decoration:underline"> @if(app()->getLocale()=='en') {{ $company->company_name_en }} @else {{ $company->company_name_ar }} @endif </span>
                                        </h5>
                                        <a href="#" class="card-options-remove" data-bs-dismiss="modal"
                                           aria-label="Close">
                                            <i class="fe fe-x" style="color:#004660"></i></a>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('company-app.store') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="company_id" value="{{ $company->company_id }}">
                                            <div class="mb-3">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('home.applications') </label>
                                                        <select class="form-control is-invalid" name="app_id" required
                                                                id="app_id">
                                                            <option value="">@lang('home.choose_application')</option>
                                                            @foreach($applications as $application)
                                                                <option value="{{ $application->app_id }}">
                                                                    @if(app()->getLocale()=='ar')
                                                                        {{ $application->app_name_ar }}
                                                                    @else
                                                                        {{ $application->app_name_en }}
                                                                    @endif
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('home.status') </label>
                                                        <select class="form-control" name="co_app_is_active" required>
                                                            <option selected value="1">on</option>
                                                            <option value="0">off</option>
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-secondary mr-2"
                                                        data-bs-dismiss="modal">@lang('home.save')</button>
                                                <button type="button"
                                                        class="btn btn-primary"> @lang('home.close')</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('Includes.form-errors')

                        <div class="card">
                            <div class="card-header">
                                <div class="card-options">
                                </div>
                            </div>
                            <div class="card-body">
                                <p
                                        style="text-decoration:underline;font-size:25px"> @if(app()->getLocale()=='en') {{ $company->company_name_en }} @else {{ $company->company_name_ar }} @endif </p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-vcenter table-hover mb-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('home.application_name')</th>
                                            <th>@lang('home.icon')</th>
                                            <th>@lang('home.application_code')</th>
                                            <th>@lang('home.status')</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($company->apps as $k=>$application)
                                            <tr>
                                                <td>{{ $k+1 }}</td>
                                                <td>
                                                    <div class="font-15">
                                                        @if(app()->getLocale() == 'ar')
                                                            {{ $application->app_name_ar }}
                                                        @else
                                                            {{ $application->app_name_en }}
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>{{ $application->app_icon }}</td>
                                                <td>{{ $application->app_code }}</td>
                                                <td>
                                                    @php $company_app=\App\Models\CompanyApp::where('company_id',$company->company_id)->where('app_id',$application->app_id)->first() @endphp
                                                    @if($company_app->co_app_is_active)<i class="fa fa-check"></i> @else
                                                        <i class="fa fa-remove"></i>  @endif</td>
                                                <td>
                                                    <form action="{{ route('company-app.delete',$company_app->company_app_id) }}"
                                                          method="post">
                                                        @method('delete')
                                                        @csrf
                                                        <input type="hidden" value="{{ $company->company_id }}"
                                                               name="company_id">
                                                        <button type="submit" class="btn btn-icon js-sweetalert"
                                                                title="@lang('home.delete')">
                                                            <i class="fa fa-trash text-danger"></i>
                                                        </button>
                                                    </form>

                                                    @php $company_app = \App\Models\CompanyApp::where('company_id',$company->company_id)->where('app_id',$application->app_id)->first() @endphp
                                                    <button type="button" class="btn btn-icon js-sweetalert"
                                                            data-toggle="modal"
                                                            data-target="#exampleModalEdit{{ $company_app->company_app_id }}"
                                                            data-whatever="@mdo">
                                                        <i class="fa fa-edit"></i>
                                                    </button>

                                                    <div class="modal fade"
                                                         id="exampleModalEdit{{ $company_app->company_app_id }}"
                                                         tabindex="-1"
                                                         role="dialog" aria-labelledby="exampleModalLabel"
                                                         aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="exampleModalLabel">@lang('home.edit_application') @lang('home.to')
                                                                        <p
                                                                                style="text-decoration:underline;font-size:25px"> @if(app()->getLocale()=='en') {{ $company->company_name_en }} @else {{ $company->company_name_ar }} @endif </p>
                                                                    </h5>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">

                                                                    <form action="{{ route('company-app.update',$company_app->company_app_id) }}"
                                                                          method="post">
                                                                        @method('put')
                                                                        @csrf
                                                                        <div class="form-group">
                                                                            <label for="recipient-name"
                                                                                   class="col-form-label">@lang('home.status')
                                                                            </label>
                                                                            <select class="form-control"
                                                                                    name="co_app_is_active">
                                                                                <option value="1"
                                                                                        @if($company_app->co_app_is_active == 1) selected @endif>
                                                                                    on
                                                                                </option>
                                                                                <option value="0"
                                                                                        @if($company_app->co_app_is_active == 0) selected @endif>
                                                                                    off
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-dismiss="modal">@lang('home.close')
                                                                            </button>
                                                                            <button type="submit"
                                                                                    class="btn btn-primary">
                                                                                @lang('home.save')
                                                                            </button>
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

                    {{-- files part --}}
                    <div class="tab-pane fade" id="files-grid" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-12">

                                <x-files.form>
                                    <input type="hidden" name="transaction_id" value="{{ $company->company_id }}">
                                    <input type="hidden" name="app_menu_id" value="2">
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
                                                   target="_blank" class="mr-1 ml-1"><i class="fa fa-eye text-info"
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
                                    <input type="hidden" name="transaction_id" value="{{ $company->company_id }}">
                                    <input type="hidden" name="app_menu_id" value="2">
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

    </div>
@endsection

@section('scripts')

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

            $(".modal form input").click(function (event) {
                event.stopPropagation();
            });

            $(".modal form select").click(function (event) {
                event.stopPropagation();
            });
            //    validation to create modal
            $('#company_name_en').keyup(function () {
                if ($('#company_name_en').val().length < 3) {
                    $('#company_name_en').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#company_name_en').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });

            $('#company_name_ar').keyup(function () {
                if ($('#company_name_ar').val().length < 3) {
                    $('#company_name_ar').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#company_name_ar').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });

            $('#co_email').keyup(function () {
                if (!validEmail($('#co_email').val())) {
                    $('#co_email').addClass('is-invalid')
                    $('#submit').attr('disabled', 'disabled')
                } else {
                    $('#co_email').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });

            $('#co_responsible_person').keyup(function () {
                if ($('#co_responsible_person').val().length < 3) {
                    $('#co_responsible_person').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#co_responsible_person').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });

            $('#co_mobile_number').keyup(function () {
                if ($('#co_mobile_number').val().length < 11) {
                    $('#co_mobile_number').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#co_mobile_number').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });

            $('#co_phone_no').keyup(function () {
                if ($('#co_phone_no').val().length < 11) {
                    $('#co_phone_no').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#co_phone_no').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });

            $('#company_tax_no').keyup(function () {
                if ($('#company_tax_no').val().length < 3) {
                    $('#company_tax_no').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#company_tax_no').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });

            $('#co_address').keyup(function () {
                if ($('#co_address').val().length < 3) {
                    $('#co_address').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#co_address').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });

            $('#company_postal_code').keyup(function () {
                if ($('#company_postal_code').val().length < 3) {
                    $('#company_postal_code').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#company_postal_code').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });

            $('#company_postal_box').keyup(function () {
                if ($('#company_postal_box').val().length < 3) {
                    $('#company_postal_box').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#company_postal_box').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });

            $('#company_register').keyup(function () {
                if ($('#company_register').val().length < 3) {
                    $('#company_register').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#company_register').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });

            $('#co_branches_no').keyup(function () {
                if ($('#co_branches_no').val() <= 0) {
                    $('#co_branches_no').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#co_branches_no').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });

            $('#co_emp_no').keyup(function () {
                if ($('#co_emp_no').val() <= 0) {
                    $('#co_emp_no').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#co_emp_no').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });

            function validEmail(email) {
                var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }

            //    validation to create modal
            $('#branch_name_en').keyup(function () {
                if ($('#branch_name_en').val().length < 3) {
                    $('#branch_name_en').addClass('is-invalid');
                    $('#submit_create_branch').attr('disabled', 'disabled');
                } else {
                    $('#branch_name_en').removeClass('is-invalid');
                    $('#submit_create_branch').removeAttr('disabled');
                }
            });

            $('#branch_name_ar').keyup(function () {
                if ($('#branch_name_ar').val().length < 3) {
                    $('#branch_name_ar').addClass('is-invalid');
                    $('#submit_create_branch').attr('disabled', 'disabled');
                } else {
                    $('#branch_name_ar').removeClass('is-invalid');
                    $('#submit_create_branch').removeAttr('disabled');
                }
            });

            $('#branch_address').keyup(function () {
                if ($('#branch_address').val().length < 3) {
                    $('#branch_address').addClass('is-invalid');
                    $('#submit_create_branch').attr('disabled', 'disabled');
                } else {
                    $('#branch_address').removeClass('is-invalid');
                    $('#submit_create_branch').removeAttr('disabled');
                }
            });

            $('#branch_phone').keyup(function () {
                if ($('#branch_phone').val().length < 11) {
                    $('#branch_phone').addClass('is-invalid');
                    $('#submit_create_branch').attr('disabled', 'disabled');
                } else {
                    $('#branch_phone').removeClass('is-invalid');
                    $('#submit_create_branch').removeAttr('disabled');
                }
            });

            $('#branch_code').keyup(function () {
                if ($('#branch_code').val().length < 3) {
                    $('#branch_code').addClass('is-invalid');
                    $('#submit_create_branch').attr('disabled', 'disabled');
                } else {
                    $('#branch_code').removeClass('is-invalid');
                    $('#submit_create_branch').removeAttr('disabled');
                }
            });

            $('#branch_start_date').change(function () {
                $('#branch_start_date').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');

            });

            $('#branch_end_date').change(function () {
                $('#branch_end_date').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            });

            $('.branch_name_en').each(function () {
                $(this).keyup(function () {
                    if ($(this).val().length < 3) {
                        $(this).addClass('is-invalid')
                    } else {
                        $(this).removeClass('is-invalid')
                    }
                });
            })

            $('.branch_name_ar').each(function () {
                $(this).keyup(function () {
                    if ($(this).val().length < 3) {
                        $(this).addClass('is-invalid')
                    } else {
                        $(this).removeClass('is-invalid')
                    }
                });
            })

            $('.branch_phone').each(function () {
                $(this).keyup(function () {
                    if ($(this).val().length < 11) {
                        $(this).addClass('is-invalid')
                    } else {
                        $(this).removeClass('is-invalid')
                    }
                });
            })

            $('.branch_address').each(function () {
                $(this).keyup(function () {
                    if ($(this).val().length < 3) {
                        $(this).addClass('is-invalid')
                    } else {
                        $(this).removeClass('is-invalid')
                    }
                });
            })

            $('.branch_code').each(function () {
                $(this).keyup(function () {
                    if ($(this).val().length < 3) {
                        $(this).addClass('is-invalid')
                    } else {
                        $(this).removeClass('is-invalid')
                    }
                });
            })

            $('#app_id').change(function () {
                if (!$('#app_id').val()) {
                    $('#app_id').addClass('is-invalid')
                } else {
                    $('#app_id').removeClass('is-invalid')
                }
            })

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
        });
    </script>
@endsection


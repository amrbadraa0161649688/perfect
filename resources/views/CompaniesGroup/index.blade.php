@extends('Layouts.master')
@section('content')
    <div class="section-body mt-3">
        <div class="container-fluid">

            <div class="header-action">
                @if(auth()->user()->user_type_id == 1)
                    <a type="button" class="btn btn-primary" href="{{ route('mainCompanies.create') }}"><i
                                class="fe fe-plus mr-2"></i>@lang('home.add_company_group')</a>
                @endif
            </div>

            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-options">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-vcenter table-hover mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('home.company_logo')</th>
                                        <th>@lang('home.company_name')</th>
                                        <th>@lang('home.responsible_person')</th>
                                        <th>@lang('home.mobile_number')</th>
                                        <th>@lang('home.expiration_contract_date')</th>
                                        <th>@lang('home.companies_number')</th>
                                        <th>@lang('home.status')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(auth()->user()->user_type_id != 1)
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <div class="avatar avatar-blue" data-toggle="tooltip"
                                                     data-placement="top"
                                                     title="" data-original-title="Avatar Name">
                                                    <img class="avatar avatar-blue"
                                                         src="{{auth()->user()->companyGroup->company_group_logo}}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-15">
                                                    @if(app()->getLocale() == 'ar')
                                                        {{ auth()->user()->companyGroup->company_group_ar }}
                                                    @else
                                                        {{ auth()->user()->companyGroup->company_group_en }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ auth()->user()->companyGroup->responsible_person }}</td>
                                            <td>{{ auth()->user()->companyGroup->mobile_number }}</td>
                                            <td>{{ auth()->user()->companyGroup->end_date }}</td>
                                            <td>{{ auth()->user()->companyGroup->companys_number }}</td>
                                            <td>@if(auth()->user()->companyGroup->c_group_is_active)<i
                                                        class="fa fa-check"></i> @else
                                                    <i
                                                            class="fa fa-remove"></i>  @endif</td>
                                            <td>
                                                @foreach(session('job')->permissions as $job_permission)
                                                    @if($job_permission->app_menu_id == 2 && $job_permission->permission_update)
                                                        <button type="button" class="btn btn-icon"
                                                                title="@lang('home.edit')"
                                                                data-toggle="modal"
                                                                data-target="#exampleModal{{ auth()->user()->companyGroup->company_group_id }}">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    @endif
                                                @endforeach
                                                <button type="button" class="btn btn-icon js-sweetalert"
                                                        id="application{{ auth()->user()->companyGroup->company_group_id }}"
                                                        title="@lang('home.show')" data-type="confirm"
                                                        onclick="show(this)">
                                                    <i class="fa fa-eye text-danger"></i>
                                                </button>

                                                {{-- update company group modal --}}
                                                <div class="modal fade bd-example-modal-lg"
                                                     id="exampleModal{{ auth()->user()->companyGroup->company_group_id  }}"
                                                     tabindex="-1"
                                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" style="min-width: 50%">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="exampleModalLabel">@lang('home.update_company')</h5>
                                                                <button type="button" class="close"
                                                                        data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form class="card"
                                                                      action="{{ route('mainCompanies.update',auth()->user()->companyGroup->company_group_id) }}"
                                                                      method="post" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <input type="file" id="dropify-event"
                                                                                   name="company_group_logo">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <img src="{{ auth()->user()->companyGroup->company_group_logo }}"
                                                                                 width="100" height="100">
                                                                        </div>
                                                                    </div>

                                                                    <div class="card-body">
                                                                        <div class="row">

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">@lang('home.name_ar')</label>
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           placeholder="@lang('home.company_name_ar')"
                                                                                           name="company_group_ar"
                                                                                           oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                                                                           required
                                                                                           id="company_group_ar"
                                                                                           value="{{ auth()->user()->companyGroup->company_group_ar }}">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">@lang('home.name_en')</label>
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="company_group_en"
                                                                                           oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                                                                           required
                                                                                           id="company_group_en"
                                                                                           value="{{ auth()->user()->companyGroup->company_group_en }}">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">@lang('home.email_address')</label>
                                                                                    <input type="email"
                                                                                           name="main_email"
                                                                                           class="form-control"
                                                                                           required
                                                                                           id="main_email"
                                                                                           value="{{ auth()->user()->companyGroup->main_email }}">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">@lang('home.responsible_person')</label>
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           id="responsible_person"
                                                                                           name="responsible_person"
                                                                                           required
                                                                                           value="{{ auth()->user()->companyGroup->responsible_person }}">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-sm-6 col-md-4">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">@lang('home.mobile_number')</label>
                                                                                    <input type="number"
                                                                                           name="mobile_number"
                                                                                           class="form-control"
                                                                                           required
                                                                                           id="mobile_number"
                                                                                           value="{{ auth()->user()->companyGroup->mobile_number }}">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-sm-6 col-md-3">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">@lang('home.phone_number')</label>
                                                                                    <input type="number"
                                                                                           class="form-control"
                                                                                           name="phone_no" required
                                                                                           id="phone_no"
                                                                                           value="{{ auth()->user()->companyGroup->phone_no }}">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-5">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">@lang('home.tax_number')</label>
                                                                                    <input type="number"
                                                                                           class="form-control"
                                                                                           name="tax_number"
                                                                                           required
                                                                                           id="tax_number"
                                                                                           value="{{ auth()->user()->companyGroup->tax_number }}">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">@lang('home.address')</label>
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="main_address"
                                                                                           required
                                                                                           id="main_address"
                                                                                           value="{{ auth()->user()->companyGroup->main_address }}">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">@lang('home.postal_code')</label>
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           placeholder="1234"
                                                                                           required
                                                                                           id="postal_code"
                                                                                           value="{{ auth()->user()->companyGroup->postal_code }}"
                                                                                           name="postal_code">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">@lang('home.postal_box')</label>
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="postal_box"
                                                                                           required
                                                                                           id="postal_box"
                                                                                           value="{{ auth()->user()->companyGroup->postal_box }}">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">@lang('home.commercial_register')</label>
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           required
                                                                                           id="commercial_register"
                                                                                           value="{{ auth()->user()->companyGroup->commercial_register }}"
                                                                                           name="commercial_register">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">@lang('home.companies_number')</label>
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="companys_number"
                                                                                           required
                                                                                           min="1"
                                                                                           id="companys_number"
                                                                                           value="{{ auth()->user()->companyGroup->companys_number }}">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">@lang('home.open_date')</label>
                                                                                    <input type="date"
                                                                                           class="form-control"
                                                                                           required id="open_date"
                                                                                           value="{{ auth()->user()->companyGroup->open_date }}"
                                                                                           name="open_date"
                                                                                           required>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">@lang('home.end_date')</label>
                                                                                    <input type="date"
                                                                                           class="form-control"
                                                                                           name="end_date" required
                                                                                           id="end_date"
                                                                                           value="{{ auth()->user()->companyGroup->end_date }}">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">@lang('home.status')</label>
                                                                                    <select class="form-control"
                                                                                            required
                                                                                            name="c_group_is_active">
                                                                                        <option value="1"
                                                                                                @if(auth()->user()->companyGroup->c_group_is_active) selected @endif>
                                                                                            on
                                                                                        </option>
                                                                                        <option value="0"
                                                                                                @if(!auth()->user()->companyGroup->c_group_is_active) selected @endif>
                                                                                            off
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-dismiss="modal">@lang('home.close')
                                                                        </button>

                                                                        <button type="submit"
                                                                                id="submit_main_company"
                                                                                class="btn btn-primary">@lang('home.update_company')</button>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    @elseif(auth()->user()->user_type_id == 1)
                                        {{-- if auth user id perfect --}}
                                        @foreach($companies_group as $k=>$company_group)
                                            <tr>
                                                <td>{{ $k+1 }}</td>
                                                <td>
                                                    <div class="avatar avatar-blue" data-toggle="tooltip"
                                                         data-placement="top"
                                                         title="" data-original-title="Avatar Name">
                                                        <img class="avatar avatar-blue"
                                                             src="{{$company_group->company_group_logo}}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="font-15">
                                                        @if(app()->getLocale() == 'ar')
                                                            {{ $company_group->company_group_ar }}
                                                        @else
                                                            {{ $company_group->company_group_en }}
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>{{ $company_group->responsible_person }}</td>
                                                <td>{{ $company_group->mobile_number }}</td>
                                                <td>{{ $company_group->end_date }}</td>
                                                <td>{{ $company_group->companys_number }}</td>
                                                <td>@if($company_group->c_group_is_active)<i
                                                            class="fa fa-check"></i> @else
                                                        <i
                                                                class="fa fa-remove"></i>  @endif</td>
                                                <td>
                                                    <button type="button" class="btn btn-icon"
                                                            title="@lang('home.edit')"
                                                            data-toggle="modal"
                                                            data-target="#exampleModal{{ $company_group->company_group_id }}">
                                                        <i
                                                                class="fa fa-edit"></i></button>
                                                    <button type="button" class="btn btn-icon js-sweetalert"
                                                            id="application{{ $company_group->company_group_id }}"
                                                            title="@lang('home.show')" data-type="confirm"
                                                            onclick="show(this)">
                                                        <i class="fa fa-eye text-danger"></i>
                                                    </button>

                                                    {{-- update company group modal --}}
                                                    <div class="modal fade bd-example-modal-lg"
                                                         id="exampleModal{{ $company_group->company_group_id  }}"
                                                         tabindex="-1"
                                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" style="min-width: 50%">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="exampleModalLabel">@lang('home.update_company')</h5>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="card"
                                                                          action="{{ route('mainCompanies.update',$company_group->company_group_id) }}"
                                                                          method="post"
                                                                          enctype="multipart/form-data">
                                                                        @csrf
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <input type="file"
                                                                                       id="dropify-event"
                                                                                       name="company_group_logo">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <img src="{{ $company_group->company_group_logo }}"
                                                                                     width="100" height="100">
                                                                            </div>
                                                                        </div>

                                                                        <div class="card-body">
                                                                            <div class="row">

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">@lang('home.name_ar')</label>
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               placeholder="@lang('home.company_name_ar')"
                                                                                               name="company_group_ar"
                                                                                               oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                                                                               required
                                                                                               id="company_group_ar"
                                                                                               value="{{ $company_group->company_group_ar }}">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">@lang('home.name_en')</label>
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               name="company_group_en"
                                                                                               oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                                                                               required
                                                                                               id="company_group_en"
                                                                                               value="{{ $company_group->company_group_en }}">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">@lang('home.email_address')</label>
                                                                                        <input type="email"
                                                                                               name="main_email"
                                                                                               class="form-control"
                                                                                               required
                                                                                               id="main_email"
                                                                                               value="{{ $company_group->main_email }}">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">@lang('home.responsible_person')</label>
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               id="responsible_person"
                                                                                               name="responsible_person"
                                                                                               required
                                                                                               value="{{ $company_group->responsible_person }}">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-6 col-md-4">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">@lang('home.mobile_number')</label>
                                                                                        <input type="number"
                                                                                               name="mobile_number"
                                                                                               class="form-control"
                                                                                               required
                                                                                               id="mobile_number"
                                                                                               value="{{ $company_group->mobile_number }}">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-6 col-md-3">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">@lang('home.phone_number')</label>
                                                                                        <input type="number"
                                                                                               class="form-control"
                                                                                               name="phone_no"
                                                                                               required
                                                                                               id="phone_no"
                                                                                               value="{{ $company_group->phone_no }}">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-5">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">@lang('home.tax_number')</label>
                                                                                        <input type="number"
                                                                                               class="form-control"
                                                                                               name="tax_number"
                                                                                               required
                                                                                               id="tax_number"
                                                                                               value="{{ $company_group->tax_number }}">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">@lang('home.address')</label>
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               name="main_address"
                                                                                               required
                                                                                               id="main_address"
                                                                                               value="{{ $company_group->main_address }}">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">@lang('home.postal_code')</label>
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               placeholder="1234"
                                                                                               required
                                                                                               id="postal_code"
                                                                                               value="{{ $company_group->postal_code }}"
                                                                                               name="postal_code">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">@lang('home.postal_box')</label>
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               name="postal_box"
                                                                                               required
                                                                                               id="postal_box"
                                                                                               value="{{ $company_group->postal_box }}">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">@lang('home.commercial_register')</label>
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               required
                                                                                               id="commercial_register"
                                                                                               value="{{ $company_group->commercial_register }}"
                                                                                               name="commercial_register">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">@lang('home.companies_number')</label>
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               name="companys_number"
                                                                                               required
                                                                                               min="1"
                                                                                               id="companys_number"
                                                                                               value="{{ $company_group->companys_number }}">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">@lang('home.open_date')</label>
                                                                                        <input type="date"
                                                                                               class="form-control"
                                                                                               required
                                                                                               id="open_date"
                                                                                               value="{{ $company_group->open_date }}"
                                                                                               name="open_date"
                                                                                               required>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">@lang('home.end_date')</label>
                                                                                        <input type="date"
                                                                                               class="form-control"
                                                                                               name="end_date"
                                                                                               required
                                                                                               id="end_date"
                                                                                               value="{{ $company_group->end_date }}">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">@lang('home.status')</label>
                                                                                        <select class="form-control"
                                                                                                required
                                                                                                name="c_group_is_active">
                                                                                            <option value="1"
                                                                                                    @if($company_group->c_group_is_active) selected @endif>
                                                                                                on
                                                                                            </option>
                                                                                            <option value="0"
                                                                                                    @if(!$company_group->c_group_is_active) selected @endif>
                                                                                                off
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-dismiss="modal">@lang('home.close')
                                                                            </button>

                                                                            <button type="submit"
                                                                                    id="submit_main_company"
                                                                                    class="btn btn-primary">@lang('home.update_company')</button>
                                                                        </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

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


        <div>
            @if(auth()->user()->user_type_id != 1)
                <div class="section-body section-sub-application mt-3"
                     id="app-application{{ auth()->user()->companyGroup->company_group_id }}"
                     style="display:none">
                    <div class="container-fluid">

                        <div class="tab-content mt-3">
                            <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-options">
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="header-action">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    @foreach(session('job')->permissions as $job_permission)
                                                        @if($job_permission->app_menu_id == 2 && $job_permission->permission_add)
                                                            <a type="button" class="btn btn-primary"
                                                               href="{{ route('company.create',auth()->user()->companyGroup->company_group_id) }}"><i
                                                                        class="fe fe-plus mr-2"></i>@lang('home.add_sub_company')
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <div class="col-md-6">
                                                    <p style="font-size: 25px;text-decoration: underline">
                                                        @if(app()->getLocale()=='ar') {{ auth()->user()->companyGroup->company_group_ar }} @else
                                                            {{ auth()->user()->companyGroup->company_group_en }} @endif</p>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-striped table-vcenter table-hover mb-0">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>@lang('home.company_logo')</th>
                                                    <th>@lang('home.company_name')</th>
                                                    <th>@lang('home.responsible_person')</th>
                                                    <th>@lang('home.expiry_date')</th>
                                                    <th>@lang('home.branches_number')</th>
                                                    <th>@lang('home.employees_number')</th>
                                                    <th>@lang('home.status')</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach(auth()->user()->companyGroup->companies as $k=>$company)
                                                    <tr>
                                                        <td>{{ $k+1 }}</td>
                                                        <td>
                                                            <div class="avatar avatar-blue" data-toggle="tooltip"
                                                                 data-placement="top"
                                                                 title="" data-original-title="Avatar Name">
                                                                <img class="avatar avatar-blue"
                                                                     src="{{$company->company_logo}}">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="font-15">
                                                                @if(app()->getLocale() == 'ar')
                                                                    {{ $company->company_name_ar }}
                                                                @else
                                                                    {{ $company->company_name_en }}
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>{{ $company->co_responsible_person }}</td>
                                                        <td>{{ $company->co_end_date }}</td>
                                                        <td>{{ $company->co_branches_no }}</td>
                                                        <td>{{ $company->co_emp_no }}</td>
                                                        <td>@if($company->co_is_active) <i
                                                                    class="fa fa-remove"></i> @else <i
                                                                    class="fa fa-check"></i> @endif</td>
                                                        <td>
                                                            @foreach(session('job')->permissions as $job_permission)
                                                                @if($job_permission->app_menu_id == 2 && $job_permission->permission_update)
                                                                    <a href="{{ route('company.edit',$company->company_id) }}"
                                                                       class="btn btn-icon js-sweetalert"
                                                                       title="@lang('home.show')"
                                                                       data-type="confirm">
                                                                        <i class="fa fa-eye text-danger"></i>
                                                                    </a>
                                                                @endif

                                                            @endforeach
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
            @elseif(auth()->user()->user_type_id == 1)
                @foreach($companies_group as $company_group)
                    <div class="section-body section-sub-application mt-3"
                         id="app-application{{ $company_group->company_group_id }}"
                         style="display:none">
                        <div class="container-fluid">

                            <div class="tab-content mt-3">
                                <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-options">
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="header-action">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <a type="button" class="btn btn-primary"
                                                           href="{{ route('company.create',$company_group->company_group_id) }}"><i
                                                                    class="fe fe-plus mr-2"></i>@lang('home.add_sub_company')
                                                        </a>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p style="font-size: 25px;text-decoration: underline">@if(app()->getLocale()=='ar') {{ $company_group->company_group_ar }} @else {{ $company_group->company_group_en }} @endif</p>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-striped table-vcenter table-hover mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>@lang('home.company_logo')</th>
                                                        <th>@lang('home.company_name')</th>
                                                        <th>@lang('home.responsible_person')</th>
                                                        <th>@lang('home.expiry_date')</th>
                                                        <th>@lang('home.branches_number')</th>
                                                        <th>@lang('home.employees_number')</th>
                                                        <th>@lang('home.status')</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($company_group->companies as $k=>$company)
                                                        <tr>
                                                            <td>{{ $k+1 }}</td>
                                                            <td>
                                                                <div class="avatar avatar-blue" data-toggle="tooltip"
                                                                     data-placement="top"
                                                                     title="" data-original-title="Avatar Name">
                                                                    <img class="avatar avatar-blue"
                                                                         src="{{$company->company_logo}}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="font-15">
                                                                    @if(app()->getLocale() == 'ar')
                                                                        {{ $company->company_name_ar }}
                                                                    @else
                                                                        {{ $company->company_name_en }}
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td>{{ $company->co_responsible_person }}</td>
                                                            <td>{{ $company->co_end_date }}</td>
                                                            <td>{{ $company->co_branches_no }}</td>
                                                            <td>{{ $company->co_emp_no }}</td>
                                                            <td>@if($company->co_is_active) <i
                                                                        class="fa fa-remove"></i> @else <i
                                                                        class="fa fa-check"></i> @endif</td>
                                                            <td>

                                                                <a href="{{ route('company.edit',$company->company_id) }}"
                                                                   class="btn btn-icon js-sweetalert"
                                                                   title="@lang('home.show')"
                                                                   data-type="confirm">
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
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function show(el) {
            var x = el.id;
            $("#app-" + x).css("display", "block");
            $("#app-" + x).siblings().css('display', 'none')
        }

        //    validation to create company group modal
        $('#company_group_en').keyup(function () {
            if ($('#company_group_en').val().length < 3) {
                $('#company_group_en').addClass('is-invalid');
                $('#submit_main_company').attr('disabled', 'disabled');
            } else {
                $('#company_group_en').removeClass('is-invalid');
                $('#submit_main_company').removeAttr('disabled');
            }
        });

        $('#company_group_ar').keyup(function () {
            if ($('#company_group_ar').val().length < 3) {
                $('#company_group_ar').addClass('is-invalid');
                $('#submit_main_company').attr('disabled', 'disabled');
            } else {
                $('#company_group_ar').removeClass('is-invalid');
                $('#submit_main_company').removeAttr('disabled');
            }
        });

        $('#main_email').keyup(function () {
            if (!validEmail($('#main_email').val())) {
                $('#main_email').addClass('is-invalid')
                $('#submit_main_company').attr('disabled', 'disabled')
            } else {
                $('#main_email').removeClass('is-invalid');
                $('#submit_main_company').removeAttr('disabled');
            }
        });

        $('#responsible_person').keyup(function () {
            if ($('#responsible_person').val().length < 3) {
                $('#responsible_person').addClass('is-invalid');
                $('#submit_main_company').attr('disabled', 'disabled');
            } else {
                $('#responsible_person').removeClass('is-invalid');
                $('#submit_main_company').removeAttr('disabled');
            }
        });

        $('#mobile_number').keyup(function () {
            if ($('#mobile_number').val().length < 11) {
                $('#mobile_number').addClass('is-invalid');
                $('#submit_main_company').attr('disabled', 'disabled');
            } else {
                $('#mobile_number').removeClass('is-invalid');
                $('#submit_main_company').removeAttr('disabled');
            }
        });

        $('#phone_no').keyup(function () {
            if ($('#phone_no').val().length < 11) {
                $('#phone_no').addClass('is-invalid');
                $('#submit_main_company').attr('disabled', 'disabled');
            } else {
                $('#phone_no').removeClass('is-invalid');
                $('#submit_main_company').removeAttr('disabled');
            }
        });

        $('#tax_number').keyup(function () {
            if ($('#tax_number').val().length < 3) {
                $('#tax_number').addClass('is-invalid');
                $('#submit_main_company').attr('disabled', 'disabled');
            } else {
                $('#tax_number').removeClass('is-invalid');
                $('#submit_main_company').removeAttr('disabled');
            }
        });

        $('#main_address').keyup(function () {
            if ($('#main_address').val().length < 3) {
                $('#main_address').addClass('is-invalid');
                $('#submit_main_company').attr('disabled', 'disabled');
            } else {
                $('#main_address').removeClass('is-invalid');
                $('#submit_main_company').removeAttr('disabled');
            }
        });

        $('#postal_code').keyup(function () {
            if ($('#postal_code').val().length < 3) {
                $('#postal_code').addClass('is-invalid');
                $('#submit_main_company').attr('disabled', 'disabled');
            } else {
                $('#postal_code').removeClass('is-invalid');
                $('#submit_main_company').removeAttr('disabled');
            }
        });

        $('#postal_box').keyup(function () {
            if ($('#postal_box').val().length < 3) {
                $('#postal_box').addClass('is-invalid');
                $('#submit_main_company').attr('disabled', 'disabled');
            } else {
                $('#postal_box').removeClass('is-invalid');
                $('#submit_main_company').removeAttr('disabled');
            }
        });

        $('#commercial_register').keyup(function () {
            if ($('#commercial_register').val().length < 3) {
                $('#commercial_register').addClass('is-invalid');
                $('#submit_main_company').attr('disabled', 'disabled');
            } else {
                $('#commercial_register').removeClass('is-invalid');
                $('#submit_main_company').removeAttr('disabled');
            }
        });

        $('#companys_number').keyup(function () {
            if ($('#companys_number').val() <= 0) {
                $('#companys_number').addClass('is-invalid');
                $('#submit_main_company').attr('disabled', 'disabled');
            } else {
                $('#companys_number').removeClass('is-invalid');
                $('#submit_main_company').removeAttr('disabled');
            }
        });

        function validEmail(email) {
            var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }
    </script>

@endsection


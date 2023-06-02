@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>
@endsection

@section('content')

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6>  @lang('maintenanceType.m_cards') </h6>
                            <h3 class="pt-2"><span class="counter">{{$maintenance_card_all}}</span></h3>
                            <span><span class="text-success mr-2"><i class="fa fa-car"></i>  </span> </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6>@lang('maintenanceType.mntns_card_q_count')</h6>
                            <h3 class="pt-2"><span class="counter">{{$maintenance_card_q}}</span></h3>
                            <span><span class="text-danger mr-2"><i class="fa fa-car"></i> </span> </span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6> @lang('maintenanceType.mntns_card_o_count')</h6>
                            <h3 class="pt-2"><span class="counter">{{$maintenance_card_o}}</span></h3>
                            <span><span class="text-danger mr-2"><i class="fa fa-car"></i> </span>  </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6> @lang('maintenanceType.mntns_card_c_count')</h6>
                            <h3 class="pt-2"><span class="counter">{{$maintenance_card_c}}</span></h3>
                            <span><span class="text-success mr-2"><i class="fa fa-car"></i> </span> </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="tab-content mt-3">
        <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">
            <div class="card">

                <div class="card-body">

                    <div class="col-md-2">
                        <div class="header-action">
                            @foreach(session('job')->permissions as $job_permission)
                                @if($job_permission->app_menu_id == 71 && $job_permission->permission_add)
                                    <div class="header-action">
                                        <a class="btn btn-primary text-white" data-toggle="modal"
                                           data-target="#add_card_modal">
                                            <i class="fe fe-plus mr-2"></i> كرت صيانة جديد
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                    </div>

                    <form action="">
                        <div class="row">

                            <div class="col-md-6">
                                <label>{{ __('Main Company') }}</label>
                                @if(session('company_group'))
                                    <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                                    {{ session('company_group')['company_group_ar'] }} @else
                                    {{ session('company_group')['company_group_en'] }} @endif" readonly>
                                @else
                                    <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                                    {{ auth()->user()->companyGroup->company_group_ar }} @else
                                    {{ auth()->user()->companyGroup->company_group_en }} @endif" readonly>
                                @endif
                            </div>

                            <div class="col-md-3">
                                <label>{{__('company')}}</label>
                                <select class="form-control" name="company_id">
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

                            </div>

                            <div class="col-md-3">
                                <label>@lang('maintenanceType.mntns_card_plate')</label>
                                <input type="text" class="form-control" name="mntns_cars_id"
                                       @if(request()->mntns_cars_id) value="{{ request()->mntns_cars_id }}" @endif>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-3">
                                <label>@lang('maintenanceType.mntns_card_mobile')</label>
                                <input type="text" class="form-control" name="mntns_card_mobile"
                                       @if(request()->mntns_card_mobile) value="{{ request()->mntns_card_mobile }}" @endif>
                            </div>

                            <div class="col-md-3">
                                <label>@lang('maintenanceType.mntns_card_no')</label>
                                <input type="text" class="form-control" name="mntns_card_no"
                                       @if(request()->mntns_card_no) value="{{ request()->mntns_card_no }}" @endif>
                            </div>

                            <div class="col-md-3">
                                <label>@lang('maintenanceType.mntns_card_status')</label>
                                {{--<input type="text" class="form-control" name="mntns_card_status"--}}
                                       {{--@if(request()->mntns_card_status) value="{{ request()->mntns_card_status }}" @endif>--}}

                                <select class="form-control" name="mntns_card_status">
                                    <option value="">@lang('home.choose')</option>
                                    @foreach($cards_status as $card_status)
                                        <option value="{{$card_status->system_code_id}}"
                                                @if(request()->mntns_card_status == $card_status->system_code_id) selected @endif>
                                            {{app()->getLocale() == 'ar' ? $card_status->system_code_name_ar
                                        :$card_status->system_code_name_en}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <br>
                                <br>
                                <button type="submit" class="btn btn-primary">{{__('search')}}</button>
                            </div>

                        </div>
                    </form>
                    <br>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-striped   card_table">
                            <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>@lang('maintenanceType.mntns_card_no')</th>
                                <th>@lang('maintenanceType.mntns_card_types')</th>
                                <th>@lang('home.date')</th>
                                <th>@lang('maintenanceType.mntns_card_customer')</th>
                                <th>@lang('maintenanceType.mntns_card_cus_type')</th>
                                <th>@lang('maintenanceType.mntns_card_mobile')</th>
                                <th></th>
                                <th>@lang('maintenanceType.mntns_card_plate')</th>
                                <th>@lang('maintenanceType.mntns_card_status')</th>
                                <th>@lang('maintenanceType.mntns_card_paid')</th>
                                <th>@lang('maintenanceType.mntns_card_amount')</th>


                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($maintenance_card as $k=>$m_card)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td>{{$m_card->mntns_cards_no}}</td>
                                    <td>{{$m_card->type_system_code_name_ar}}</td>
                                    <td>{{$m_card->created_date}}</td>
                                    <td>{{$m_card->customer_name_full_ar}}</td>
                                    <td>{{$m_card->customer_system_code_name_ar}}</td>
                                    <td>{{$m_card->customer_mobile}}</td>
                                    <td></td>
                                    <td>{{$m_card->truck_name}}</td>
                                    <td>{{$m_card->status_system_code_name_ar}}</td>
                                    <td>{{$m_card->mntns_cards_payment_amount}}</td>
                                    <td>{{$m_card->mntns_cards_due_amount}}</td>
                                    <td>
                                        <div class="row">

                                            @if(auth()->user()->user_type_id != 1)
                                                @foreach(session('job')->permissions as $job_permission)
                                                    @if($job_permission->app_menu_id == 71 && $job_permission->permission_add)
                                                        <div class="col-md-2">
                                                            <a class="btn btn-icon"
                                                               href=" {{ route('maintenance-card.edit' , $m_card->uuid) }}"
                                                               title="">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif

                                            @if(auth()->user()->user_type_id == 1)
                                                <div class="col-md-2">
                                                    <a class="btn btn-icon"
                                                       href=" {{ route('maintenance-card.edit' , $m_card->uuid) }}"
                                                       title="">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </div>
                                            @endif


                                            @if($m_card->mntns_cards_due_amount != 0)
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-icon btn-lg text-danger"
                                                            data-toggle="modal"
                                                            data-target="#exampleModal{{$m_card->uuid}}"
                                                            data-whatever="@mdo">
                                                        @lang('home.add_bond')
                                                    </button>
                                                </div>
                                            @endif
                                        </div>

                                        {{--سند القبض--}}
                                        <div class="modal fade" id="exampleModal{{$m_card->uuid}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="exampleModalLabel">@lang('home.add_capture_bond')</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <form action="{{ route('maintenance-card.addBondWithJournal') }}"
                                                              method="post">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">@lang('home.sub_company')</label>
                                                                        @if(session('company'))
                                                                            <input type="text" class="form-control"
                                                                                   disabled=""
                                                                                   value="{{ app()->getLocale()=='ar' ? session('company')['company_name_ar']
                                                            : session('company')['company_name_en ']}}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                   disabled=""
                                                                                   value="{{ app()->getLocale()=='ar' ? auth()->user()->company->company_name_ar
                                                            :  auth()->user()->company->company_name_en }}">
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">@lang('home.branch')</label>
                                                                        <input type="text" class="form-control"
                                                                               disabled=""
                                                                               value="{{ app()->getLocale()=='ar' ? session('branch')['branch_name_ar']
                                                            : session('branch')['branch_name_en'] }}">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">@lang('home.created_date')</label>
                                                                        <input type="text" id="date"
                                                                               class="form-control" disabled="">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">@lang('home.user')</label>
                                                                        <input type="text" class="form-control"
                                                                               disabled=""
                                                                               placeholder="Company"
                                                                               value="{{ app()->getLocale()=='ar' ? auth()->user()->user_name_ar :
                                                auth()->user()->user_name_en }}">
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="transaction_id"
                                                                       value="{{$m_card->mntns_cards_id}}">

                                                                {{--النشاط--}}
                                                                <div class="col-sm-6 col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">@lang('home.bonds_activity')</label>

                                                                        <input type="text" disabled=""
                                                                               value=" كارت الصيانه"
                                                                               class="form-control">
                                                                        <input type="hidden" name="transaction_type"
                                                                               value="58">

                                                                    </div>
                                                                </div>

                                                                {{--الرقم المرجعي--}}
                                                                <div class="col-sm-6 col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">@lang('home.reference_number')</label>

                                                                        <input type="text" class="form-control"
                                                                               name="bond_ref_no"
                                                                               value="{{ $m_card->mntns_cards_no }}"
                                                                               readonly>
                                                                    </div>
                                                                </div>

                                                                {{--القيمه المستحقه--}}
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">@lang('home.deserved_value')</label>
                                                                        <input type="text" class="form-control"
                                                                               readonly
                                                                               id="deserved_value"
                                                                               value="{{ $m_card->mntns_cards_due_amount }}">

                                                                    </div>
                                                                </div>

                                                                {{--نوع الحساب--}}
                                                                <div class="col-sm-6 col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">@lang('home.account_type')</label>

                                                                        <input type="text" class="form-control"
                                                                               readonly
                                                                               value="{{app()->getLocale()=='ar' ? \App\Models\SystemCode::where('system_code',56002)
                                                   ->first()->system_code_name_ar : \App\Models\SystemCode::where('system_code',56002)
                                                   ->first()->system_code_name_en}}">
                                                                        <input type="hidden" value="{{ \App\Models\SystemCode::where('system_code',56002)
                                            ->first()->system_code_id}}" name="account_type">
                                                                    </div>
                                                                </div>


                                                                {{--نوع العميل--}}
                                                                <div class="col-sm-6 col-md-4">
                                                                    <div class="form-group">
                                                                        <input type="hidden" name="customer_type"
                                                                               value="customer">

                                                                        <label class="form-label">@lang('home.customer')</label>

                                                                        <input type="text" readonly
                                                                               class="form-control"
                                                                               value="{{app()->getLocale()=='ar' ? $m_card->customer_name_full_ar :
                                                              $m_card->customer_name_full_en  }}">
                                                                        <input type="hidden" name="customer_id"
                                                                               value="{{ $m_card->customer_id }}">
                                                                    </div>
                                                                </div>

                                                                {{-- قم الحساب للعميل--}}
                                                                <div class="col-sm-6 col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">@lang('home.account_code')</label>
                                                                        <input type="hidden" class="form-control"
                                                                               name="bond_acc_id"
                                                                               value="{{ $m_card->customer_account_id}}">
                                                                        @if($m_card->acc_name_ar)
                                                                            <input type="text" readonly
                                                                                   class="form-control"
                                                                                   value="{{app()->getLocale() == 'ar' ?
                                                               $m_card->acc_name_ar :
                                                               $m_card->acc_name_en}} . {{ $m_card->acc_code }}">
                                                                        @else
                                                                            <input type="text" readonly
                                                                                   class="form-control"
                                                                                   value="لا يوجد حساب للعميل">
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                {{--انواع الايرادات--}}
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">@lang('home.revenue_types')</label>
                                                                        <select class="form-control"
                                                                                data-live-search="true"
                                                                                name="bond_doc_type" required>
                                                                            <option value="">@lang('home.choose')</option>
                                                                            @foreach(App\Models\SystemCode::where('sys_category_id', 58)
                                                    ->where('company_group_id', $m_card->company_group_id)->get() as $system_code)
                                                                                <option value="{{$system_code->system_code_id}}">
                                                                                    {{ app()->getLocale()=='ar' ?
                                                                                $system_code->system_code_name_ar :  $system_code->system_code_name_en }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                {{--طرق الدفع--}}
                                                                <div class="col-sm-6 col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">@lang('home.payment_method')</label>
                                                                        <select class="form-control"
                                                                                onchange="validInputs($(this))"
                                                                                name="bond_method_type"
                                                                                required>
                                                                            <ooption
                                                                                    value="">@lang('home.choose')</ooption>
                                                                            @foreach( App\Models\SystemCode::where('sys_category_id', 57)
                                                    ->where('company_group_id', $m_card->company_group_id)->get() as $payment_method)
                                                                                <option value="{{ $payment_method->system_code }}">{{ app()->getLocale()=='ar' ?
                                                 $payment_method->system_code_name_ar : $payment_method->system_code_name_en }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                {{--رقم العمليه--}}
                                                                <div class="col-sm-6 col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">@lang('home.process_number')</label>
                                                                        <input type="text" class="form-control"
                                                                               name="process_number" disabled="">
                                                                    </div>
                                                                </div>

                                                                {{--البنك--}}
                                                                <div class="col-sm-6 col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">@lang('home.bank')</label>
                                                                        <select class="form-control"
                                                                                name="bond_bank_id" disabled="">
                                                                            <option value="">@lang('home.choose')</option>
                                                                            @foreach(App\Models\SystemCode::where('sys_category_id', 40)
                                                    ->where('company_group_id', $m_card->company_group_id)->get() as $bank)
                                                                                <option value="{{ $bank->system_code_id }}">
                                                                                    {{ app()->getLocale()=='ar' ? $bank->system_code_name_ar :
                                                                                     $bank->system_code_name_en }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                {{--القيمه--}}
                                                                <div class="col-sm-6 col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">@lang('home.value')</label>
                                                                        <input type="text" class="form-control"
                                                                               id="value"
                                                                               value="{{$m_card->mntns_cards_due_amount }}"
                                                                               name="bond_amount_credit" required
                                                                               onkeyup="validPaid($(this))">
                                                                        <small class="text-danger"
                                                                               id="error_message">
                                                                        </small>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-6 col-md-6">
                                                                    <label class="form-label">@lang('home.notes')</label>
                                                                    <textarea class="form-control" name="bond_notes"
                                                                              placeholder="@lang('home.notes')"></textarea>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                            class="btn btn-primary btn-sm"
                                                                            id="submit_button">
                                                                        @lang('home.add_bond')
                                                                    </button>
                                                                </div>

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
                    <div>
                        {{$maintenance_card->links()}}
                    </div>
                </div>
            </div>
        </div>


        <div id="add_card_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="width:130%">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" onclick="closeItemModal()">&times;
                        </button>
                        <h4 class="modal-title" style="text-align:left">@lang('maintenanceType.m_new_card')</h4>
                    </div>

                    <div class="modal-body">
                        <form id="card_data_form" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="recipient-name"
                                                   class="col-form-label ">  @lang('maintenanceType.mntns_card_types') </label>
                                            <select class="form-select form-control is-invalid" name="mntns_cards_type"
                                                    id="mntns_cards_type" required>

                                                <option value="" selected> choose</option>
                                                @foreach($mntns_cards_type as $mct)
                                                    <option value="{{$mct->system_code_id}}"> {{ $mct->getSysCodeName() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('maintenanceType.mntns_type') </label>
                                            <select class="form-select form-control is-invalid"
                                                    name="mntns_cards_category"
                                                    id="mntns_cards_category" required>
                                                <option value="" selected> choose</option>
                                                @foreach($mntns_cards_category as $mcc)
                                                    <option value="{{$mcc->system_code_id}}"> {{ $mcc->getSysCodeName() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('maintenanceType.mntns_card_cus_type') </label>
                                            <select class="form-select form-control is-invalid"
                                                    name="mntns_cards_customer_type" id="mntns_cards_customer_type"
                                                    required>
                                                <option value="" selected> choose</option>
                                                @foreach($cards_customer_type as $customer_type)
                                                    <option value="{{$customer_type->system_code_id}}"> {{ $customer_type->getSysCodeName() }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-md-4">
                                            <label for="recipient-name"
                                                   class="col-form-label "> @lang('maintenanceType.mntns_card_customer') </label>
                                            <select class="selectpicker show-tick form-control customer"
                                                    data-live-search="true" name="customer_id" id="customer_id"
                                                    required>
                                                <option value="" selected> choose</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="recipient-name"
                                                   class="col-form-label">  @lang('maintenanceType.mntns_card_plate') </label>
                                            <select class="selectpicker show-tick form-control car"
                                                    name="mntns_cars_id" id="mntns_cars_id" data-live-search="true"
                                                    required>
                                                <option value="" selected> choose</option>
                                            </select>


                                        </div>
                                        <div class="col-md-4">
                                            <br>
                                            <br>
                                            @foreach(session('job')->permissions as $job_permission)
                                                @if($job_permission->app_menu_id == 59 && $job_permission->permission_add)
                                                    <a href="{{route('maintenance-car.create')}}" target="_blank"
                                                       class="btn btn-primary">
                                                        <i class="fe fe-plus mr-2"></i> @lang('maintenanceType.mntns_new_car')
                                                    </a>
                                                @endif
                                            @endforeach

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="recipient-name"
                                                   class="col-form-label "> @lang('maintenanceType.mntns_counter_car') </label>
                                            <input type="number" class="form-control is-invalid" name="mntns_cars_meter"
                                                   id="mntns_cars_meter" value="">
                                        </div>
                                        <div class="col-md-8">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('home.notes') </label>
                                            <textarea rows="2" class="form-control" name="mntns_cards_notes"
                                                      id="mntns_cards_notes" placeholder="Here can be your note"
                                                      value=""></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                            <button type="button" id="btnCancel" class="btn btn-secondary btn-block"
                                    onclick="closeItemModal()"> @lang('maintenanceType.mntns_b_cance')</button>
                        </div>
                        <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                            <button type="button" id="btnSave" class="btn btn-success forward btn-block"
                                    onclick="saveCard()"> @lang('maintenanceType.mntns_b_add')</button>
                        </div>
                    </div>
                </div>
                   
            </div>
        </div>

        @include('Maintenance.MaintenanceCard.form.add_card')
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script type="text/javascript">
                @if(session('company'))
        var company_id =
                {{ request()->company_id ? request()->company_id : session('company')['company_id'] }}

                @else
        var company_id = {{ request()->company_id ? request()->company_id : auth()->user()->company_id }}

                        @endif

                        {{--$(function () {--}}
                        {{--lang = '{{app()->getLocale()}}';--}}
                        {{--console.log(lang);--}}

                        {{--var table = $('.card_table').DataTable({--}}
                        {{--language: {--}}
                        {{--search: "البحث",--}}
                        {{--Show: "",--}}
                        {{--info: " ",--}}
                        {{--infoEmpty: " ",--}}
                        {{--paginate: {--}}
                        {{--first: "الاول",--}}
                        {{--previous: "السابق",--}}
                        {{--next: "التالي",--}}
                        {{--last: "الاخير",--}}
                        {{--processing: "جاري البحث...."--}}
                        {{--},--}}
                        {{--aria: {--}}
                        {{--sortAscending: ": activer pour trier la colonne par ordre croissant",--}}
                        {{--sortDescending: ": activer pour trier la colonne par ordre décroissant"--}}
                        {{--}--}}
                        {{--},--}}
                        {{--processing: true,--}}
                        {{--serverSide: true,--}}
                        {{--ajax: "?company_id=" + company_id,--}}
                        {{--columns: [--}}

                        {{--{data: 'DT_RowIndex', name: 'DT_RowIndex'},--}}
                        {{--{data: 'card_no', name: 'card_no'},--}}
                        {{--{data: 'card_type', name: 'card_type'},--}}
                        {{--{data: 'card_date', name: 'card_date'},--}}
                        {{--{data: 'customer', name: 'customer'},--}}
                        {{--{data: 'customer_type', name: 'customer_type'},--}}
                        {{--{data: 'customer_mobile', name: 'customer_mobile'},--}}
                        {{--{data: 'truckname', name: 'truckname'},--}}
                        {{--{data: 'car', name: 'car'},--}}
                        {{--{data: 'status', name: 'status'},--}}
                        {{--{data: 'payment', name: 'payment'},--}}
                        {{--{data: 'due', name: 'due'},--}}
                        {{--{--}}
                        {{--data: 'action',--}}
                        {{--name: 'action',--}}
                        {{--orderable: true,--}}
                        {{--searchable: true--}}
                        {{--},--}}
                        {{--]--}}
                        {{--});--}}

                        {{--});--}}


            function addCard() {

                $.ajax({
                    type: 'GET',
                    url: "{{route('maintenance-card.card.create')}}",
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                }).done(function (data) {
                    if (data.success) {
                        $('#add_card_modal').modal("show").on('shown.bs.modal', function () {
                            $('#add_card_modal .modal-body').html(data.view.content);
                        });
                    }
                    else {
                        toastr.warning('ERORR !');
                    }
                });
            }

        function closeItemModal() {
            $('#add_card_modal').on('hidden.bs.modal', function () {
                //$('#add_card_modal .modal-body').html('');
            });
            $('#add_card_modal').modal('hide');
        }

        function show(el) {
            var x = el.id;
            $("#app-" + x).css("display", "block");
            $("#app-" + x).siblings().css('display', 'none')
        }

        $('#mntns_cards_type').change(function () {
            if (!$('#mntns_cards_type').val()) {
                $('#mntns_cards_type').addClass('is-invalid')
            } else {
                $('#mntns_cards_type').removeClass('is-invalid')
            }
        });

        $('#mntns_cards_category').change(function () {
            if (!$('#mntns_cards_category').val()) {
                $('#mntns_cards_category').addClass('is-invalid')
            } else {
                $('#mntns_cards_category').removeClass('is-invalid')
            }
        });

        $('#mntns_cards_customer_type').change(function () {
            if (!$('#mntns_cards_customer_type').val()) {
                $('#mntns_cards_customer_type').addClass('is-invalid')
            } else {
                $('#mntns_cards_customer_type').removeClass('is-invalid')
            }
        });

        $('#customer_id').change(function () {
            console.log($('#customer_id').val());
            if (!$('#customer_id').val()) {
                $('#customer_id').addClass('is-invalid');
                $('.customer').addClass("is-invalid");
            } else {
                $('#customer_id').removeClass('is-invalid');
                $('.customer').removeClass("is-invalid");

            }
        });

        $('#mntns_cars_id').change(function () {
            if (!$('#mntns_cars_id').val()) {
                $('#mntns_cars_id').addClass('is-invalid');
                $('.car').addClass("is-invalid");
            } else {
                $('#mntns_cars_id').removeClass('is-invalid');
                $('.car').removeClass("is-invalid");
            }
        });

        $('#mntns_cars_meter').keyup(function () {
            console.log('he')
            if ($('#mntns_cars_meter').val().length < 2) {
                $('#mntns_cars_meter').addClass('is-invalid')
            } else {
                $('#mntns_cars_meter').removeClass('is-invalid');
            }
        });


        // get cusotmer registerd car
        $('#customer_id').on('change', function () {
            //console.log('changed');
            $("#mntns_cars_id option").remove();
            var customer_id = $('#customer_id').val();
            $.ajax({
                url: '{{ route( 'maintenanced-car.get.car.list.by.customer.id' ) }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "customer_id": customer_id
                },
                type: 'get',
                dataType: 'json',
                success: function (result) {
                    $('#mntns_cars_id').append($('<option>', {value: '', text: 'choose'}));
                    $.each(result.data, function (k) {
                        data = result.data;
                        // console.log('changed'+ result.data[k].brand.system_code_name_ar + '-' + data[k].mntns_cars_color + '-' + data[k].mntns_cars_plate_no);
                        // console.log(lang);

                        // if (lang == 'ar') {
                        $('#mntns_cars_id').append($('<option>', {
                            value: data[k].mntns_cars_id,
                            text: result.data[k].brand.system_code_name_ar + '-' + data[k].mntns_cars_plate_no + '-' + data[k].mntns_cars_type
                        }));
                        // }
                        // else {
                        //     $('#mntns_cars_id').append($('<option>', {
                        //         value: data[k].mntns_cars_id,
                        //         text: result.data[k].brand.system_code_name_en + '-' + data[k].mntns_cars_plate_no + '-' + data[k].mntns_cars_type
                        //     }));
                        // }


                    });
                    $('#mntns_cars_id').selectpicker('refresh');
                },
                error: function () {
                    //handle errors
                    alert('error...');
                }
            });
        });

        //get customer list base on cusomter type
        $('#mntns_cards_customer_type').on('change', function () {
            //console.log('changed');
            $("#customer_id option").remove();
            var customer_type = $('#mntns_cards_customer_type').val();
            $.ajax({
                url: '{{ route( 'maintenance-card.get.customer.by.type' ) }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "customer_type": customer_type
                },
                type: 'get',
                dataType: 'json',
                success: function (result) {
                    $('#customer_id').append($('<option>', {value: '', text: 'choose'}));
                    $.each(result.data, function (k) {
                        // //console.log(result.data[k].mntns_cars_id);
                        // if (lang == 'ar') {
                        $('#customer_id').append($('<option>', {
                            value: result.data[k].customer_id,
                            text: result.data[k].customer_name_full_ar
                        }));
                        // }
                        // else {
                        //     $('#customer_id').append($('<option>', {
                        //         value: result.data[k].customer_id,
                        //         text: result.data[k].customer_name_full_en
                        //     }));
                        // }
                    });
                    $('#customer_id').selectpicker('refresh');

                },
                error: function () {
                    //handle errors
                    alert('error...');
                }
            });
        });

        function saveCard() {
            // if ($('.is-invalid').length > 0) {
            //     return toastr.warning('تاكد من ادخال كافة الحقول');
            // }

            url = '{{ route('maintenance-card.card.store') }}'
            var form = new FormData($('#card_data_form')[0]);
            var data = form;
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,

            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    url = '{{ route("maintenance-card.edit", ":id") }}';
                    url = url.replace(':id', data.uuid);
                    window.location.href = url;
                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }
    </script>


@endsection


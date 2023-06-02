@extends('Layouts.master')
@section('style')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>
@endsection
@section('content')
    <div class="section-gray py-4">
        <div class="container-fluid">
            <div class="row">

                <div class="card">
                    <div class="card-body">
                        <form action="">

                            <div class="col-md-12">
                                <div class="row">

                                    {{--الشركات--}}
                                    <div class="col-md-4">
                                        <label>@lang('home.companies')</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="company_id[]">
                                            @foreach($companies as $company)
                                                <option value="{{$company->company_id}}"
                                                        @if(request()->company_id) @foreach(request()->company_id as
                                                     $company_id) @if($company->company_id == $company_id) selected @endif @endforeach @endif>
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$company->company_name_ar}}
                                                    @else
                                                        {{$company->company_name_en}}
                                                    @endif
                                                </option>

                                            @endforeach
                                        </select>

                                    </div>

                                    {{--الفروع--}}
                                    <div class="col-md-4">
                                        {{-- branches  --}}
                                        <label>@lang('home.branches')</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="branch_ids[]">
                                            @foreach($companies as $company)
                                                @foreach($company->branches as $branch)
                                                    <option @if(request()->branch_ids) @foreach(request()->branch_ids as
                                                     $branch_id) @if($branch->branch_id == $branch_id) selected
                                                            @endif @endforeach @endif value="{{ $branch->branch_id }}">{{ app()->getLocale()=='ar' ?
                                                     $branch->branch_name_ar : $branch->branch_name_en }}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>

                                    {{--طرق الدفع--}}
                                    <div class="col-md-4">
                                        <label>@lang('home.payment_method')</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="bond_method_type[]">
                                            @foreach($payment_methods as $payment_method)
                                                <option @if(request()->bond_method_type) @foreach(request()->bond_method_type as
                                                     $method) @if($payment_method->system_code == $method) selected
                                                        @endif @endforeach @endif  value="{{ $payment_method->system_code }}">
                                                    {{ app()->getLocale()=='ar' ?  $payment_method->system_code_name_ar :
                                                $payment_method->system_code_name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">

                                    {{--النشاط --}}
                                    <div class="col-md-4">
                                        <label>@lang('home.bonds_activity')</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="transaction_type[]">
                                            @foreach($companies as $company)
                                                @foreach($company->appsActive as $application)
                                                    @foreach($application->applicationMenuVoucher as $application_menu)
                                                        <option @if(request()->transaction_type) @foreach(request()->transaction_type as
                                                     $transaction_type) @if($application_menu->app_menu_id == $transaction_type) selected
                                                                @endif @endforeach @endif  value="{{ $application_menu->app_menu_id }}">
                                                            @if(app()->getLocale()=='ar')
                                                                {{ $application_menu->app_menu_name_ar }}
                                                            @else   {{ $application_menu->app_menu_name_en }} @endif
                                                        </option>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>

                                    {{--رقم السند--}}
                                    <div class="col-md-4">
                                        <label>@lang('home.bonds_number')</label>
                                        <input type="text" class="form-control" name="bond_code"
                                               value="{{ request()->bond_code ? request()->bond_code : '' }}"
                                               placeholder="@lang('home.bonds_number')">
                                    </div>

                                </div>

                                <div class="row">
                                    {{--رقم الحساب--}}
                                    <div class="col-md-2">
                                        <label>@lang('home.bonds_account')</label>
                                        <input type="text" class="form-control" name="bond_acc_id"
                                               value="{{ request()->bond_acc_id ? request()->bond_acc_id : '' }}"
                                               placeholder="@lang('home.bonds_account')">
                                    </div>

                                    {{--رقم العمليه--}}
                                    <div class="col-md-2">
                                        <label>@lang('home.bonds_transaction_number')</label>
                                        <input type="text" class="form-control" name="bond_check_no"
                                               value="{{ request()->bond_check_no ? request()->bond_check_no : '' }}"
                                               placeholder="@lang('home.bonds_transaction_number')">
                                    </div>

                                    {{--تاريخ اانشاء من والي--}}
                                    <div class="col-md-2">
                                        <label>@lang('home.created_date_from')</label>
                                        <input type="date" class="form-control" name="created_date_from"
                                               @if(request()->created_date_from) value="{{request()->created_date_from}}"
                                                @endif>
                                    </div>
                                    <div class="col-md-2">
                                        <label>@lang('home.created_date_to')</label>
                                        <input type="date" class="form-control" name="created_date_to"
                                               @if(request()->created_date_to) value="{{request()->created_date_to}}" @endif>
                                    </div>

                                    <div class="col-md-2">
                                        <button class="btn btn-primary mt-4" type="submit">@lang('home.search')
                                            <i class="fa fa-search fa-fw"></i></button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="row mb-12">
                <div class="col- col-md-3">
                    @if(auth()->user()->user_type_id != 1)
                        @foreach(session('job')->permissions as $job_permission)
                            @if($job_permission->app_menu_id == 53 && $job_permission->permission_add)
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#exampleModal">

                                    <a href="{{ route('bonds.capture.safe.create') }}" class="btn btn-primary">
                                        <i class="fe fe-plus mr-2"></i>@lang('home.add_new_capture')
                                    </a>
                                </button>
                            @endif
                        @endforeach
                    @else
                        <button type="button" class="btn btn-primary">

                            <a href="{{ route('bonds.capture.safe.create') }}" class="btn btn-primary">
                                <i class="fe fe-plus mr-2"></i>@lang('home.add_new_capture')
                            </a>
                        </button>
                    @endif
                </div>
                <div class="col-md-6">
                </div>
                <div class="col-6 col-md-3 d-flex justify-content-end">
                    <form action="{{route('bonds.capture.safe.export') }}">
                        @if(request()->company_id)
                            @foreach(request()->company_id as $company_id)
                                <input type="hidden" name="company_id[]" value="{{ $company_id }}">
                            @endforeach
                        @endif

                        @if(request()->branch_ids)
                            @foreach(request()->branch_ids as $branch_id)
                                <input type="hidden" name="branch_ids[]" value="{{ $branch_id }}">
                            @endforeach
                        @endif

                        @if(request()->bond_method_type)
                            @foreach(request()->bond_method_type as $bond_method_type)
                                <input type="hidden" name="bond_method_type[]" value="{{ $bond_method_type }}">
                            @endforeach
                        @endif

                        @if(request()->transaction_type)
                            @foreach(request()->transaction_type as $transaction_type)
                                <input type="hidden" name="transaction_type[]" value="{{ $transaction_type }}">
                            @endforeach
                        @endif

                        @if (request()->created_date_from && request()->created_date_to)
                            <input type="hidden" name="created_date_from" value="{{request()->created_date_from}}">
                            <input type="hidden" name="created_date_to" value="{{request()->created_date_to}}">
                        @endif

                        @if(request()->bond_code)
                            <input type="hidden" name="bond_code" value="{{request()->bond_code}}">
                        @endif

                        @if(request()->bond_acc_id)
                            <input type="hidden" name="bond_acc_id" value="{{ request()->bond_acc_id }}">
                        @endif

                        @if(request()->bond_check_no)
                            <input type="hidden" name="bond_check_no" value="{{ request()->bond_check_no }}">
                        @endif
                        <button type="submit"
                                class="btn btn-primary">@lang('home.export_sheet')
                            <i class="fa fa-file-excel-o"></i></button>
                    </form>

                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card bg-none">

                        <div class="card-body pt-0">
                            <div class="table-responsive table_e2">
                                <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>@lang('home.bonds_number')</th>
                                        <th>@lang('home.bonds_date')</th>
                                        <th>@lang('home.sub_company')</th>
                                        <th>@lang('home.branch')</th>
                                        <th>@lang('home.bonds_activity')</th>
                                        <th>@lang('home.bonds_account')</th>
                                        <th>@lang('home.payment_method')</th>
                                        <th>@lang('home.value')</th>
                                        <th>@lang('home.user')</th>
                                        <th>@lang('home.journal')</th>
                                        <th></th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($bonds as $bond)
                                        <tr>
                                            <td>
                                                <a href="{{route('Bonds-capture.show',$bond->bond_id)}}"
                                                   class="btn btn-primary btn-sm">
                                                    {{ $bond->bond_code }}
                                                </a>
                                            </td>
                                            <td>{{ $bond->bond_date }}</td>
                                            <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->company->company_name_ar :
                                            $bond->company->company_name_en }}</td>

                                            <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->branch->branch_name_ar :
                                            $bond->branch->branch_name_en }}</td>
                                            <td>
                                                @if( $bond->transactionType)
                                                    {{app()->getLocale()=='ar' ? $bond->transactionType->app_menu_name_ar :
                                                    $bond->transactionType->app_menu_name_en }}
                                                @else
                                                    غير مرتبط بنشاط
                                                @endif
                                            </td>
                                            <td>@if($bond->account)
                                                    {{ $bond->account->acc_name_ar . $bond->account->acc_code}}
                                                @endif</td>
                                            <td>
                                                @if($bond->bond_method_type)
                                                    {{ app()->getLocale() == 'ar' ? \App\Models\SystemCode::where('company_group_id', $bond->company_group_id)->where('system_code',$bond->bond_method_type)
                                                    ->first()->system_code_name_ar :
                                                  \App\Models\SystemCode::where('company_group_id', $bond->company_group_id)->where('system_code',$bond->bond_method_type)
                                                    ->first()->system_code_name_en }}
                                                @endif
                                            </td>
                                            <td>{{ $bond->bond_amount_debit }}</td>
                                            <td>{{ app()->getLocale()=='ar' ? $bond->userCreated->user_name_ar :
                                            $bond->userCreated->user_name_en }}</td>
                                            <td>
                                                @if($bond->journalCapture)
                                                    <a href="{{ route('journal-entries.show',$bond->journalCapture->journal_hd_id) }}"
                                                       class="btn btn-primary btn-sm">
                                                        @lang('home.journal_details')
                                                        {{$bond->journalCapture->journal_hd_code}}
                                                    </a>
                                                @endif


                                            </td>
                                            <td>
                                                <a href="{{config('app.telerik_server')}}?rpt={{$bond->report_url_receipt->report_url}}&id={{$bond->bond_id}}&lang=ar&skinName=bootstrap"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.print')"><i
                                                            class="fa fa-print"></i></a>

                                                <a href="{{ route('bonds.capture.safe.show',$bond->bond_id) }}"
                                                   class="btn btn-danger btn-sm" title="show">
                                                    <i class="fa fa-eye"></i></a>


                                                <a href="{{ route('bonds.capture.safe.edit',$bond->bond_id) }}"
                                                   class="btn btn-danger btn-sm" title="show">
                                                    <i class="fa fa-edit"></i></a>
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
            <div class="row">
                <div class="col-md-6">
                    {{ $bonds->appends($data)->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
@endsection

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
                                                name="company_id[]" @if($flag==0) disabled @else required @endif>
                                            @foreach($companies as $company)
                                                <option value="{{$company->company_id}}"
                                                        @if($flag==0) @if($company_auth->company_id == $company->company_id) selected
                                                        @endif @endif
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
                                                name="branch_ids[]" @if($flag==0) disabled @endif>
                                            @foreach($companies as $company)
                                                @foreach($company->branches as $branch)
                                                    <option @if(request()->branch_ids) @foreach(request()->branch_ids as
                                                     $branch_id) @if($branch->branch_id == $branch_id) selected
                                                            @endif @endforeach @endif
                                                            @if($flag==0) @if(session('branch')['branch_id'] == $branch->branch_id ) selected
                                                            @endif @endif
                                                            value="{{ $branch->branch_id }}">{{ app()->getLocale()=='ar' ?
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


                                    {{--بقيد او بدون--}}
                                    <div class="col-md-4">
                                        <label>@lang('home.journal')</label>

                                        <select name="journal_s" class="form-control">
                                            <option value="" selected>@lang('home.choose')</option>
                                            <option value="1"
                                                    @if(request()->journal_s) @if(request()->journal_s == 1) selected @endif @endif>
                                                قيد
                                            </option>
                                            <option value="2"
                                                    @if(request()->journal_s) @if(request()->journal_s == 2) selected @endif @endif>
                                                بدون قيد
                                            </option>
                                        </select>
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
                <div class="col-md-3">
                    @if(auth()->user()->user_type_id != 1)
                        @foreach(session('job')->permissions as $job_permission)
                            @if($job_permission->app_menu_id == 54 && $job_permission->permission_add)
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#exampleModal">

                                    <a href="{{ route('Bonds-cash.create') }}" class="btn btn-primary">
                                        <i class="fe fe-plus mr-2"></i>@lang('home.add_new_cash')
                                    </a>
                                </button>
                            @endif
                        @endforeach
                    @else
                        <button type="button" class="btn btn-primary">

                            <a href="{{ route('Bonds-cash.create') }}" class="btn btn-primary">
                                <i class="fe fe-plus mr-2"></i>@lang('home.add_new_cash')
                            </a>
                        </button>
                    @endif
                </div>


                <div class="col-md-6">
                    @if(request()->journal_s)
                        @if(request()->journal_s == 2)
                            <form action="{{route('Bonds-cash.approveAllBond')}}" method="post" id="formAll">
                                @csrf
                                @foreach($bonds as $bond)
                                    <input type="hidden" value="{{$bond->bond_id}}" name="bond_id[]">
                                @endforeach
                                <button type="button" class="btn btn-primary" id="submitAll"
                                        onclick="stopAllButton()">Approve All
                                </button>

                                <div class="spinner-border" role="status"
                                     style="display: none"
                                     id="loadingAll">
                                    <span class="sr-only">Loading...</span>

                                </div>

                            </form>

                        @endif
                    @endif
                </div>

                <div class="col-md-3  d-flex justify-content-end">
                    <form action="{{route('Bonds-cash.export') }}">
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
                                        <th>@lang('home.branch')</th>
                                        <th>{{__('Cash Type')}}</th>
                                        <th>@lang('home.notes')</th>
                                        <th>@lang('home.payment_method')</th>
                                        <th>@lang('home.value')</th>
                                        <th>@lang('home.user')</th>
                                        <th>@lang('home.journal')</th>
                                        @if($flag == 1)
                                            <th>التعميد</th>
                                        @endif
                                        <th></th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($bonds as $bond)
                                        <tr>
                                            <td>
                                                <a href="{{ route('Bonds-cash.show',$bond->bond_id) }}"
                                                   class="btn btn-primary btn-sm">
                                                    {{ $bond->bond_code }}
                                                </a>
                                            </td>
                                            <td>{{ $bond->created_date }}</td>

                                            <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->branch->branch_name_ar :
                                            $bond->branch->branch_name_en }}</td>
                                            <td>{{app()->getLocale() == 'ar' ?
                                            $bond->bondDocType->system_code_name_ar : $bond->bondDocType->system_code_name_en}}</td>
                                            <td>{{$bond->bond_notes}}</td>
                                            <td> @if($bond->bond_method_type)
                                                    {{ $bond->payment_method_name }}
                                                @endif</td>
                                            <td>{{ $bond->bond_amount_credit }}</td>
                                            <td>{{ app()->getLocale()=='ar' ? $bond->userCreated->user_name_ar :
                                            $bond->userCreated->user_name_en }}</td>
                                            <td>
                                                @if($bond->journalCash)
                                                    <a href="{{ route('journal-entries.show',$bond->journalCash->journal_hd_id) }}"
                                                       class="btn btn-primary btn-sm">
                                                        @lang('home.journal_details')
                                                        {{$bond->journalCash->journal_hd_code}}
                                                    </a>
                                                @endif

                                            </td>
                                            @if($flag == 1)
                                                <td>
                                                    @if(!$bond->journalCash)

                                                        <form action="{{route('Bonds-cash.approveOneBond')}}"
                                                              method="post" style="float:left"
                                                              id="form{{$bond->bond_id}}">
                                                            <input type="hidden" name="bond_id"
                                                                   value="{{$bond->bond_id}}">
                                                            <button type="button" id="submit{{$bond->bond_id}}"
                                                                    class="btn btn-primary btn-lg"
                                                                    onclick="stopButton('{{$bond->bond_id}}')">
                                                                {{__('approve')}}</button>

                                                            <div class="spinner-border" role="status"
                                                                 style="display: none"
                                                                 id="loading{{$bond->bond_id}}">
                                                                <span class="sr-only">Loading...</span>

                                                            </div>
                                                        </form>
                                                    @else
                                                        تم اضافه القيد
                                                    @endif
                                                </td>
                                            @endif
                                            <td>
                                                <a href="{{config('app.telerik_server')}}?rpt={{$bond->report_url_payment->report_url}}&id={{$bond->bond_id}}&lang=ar&skinName=bootstrap"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.show')"><i
                                                            class="fa fa-print"></i></a>
                                                <a href="{{ route('Bonds-cash.show',$bond->bond_id) }}"
                                                   class="btn btn-danger btn-sm" title="show">
                                                    <i class="fa fa-eye"></i></a>


                                                @if(auth()->user()->user_type_id != 1)
                                                    @foreach(session('job')->permissions as $job_permission)
                                                        @if($job_permission->app_menu_id == 54 && $job_permission->permission_update)
                                                            <a href="{{ route('Bonds-cash.edit',$bond->bond_id) }}"
                                                               class="btn btn-danger btn-sm" title="show">
                                                                <i class="fa fa-edit"></i></a>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <a href="{{ route('Bonds-cash.edit',$bond->bond_id) }}"
                                                       class="btn btn-danger btn-sm" title="show">
                                                        <i class="fa fa-edit"></i></a>
                                                @endif
                                            </td>
                                            </td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="5"><p class="text-center bold">@lang('home.total')</p></td>
                                        <td colspan="6">{{$total}}</td>
                                    </tr>
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
    <script>
        function stopButton(el) {
            console.log(el)
            $('#submit' + el).css('display', 'none');
            $('#loading' + el).css('display', 'block');
            $('#form' + el).submit();
        }

        function stopAllButton() {
            $('#submitAll').css('display', 'none');
            $('#loadingAll').css('display', 'block');
            $('#formAll').submit();
        }
    </script>
@endsection

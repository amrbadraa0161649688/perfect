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
            <div class="row clearfix">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body p-3">
                            <form action="">
                                <div class="row">
                                    {{--الشركات--}}
                                    <div class="col-md-6">
                                        <label>@lang('home.companies')</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="company_id[]" required>
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
                                    <div class="col-md-6">
                                        {{-- branches  --}}
                                        <label>@lang('home.branches')</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="branch_id[]">
                                            @foreach($companies as $company)
                                                @foreach($company->branches as $branch)
                                                    <option @if(request()->branch_ids) @foreach(request()->branch_id as
                                                     $branch_id) @if($branch->branch_id == $branch_id) selected
                                                            @endif @endforeach @endif value="{{ $branch->branch_id }}">{{ app()->getLocale()=='ar' ?
                                                     $branch->branch_name_ar : $branch->branch_name_en }}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="row">

                                    {{--نوع اليوميه--}}
                                    <div class="col-md-3">
                                        <label>@lang('home.daily_accounts_type')</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="journal_type_id[]">
                                            @foreach($journal_types as $journal_type)
                                                <option value="{{ $journal_type->system_code_id }}">
                                                    {{ app()->getLocale()=='ar' ?
                                                $journal_type->system_code_name_ar :
                                                $journal_type->system_code_name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{--حاله القيد--}}
                                    <div class="col-md-3">
                                        <label>@lang('home.restriction_account_status')</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="journal_status[]">
                                            @foreach($journal_statuses as $journal_status)
                                                <option value="{{$journal_status->system_code_id}}">
                                                    {{app()->getLocale()=='ar' ? $journal_status->system_code_name_ar
                                                    : $journal_status->system_code_name_en}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label>@lang('home.restriction_number')</label>
                                        <input type="text" class="form-control" name="journal_hd_code"
                                               @if(request()->journal_hd_code) value="{{request()->journal_hd_code}}" @endif>
                                    </div>

                                    <div class="col-md-3">
                                        <label>@lang('home.notes')</label>
                                        <input type="text" class="form-control" name="journal_hd_notes"
                                               @if(request()->journal_hd_notes) value="{{request()->journal_hd_notes}}" @endif>
                                    </div>
                                </div>

                                <div class="row">

                                    {{--تاريخ اانشاء من والي--}}
                                    <div class="col-md-3">
                                        <label>@lang('home.created_date_from')</label>
                                        <input type="date" class="form-control" name="created_date_from"
                                               @if(request()->created_date_from) value="{{request()->created_date_from}}"
                                                @endif>
                                    </div>
                                    <div class="col-md-3">
                                        <label>@lang('home.created_date_to')</label>
                                        <input type="date" class="form-control" name="created_date_to"
                                               @if(request()->created_date_to) value="{{request()->created_date_to}}" @endif>
                                    </div>


                                    <div class="col-4 col-md-2">
                                        <button class="btn btn-primary mt-4" type="submit">@lang('home.filter')</button>
                                    </div>

                                    <div hidden class="col-4 col-md-2 d-flex justify-content-center">
                                        <a hidden class="btn btn-primary mt-4"
                                           href="{{ route('journal-entries') }}">@lang('home.cancel_filters')
                                        </a>

                                    </div>
                                    <div class="col-4 col-md-2 d-flex justify-content-end">
                                        @foreach($companie as $companys)
                                            <a href="{{config('app.telerik_server')}}?rpt={{$companys->report_url_journal_all->report_url}}&id={{implode(',',request()->input('branch_id',[]))}}&from_date={{request()->created_date_from}}&to_date={{request()->created_date_to}}&j_type={{implode(',',request()->input('journal_type_id',[]))}}&j_status={{implode(',',request()->input('journal_status',[]))}}&lang=ar&skinName=bootstrap"
                                               class="btn btn-primary mt-4" target="_blank">@lang('home.print')</a>
                                        @endforeach
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card bg-none">
                        <div class="">
                            <div class="row justify-content-between">
                                <div class="col-6 col-md-3 my-1">
                                    <a href="{{ route('journal-entries.create') }}"
                                       class="btn btn-primary btn-sm">@lang('home.add_daily_restrictions')</a>
                                </div>

                                <div class="col-6 col-md-3 d-flex justify-content-end my-1">
                                    <a href="{{ route('journal-entries.create-sheet') }}"
                                       class="btn btn-primary btn-sm">@lang('home.upload_file')</a>
                                </div>

                            </div>

                            <div class="card-options">
                                <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i
                                            class="fe fe-maximize"></i></a>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive table_e2">
                                <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th> @lang('home.restriction_number')  </th>
                                        <th> @lang('home.date')  </th>


                                        <th>@lang('home.branch') </th>
                                        <th>@lang('home.daily_accounts_type') </th>
                                        <th style="width:150px">@lang('home.notes') </th>

                                        <th>@lang('home.debit') </th>
                                        <th>@lang('home.credit') </th>
                                        <th>@lang('home.user') </th>
                                        <th>@lang('home.restriction_account_status') </th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($journals as $journal)
                                        <tr>
                                            <td>
                                                <a href="{{ route('journal-entries.edit',$journal->journal_hd_id) }}"
                                                   class="btn btn-primary btn-sm">
                                                    {{ $journal->journal_hd_code }}
                                                </a>
                                            </td>
                                            <td>{{$journal->journal_hd_date}}</td>

                                            <td>{{  app()->getLocale()=='ar' ? $journal->branch->branch_name_ar :
                                             $journal->branch->branch_name_en}}</td>
                                            <td>{{ app()->getLocale()=='ar' ? $journal->journalType->system_code_name_ar :
                                             $journal->journalType->system_code_name_en }}</td>
                                            <td>{{$journal->journal_hd_notes}}</td>

                                            <td>{{$journal->journal_hd_debit }}</td>
                                            <td>{{$journal->journal_hd_credit }}</td>
                                            <td>
                                                {{ app()->getLocale()=='ar' ? $journal->user->user_name_ar :
                                             $journal->user->user_name_en }}
                                            </td>
                                            <td>
                                            <span class="tag tag-success"> 
                                                {{ app()->getLocale()=='ar' ? $journal->journalStatus->system_code_name_ar :
                                             $journal->journalStatus->system_code_name_en }}
                                             </span>
                                            </td>
                                            <td>
                                                @if($flag>0)
                                                    <form action="{{route('journal-entries.updateJournalStatus')}}"
                                                          method="post">
                                                        @csrf
                                                        <input type="hidden" name="journal_hd_id"
                                                               value="{{$journal->journal_hd_id}}">
                                                        <select class="custom-select" name="journal_status"
                                                                onchange="this.form.submit()">
                                                            <option value="">@lang('home.choose')</option>
                                                            @foreach($journal_statuses as $journal_status)
                                                                <option value="{{$journal_status->system_code_id}}"
                                                                        @if($journal->journal_status == $journal_status->system_code_id) selected @endif>
                                                                    {{app()->getLocale()=='ar' ? $journal_status->system_code_name_ar
                                                                    : $journal_status->system_code_name_en}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </form>
                                                @endif
                                            </td>
                                            <td>

                                                @if(auth()->user()->user_type_id != 1)
                                                    @foreach(session('job')->permissions as $job_permission)
                                                        @if($job_permission->app_menu_id == 33 && $job_permission->permission_update)
                                                            <a href="{{ route('journal-entries.edit',$journal->journal_hd_id) }}"
                                                               class="btn btn-primary btn-sm" title="Edit">
                                                                <i class="fa fa-edit"></i></a>

                                                            <a href="{{ route('journal-entries.edit_2',$journal->journal_hd_id) }}"
                                                               class="btn btn-primary btn-sm" title="Edit">
                                                                <i class="fa fa-edit"></i></a>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <a href="{{ route('journal-entries.edit',$journal->journal_hd_id) }}"
                                                       class="btn btn-primary btn-sm" title="Edit">
                                                        <i class="fa fa-edit"></i></a>

                                                    <a href="{{ route('journal-entries.edit_2',$journal->journal_hd_id) }}"
                                                       class="btn btn-primary btn-sm" title="Edit">
                                                        <i class="fa fa-edit"></i></a>
                                                @endif


                                                <a href="{{route('journal-entries.show',$journal->journal_hd_id)}}"
                                                   class="btn btn-danger btn-sm" title="show">
                                                    <i class="fa fa-eye"></i></a>
                                                <a href="{{config('app.telerik_server')}}?rpt={{$journal->report_url_journal->report_url}}&id={{$journal->journal_hd_id}}&lang=ar&skinName=bootstrap"
                                                   class="btn btn-primary btn-sm" title="Print"
                                                   target="_blank">
                                                    <i class="fa fa-print"></i></a>
                                                </a>

                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                {{ $journals->appends($data)->links() }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
@endsection


@extends('Layouts.master')

@section('content')
    <div class="section-body mt-3" id="app">


        <div class="section-body">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item">
                            <a href="#data-grid" data-toggle="tab"
                               class="nav-link active">@lang('home.data')</a>
                        </li>

                        <li class="nav-item"><a class="nav-link" href="#files-grid"
                                                data-toggle="tab">@lang('home.files')</a></li>

                        <li class="nav-item"><a class="nav-link" href="#notes-grid"
                                                data-toggle="tab">@lang('home.notes')</a></li>

                    </ul>
                </div>
            </div>
        </div>


        <div class="container-fluid">

            <div class="tab-content mt-3">
                {{-- dATA --}}
                <div class="tab-pane fade active show"
                     id="data-grid" role="tabpanel">

                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <form action="" method="post">
                                <div class="card-body">
                                    <h3 class="card-title">@lang('home.daily_restrictions_details')</h3>
                                    <div class="row">
                                        {{--الشركات--}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.companies')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       value="{{app()->getLocale()=='ar' ? $journal_hd->company->company_name_ar
                                        : $journal_hd->company->company_name_en}}">
                                            </div>
                                        </div>

                                        {{--الفروع--}}
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.branch')</label>
                                                <input type="text" disabled="" class="form-control"
                                                       value="{{app()->getLocale()=='ar' ? $journal_hd->branch->branch_name_ar
                                        : $journal_hd->branch->branch_name_en}}">
                                            </div>
                                        </div>

                                        {{--انواع يوميات قيود الحسابات--}}
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.daily_accounts_type')</label>
                                                <input type="text" disabled="" class="form-control"
                                                       value="{{ app()->getLocale()=='ar' ? $journal_hd->journalType->system_code_name_ar :
                                         $journal_hd->journalType->system_code_name_en}}">
                                            </div>
                                        </div>
                                        {{--رقم قيد اليوميه--}}
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.journal_code')</label>
                                                <input type="text" class="form-control" name="journal_hd_date" disabled
                                                       value="{{$journal_hd->journal_hd_code}}">
                                            </div>
                                        </div>
                                        {{--تاريخ اليوميه--}}
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.date')</label>
                                                <input type="date" class="form-control" name="journal_hd_date" disabled
                                                       value="{{$journal_hd->journal_hd_date}}">
                                            </div>
                                        </div>

                                        {{--رقم الملف--}}
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.file_serial')</label>
                                                <input type="number" class="form-control"
                                                       name="journal_file_no" disabled=""
                                                       value="{{ $journal_hd->journal_file_no }}">
                                            </div>
                                        </div>


                                        {{--حاله القيود اليوميه--}}
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.restriction_account_status')</label>
                                                <input type="text" class="form-control" value="{{ app()->getLocale()=='ar' ? $journal_hd->journalStatus->system_code_name_ar :
                                         $journal_hd->journalStatus->system_code_name_en}}" disabled>
                                            </div>
                                        </div>
                                        {{--المستخدم--}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.user')</label>
                                                <input type="text" class="form-control"
                                                       value="{{ app()->getLocale() == 'ar' ? $journal_hd->user->user_name_ar :
                                                $journal_hd->user->user_name_en }}" disabled="">
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12">
                                            <label>@lang('home.notes')</label>
                                            <textarea class="form-control" name="journal_hd_notes"
                                                      disabled>{{ $journal_hd->journal_hd_notes }} </textarea>
                                        </div>


                                    </div>
                                </div>

                                <div class="card">

                                    <div class="card-body">

                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead>
                                                <tr>
                                                    <th class="pl-0" style="width: 350px">@lang('home.account')</th>
                                                    <th></th>
                                                    <th colspan="2" style="width: 600px">@lang('home.notes')</th>
                                                    <th class="pr-0">@lang('home.debit')</th>
                                                    <th class="pr-0">@lang('home.credit')</th>
                                                    <th class="pr-0"></th>
                                                </tr>
                                                </thead>
                                                @foreach($journal_hd->journalDetails as $journal_dt)
                                                    <tr>
                                                        <td class="pl-0">
                                                            @if($journal_dt->account)
                                                                <input type="text" disabled="" class="form-control"
                                                                       value="{{app()->getLocale()=='ar' ?
                                                    $journal_dt->account->acc_name_ar :  $journal_dt->account->acc_name_en }}">
                                                            @endif
                                                        </td>

                                                        <td>
                                                            <button class="btn btn-link" type="button"
                                                                    style="font-weight: bold"
                                                                    data-toggle="modal"
                                                                    data-target="#exampleModalCenter{{$journal_dt->journal_dt_id}}">
                                                                <i class="fa fa-paperclip fa-2x"></i>
                                                            </button>
                                                        </td>

                                                        {{--الملاحظات--}}
                                                        <td colspan="2">
                                                            <input type="text" class="form-control"
                                                                   name="old_journal_dt_notes"
                                                                   value="{{$journal_dt->journal_dt_notes}}"
                                                                   disabled="">
                                                        </td>

                                                        {{--دائن--}}
                                                        <td class="pr-0">
                                                            <input type="number" class="form-control"
                                                                   value="{{$journal_dt->journal_dt_debit}}"
                                                                   disabled="">
                                                        </td>

                                                        {{--مدين--}}
                                                        <td class="pr-0">
                                                            <input type="number" class="form-control"
                                                                   value="{{$journal_dt->journal_dt_credit}}"
                                                                   disabled="">
                                                        </td>


                                                    </tr>
                                                @endforeach

                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>

                                                    <td>
                                                        <label>@lang('home.debit')</label>
                                                        <input type="text" name="journal_hd_debit" class="form-control"
                                                               value="{{$journal_hd->journal_hd_debit}}" disabled="">
                                                    </td>

                                                    <td>
                                                        <label>@lang('home.credit')</label>
                                                        <input type="text" name="journal_hd_credit" class="form-control"
                                                               value="{{$journal_hd->journal_hd_credit}}" disabled="">
                                                    </td>

                                                </tr>

                                                {{--form old--}}
                                                @foreach($journal_hd->journalDetails as $journal_dt)
                                                    <div class="modal fade bd-example-modal-lg"
                                                         id="exampleModalCenter{{ $journal_dt->journal_dt_id }}"
                                                         tabindex="-1" role="dialog"
                                                         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg modal-dialog-centered"
                                                             role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">
                                                                    </h5>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    {{--مركز التكلفه--}}
                                                                    <div class="row">
                                                                        @if($journal_dt->costCenterType)
                                                                            <input type="text" class="form-control"
                                                                                   value="{{app()->getLocale()=='ar' ?
                                                                        $journal_dt->costCenterType->system_code_name_ar :
                                                                        $journal_dt->costCenterType->system_code_name_en }}"
                                                                                   disabled="">
                                                                        @endif
                                                                    </div>

                                                                    @if($journal_dt->costCenterType->system_code == 56002)
                                                                        <div class="customers p-4 mt-1">
                                                                            <div class="row">
                                                                                {{--العملاء--}}
                                                                                <div class="col-md-4">
                                                                                    <div class="form-group">
                                                                                        <label for="recipient-name"
                                                                                               class="col-form-label">
                                                                                            @lang('home.debtors')
                                                                                            @lang('home.customer')</label>
                                                                                        <input type="text" disabled=""
                                                                                               class="form-control"
                                                                                               @if($journal_dt->customer) value="{{ app()->getLocale()=='ar' ?  $journal_dt->customer->customer_name_full_ar :
                                                                                 $journal_dt->customer->customer_name_full_en}}" @endif>
                                                                                    </div>
                                                                                </div>

                                                                                {{--امر شراء--}}
                                                                                @if($journal_dt->cost_center_id == 70 || $journal_dt->cost_center_id == 73)
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label for="recipient-name"
                                                                                                   class="col-form-label">
                                                                                                @lang('home.type')</label>
                                                                                            @if($journal_dt->cost_center_id == 70)
                                                                                                <input type="text"
                                                                                                       disabled=""
                                                                                                       class="form-control"
                                                                                                       value="@lang('home.waybill')">
                                                                                            @endif
                                                                                            @if($journal_dt->cost_center_id == 73)
                                                                                                <input type="text"
                                                                                                       disabled=""
                                                                                                       class="form-control"
                                                                                                       value="@lang('home.invoice')">
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                @endif

                                                                                @if($journal_dt->cost_center_id == 70 || $journal_dt->cost_center_id == 73)
                                                                                    {{--البوالص او الفواتير--}}
                                                                                    <div class="col-md-4">
                                                                                        <label class=""> @lang('home.bond')</label>

                                                                                        @if($journal_dt->cost_center_id == 70)
                                                                                            <input type="text"
                                                                                                   class="form-control"
                                                                                                   disabled=""
                                                                                                   value="{{ $journal_dt->costCenter->waybill_code .'///'.
                                                                                       $journal_dt->costCenter->waybill_total_amount  }}">
                                                                                        @endif

                                                                                        @if($journal_dt->cost_center_id == 73)
                                                                                            <input type="text"
                                                                                                   class="form-control"
                                                                                                   disabled=""
                                                                                                   value="{{ $journal_dt->costCenter->invoice_code .'///'.
                                                                                       $journal_dt->costCenter->invoice_no  }}">
                                                                                        @endif

                                                                                    </div>
                                                                                @endif
                                                                            </div>

                                                                        </div>
                                                                    @endif

                                                                    @if($journal_dt->costCenterType->system_code == 56001)
                                                                        <div class="suppliers  p-4 mt-1">
                                                                            <div class="row">
                                                                                {{--الموردين--}}
                                                                                <div class="col-md-4">
                                                                                    <label>@lang('home.suppliers')</label>
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           disabled=""
                                                                                           value="{{ app()->getLocale()=='ar' ?
                                                                             $journal_dt->supplier->customer_name_full_ar :
                                                                              $journal_dt->supplier->customer_name_full_en}}">
                                                                                </div>

                                                                                @if($journal_dt->cost_center_id == 70 || $journal_dt->cost_center_id == 73)
                                                                                    {{--امر شراء--}}
                                                                                    <div class="col-md-4">
                                                                                        <label>@lang('home.buy_command')</label>
                                                                                        @if($journal_dt->cost_center_id == 70)
                                                                                            <input type="text"
                                                                                                   disabled=""
                                                                                                   class="form-control"
                                                                                                   value="@lang('home.waybill')">
                                                                                        @endif
                                                                                        @if($journal_dt->cost_center_id == 73)
                                                                                            <input type="text"
                                                                                                   disabled=""
                                                                                                   class="form-control"
                                                                                                   value="@lang('home.invoice')">
                                                                                        @endif
                                                                                    </div>
                                                                                @endif

                                                                                {{--الفواتير او البوالص--}}
                                                                                @if($journal_dt->cost_center_id == 70 || $journal_dt->cost_center_id == 73)
                                                                                    <div class="col-md-4">
                                                                                        <label>@lang('home.number')</label>
                                                                                        @if($journal_dt->cost_center_id == 70)
                                                                                            <input type="text"
                                                                                                   class="form-control"
                                                                                                   disabled=""
                                                                                                   value="{{ $journal_dt->costCenter->waybill_code .'///'.
                                                                                       $journal_dt->costCenter->waybill_total_amount  }}">
                                                                                        @endif

                                                                                        @if($journal_dt->cost_center_id == 73)
                                                                                            <input type="text"
                                                                                                   class="form-control"
                                                                                                   disabled=""
                                                                                                   value="{{ $journal_dt->costCenter->invoice_code .'///'.
                                                                                       $journal_dt->costCenter->invoice_no  }}">
                                                                                        @endif

                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    @if($journal_dt->costCenterType->system_code == 56003)
                                                                        <div class="employees  p-4 mt-1">
                                                                            <div class="row">
                                                                                {{--الموظفين--}}
                                                                                <div class="col-md-4">
                                                                                    <div class="form-group">
                                                                                        <label for="recipient-name"
                                                                                               class="col-form-label">
                                                                                            @lang('home.employees')</label>
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               disabled="" value="{{app()->getLocale()=='ar' ?
                                                                                $journal_dt->employee->emp_name_full_ar :   $journal_dt->employee->emp_name_full_en}}">
                                                                                    </div>
                                                                                </div>

                                                                                {{--امر شراء--}}
                                                                                <div class="col-md-4">
                                                                                    <div class="form-group">
                                                                                        <label for="recipient-name"
                                                                                               class="col-form-label">
                                                                                            @lang('home.type')</label>
                                                                                        @if($journal_dt->cost_center_id == 70)
                                                                                            <input type="text"
                                                                                                   disabled=""
                                                                                                   class="form-control"
                                                                                                   value="@lang('home.waybill')">
                                                                                        @endif
                                                                                        @if($journal_dt->cost_center_id == 73)
                                                                                            <input type="text"
                                                                                                   disabled=""
                                                                                                   class="form-control"
                                                                                                   value="@lang('home.invoice')">
                                                                                        @endif
                                                                                    </div>
                                                                                </div>

                                                                                {{--البوليصه او الفاتوره--}}
                                                                                <div class="col-md-4">
                                                                                    <label> @lang('home.bond')</label>
                                                                                    @if($journal_dt->cost_center_id == 70)
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               disabled=""
                                                                                               value="{{ $journal_dt->costCenter->waybill_code .'///'.
                                                                                       $journal_dt->costCenter->waybill_total_amount  }}">
                                                                                    @endif

                                                                                    @if($journal_dt->cost_center_id == 73)
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               disabled=""
                                                                                               value="{{ $journal_dt->costCenter->invoice_code .'///'.
                                                                                       $journal_dt->costCenter->invoice_no  }}">
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    @if($journal_dt->costCenterType->system_code == 56004)
                                                                        <div class="cars p-4 mt-1">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           disabled=""
                                                                                           @if($journal_dt->truck)
                                                                                           value="{{ $journal_dt->truck->truck_name . $journal_dt->truck->truck_code}}"
                                                                                            @endif>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    @if($journal_dt->costCenterType->system_code == 56005)
                                                                        <div class="cars p-4 mt-1">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           disabled=""
                                                                                           @if($journal_dt->branch)
                                                                                           value="{{app()->getLocale()=='ar' ?
                                                                                    $journal_dt->branch->branch_name_ar :
                                                                                    $journal_dt->branch->branch_name_en }}"
                                                                                    @endif>>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    {{--<div class="branches p-4 mt-1" style="display: none">--}}
                                                                    {{--<div class="row">--}}
                                                                    {{--<select class="form-control"--}}
                                                                    {{--name="old_cc_branch_id"--}}
                                                                    {{--disabled=""--}}
                                                                    {{--v-model="journals[index]['cc_branch_id']">--}}
                                                                    {{--<option value="">@lang('home.choose')</option>--}}
                                                                    {{--<option :value="branch.branch_id"--}}
                                                                    {{--v-for="branch in branches">--}}
                                                                    {{--@if(app()->getLocale()=='ar')--}}
                                                                    {{--@{{ branch.branch_name_ar }}--}}
                                                                    {{--@else--}}
                                                                    {{--@{{ branch.branch_name_en }}--}}
                                                                    {{--@endif--}}
                                                                    {{--</option>--}}
                                                                    {{--</select>--}}
                                                                    {{--</div>--}}
                                                                    {{--</div>--}}

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                {{--end form--}}
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                </div>


                {{-- files part --}}
                <div class="tab-pane fade" id="files-grid" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-12">

                            <x-files.form>
                                <input type="hidden" name="transaction_id" value="{{ $journal_hd->journal_hd_id }}">
                                <input type="hidden" name="app_menu_id" value="33">
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
                                        <td>
                                            {{ app()->getLocale()=='ar' ?
                                         $attachment->attachmentType->system_code_name_ar :
                                          $attachment->attachmentType->system_code_name_en}}
                                        </td>
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
                                <input type="hidden" name="transaction_id" value="{{ $journal_hd->journal_hd_id }}">
                                <input type="hidden" name="app_menu_id" value="33">
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

                <div class="card-footer text-right">

                    <a href="{{config('app.telerik_server')}}?rpt={{$journal_hd->report_url_journal->report_url}}&id={{$journal_hd->journal_hd_id}}&lang=ar&skinName=bootstrap"
                       title="{trans('Print')}" class="btn btn-primary" id="showReport"
                       target="_blank">@lang('home.print')
                    </a>

                </div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')
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


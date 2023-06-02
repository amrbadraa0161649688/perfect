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

    <div class="section-body py-4" id="app">

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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="text-muted mb-4 text-center">@lang('invoice.invoice')</h2>

                                    <div class="card-options">
                                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                                    class="fe fe-chevron-up"></i></a>
                                        <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i
                                                    class="fe fe-maximize"></i></a>

                                        <div class="item-action dropdown ml-2">
                                            <a href="javascript:void(0)" data-toggle="dropdown"><i
                                                        class="fe fe-more-vertical"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">

                                                <a href="javascript:void(0)" class="dropdown-item"><i
                                                            class="dropdown-icon fa fa-share-alt"></i> Share </a>
                                                <a href="javascript:void(0)" class="dropdown-item">
                                                    <i class="dropdown-icon fa fa-cloud-download"></i> Download</a>


                                                <div class="dropdown-divider"></div>
                                                <a href="javascript:void(0)" class="dropdown-item"><i
                                                            class="dropdown-icon fa fa-copy"></i> Copy to</a>

                                                <a href="javascript:void(0)" class="dropdown-item"><i
                                                            class="dropdown-icon fa fa-trash"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <form>
                                        <div class="container-fluid">

                                            <div class="row">
                                                <div class="col-md-6 col-lg-9">

                                                    <div class="card">
                                                        <div class="row">
                                                            <div class="mt-5">
                                                                <div class="line color-red">

                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4 text-muted mb-2">
                                                                <h5> رقم الفاتورة :
                                                                    <span style="margin: 0 20px;">

                                                                    </span>
                                                                </h5>

                                                            </div>
                                                            <div class="col-md-4">

                                                                <input type="text" class="form-control" readonly
                                                                       value="{{$invoice->invoice_no }}">
                                                            </div>
                                                            <div class="col-md-4 text-muted mb-2 pull-right">
                                                                <div class="pull-left">
                                                                    <h5> : Invoice Number
                                                                        <span style="margin: 0 20px;">

                                                                        </span>
                                                                    </h5>
                                                                </div>


                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="mt-5">
                                                                <div class="line color-red">


                                                                </div>
                                                            </div>


                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4 text-muted mb-2">
                                                                <h5>  @lang('invoice.invoice_date')
                                                                    <span style="margin: 0 20px;">

                                                                    </span>
                                                                </h5>

                                                            </div>

                                                            <div class="col-md-4">

                                                                <input type="text" class="form-control" readonly
                                                                       value="{{$invoice->invoice_date }}">
                                                            </div>

                                                            <div class="col-md-4 text-muted mb-2">
                                                                <div class="pull-left">
                                                                    <h5> : Invoice Issue Date
                                                                        <span style="margin: 0 20px;">

                                                                        </span>
                                                                    </h5>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4 text-muted mb-2">

                                                                <h5> تاريخ التوريد :
                                                                    <span style="margin: 0 20px;">

                                                                        </span>
                                                                </h5>

                                                            </div>

                                                            <div class="col-md-4">

                                                                <input type="text" class="form-control" readonly
                                                                       value="{{$invoice->invoice_date }}">
                                                            </div>

                                                            <div class="col-md-4 text-muted mb-2">
                                                                <div class="pull-left">
                                                                    <h5> : Date Of Supply
                                                                        <span style="margin: 0 20px;">

                                                                        </span>
                                                                    </h5>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="mt-5">
                                                                <div class="line color-red">

                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-3">

                                                    <div class="card-body" style="center">
                                                        {!! QrCode::size(250)->generate("$invoice->qr_data") !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>@lang('invoice.customer')</label>
                                                <input type="text" class="form-control" readonly value="{{app()->getLocale()=='ar' ?
                                         $invoice->customer->customer_name_full_ar : $invoice->customer->customer_name_full_en }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label>@lang('invoice.supplier')</label>
                                                <input type="text" class="form-control" readonly value="{{app()->getLocale()=='ar' ?
                                         $invoice->company->company_name_ar : $invoice->company->company_name_en }}">
                                            </div>

                                        </div>


                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>@lang('invoice.address')</label>
                                                <input type="text" class="form-control" readonly
                                                       value="{{$invoice->customer->customer_address_1 }}">
                                            </div>


                                            <div class="col-md-6">
                                                <label>@lang('invoice.address')</label>
                                                <input type="text" class="form-control" readonly
                                                       value="{{$companies->company_address }}">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>@lang('invoice.vat_no')</label>
                                                <input type="text" class="form-control" readonly
                                                       value="{{$invoice->customer->customer_vat_no }}">
                                            </div>


                                            <div class="col-md-6">
                                                <label>@lang('invoice.vat_no')</label>
                                                <input type="text" class="form-control" readonly
                                                       value="{{$companies->company_tax_no }}">
                                            </div>


                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>@lang('invoice.notes')</label>
                                                <textarea type="text" class="form-control" readonly
                                                >{{$invoice->invoice_notes }}</textarea>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="table-responsive">
                                        <form action="{{ route('Invoices-acc.updateAccountsForInvoiceItems') }}"
                                              method="post">
                                            @csrf

                                            <table class="table text-nowrap mb-0">
                                                <thead>
                                                <tr>
                                                    <th>@lang('home.number')</th>

                                                    <th>@lang('invoice.item_type')</th>
                                                    <th>@lang('invoice.account')</th>
                                                    <th>@lang('invoice.item_unit')</th>
                                                    <th>@lang('invoice.item_qut')</th>
                                                    <th>@lang('invoice.item_price')</th>
                                                    <th>@lang('invoice.item_amount')</th>
                                                    <th>@lang('invoice.ratio')</th>
                                                    <th>@lang('invoice.vat')</th>
                                                    <th>@lang('invoice.total')</th>

                                                </tr>

                                                </thead>
                                                <tbody>
                                                @foreach($invoice->invoiceDetails as $k=>$invoice_detail)
                                                    <tr>
                                                        <input type="hidden" name="invoice_details_id[]"
                                                               value="{{$invoice_detail->invoice_details_id}}">
                                                        <td>{{ $k+1 }}</td>

                                                        <td>{{ app()->getLocale()=='ar' ? $invoice_detail->invoiceItemSetting->system_code_name_ar :
                                         $invoice_detail->invoiceItemSetting->system_code_name_en }}</td>

                                                        <td>
                                                            <select class="selectpicker" name="item_account_id[]"
                                                                    @if($invoice->journalHd) disabled @endif data-live-search="true">
                                                                <option value="">@lang('home.choose')</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{$account->acc_id}}"
                                                                            @if($invoice_detail->account)   @if($invoice_detail->account->acc_id == $account->acc_id)
                                                                            selected @endif @endif>
                                                                        {{app()->getLocale()=='ar' ? $account->acc_name_ar :
                                                                     $account->acc_name_en }}
                                                                        ===> {{$account->acc_code}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        <td>{{app()->getLocale()=='ar' ? $invoice_detail->invoiceItemUnit->system_code_name_ar :
                                                             $invoice_detail->invoiceItemUnit->system_code_name_en }}</td>
                                                        <td>{{ $invoice_detail->invoice_item_quantity }}</td>
                                                        <td>{{ number_format($invoice_detail->invoice_item_price,2) }}</td>
                                                        <td>{{ number_format($invoice_detail->invoice_item_amount,2) }}</td>
                                                        <td>{{ number_format($invoice_detail->invoice_item_vat_rate,2) }}</td>
                                                        <td>{{ number_format($invoice_detail->invoice_item_vat_amount,2) }}</td>
                                                        <td>{{ number_format($invoice_detail->invoice_total_amount,2) }}</td>

                                                    </tr>
                                                @endforeach

                                                </tbody>
                                            </table>

                                            <button type="submit" class="btn btn-primary">@lang('home.save')</button>

                                        </form>
                                    </div>

                                    <div class="mt-4">
                                        <div class="progress progress-xs">
                                            <div class="progress-bar bg-azure" style="width: 100%"></div>
                                        </div>
                                    </div>

                                    <div class="mt-1">
                                        <div class="line color-red">

                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-8  text-muted mb-2">
                                            <h6> @lang('invoice.amount_no_vat')
                                                <span style="margin: 0 1px;">

</span>
                                            </h6>

                                        </div>
                                        <div class="col-md-4">

                                            <input type="text" class="form-control" readonly
                                                   value="{{number_format($invoice->invoice_total - $invoice->invoice_vat_amount,2)  }}">
                                        </div>
                                    </div>

                                    <div class="mt-1">
                                        <div class="line color-red">

                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-8 text-muted mb-2">
                                            <h6>  @lang('invoice.vat_amount')
                                                ({{number_format($invoice->invoice_vat_rate,2) }} ٪ )
                                                <span style="margin: 0 5px;">

</span>
                                            </h6>

                                        </div>
                                        <div class="col-md-4">

                                            <input type="text" class="form-control" readonly
                                                   value="{{number_format($invoice->invoice_vat_amount,2) }}">
                                        </div>

                                    </div>
                                    <div class="mt-1">
                                        <div class="line color-red">

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8 text-muted mb-2">
                                            <h6> الاجمالي شامل ضريبة القيمة المضافة
                                                <span style="margin: 0 5px;">

</span>
                                            </h6>

                                        </div>

                                        <div class="col-md-4">

                                            <input type="text" class="form-control" readonly
                                                   value="{{$invoice->invoice_total}}">
                                        </div>
                                    </div>

                                    <div class="mt-1">
                                        <div class="line color-red">

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8 text-muted mb-2">
                                            <h6> اجمالي المدفوعـات
                                                <span style="margin: 0 5px;">

</span>
                                            </h6>

                                        </div>
                                        <div class="col-md-4">

                                            <input type="text" class="form-control" readonly
                                                   value="{{number_format($invoice->invoice_total_payment + $invoice->credit_invoice_discount,2)}}">
                                        </div>
                                    </div>

                                    <div class="mt-1">
                                        <div class="line color-red">

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8 text-muted mb-2">
                                            <h6> الاجمالي المستحق
                                                <span style="margin: 0 5px;">

</span>
                                            </h6>

                                        </div>
                                        <div class="col-md-4">

                                            <input type="text" class="form-control" readonly
                                                   value="{{number_format($invoice->invoice_total - $invoice->invoice_total_payment - $invoice->credit_invoice_discount,2)}} ">
                                        </div>
                                    </div>


                                    <div>

                                        @if($invoice->journalHd)
                                            <a href="{{route('journal-entries.show',$invoice->journalHd->journal_hd_id)}}"
                                               class="btn btn-primary" target="_blank">@lang('home.journal_code')
                                                : {{$invoice->journalHd->journal_hd_code}}</a>

                                        @else
                                            <form action="{{route('Invoices-acc.addInvoiceAccJournal',$invoice->invoice_id)}}"
                                                  method="post">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-primary btn-sm">@lang('home.add_journal')</button>

                                            </form>
                                        @endif

                                        {{--@if ($invoice->company_group = 13)--}}
                                        {{--<a--}}
                                        {{--href="{{config('app.telerik_server')}}?rpt=waqoodi/invoice_hd&id={{$invoice->invoice_id}}&lang=ar&skinName=highcontrast"--}}
                                        {{--title="{{trans('Print')}}"--}}
                                        {{--class="btn btn-circle btn-default red-flamingo" id="showReport"--}}
                                        {{--target="_blank">--}}
                                        {{--{{trans('Print')}}--}}
                                        {{--</a>--}}
                                        {{--@elseif ($invoice->company_group_id = 4)--}}

                                        {{--<a--}}
                                        {{--href="{{config('app.telerik_server')}}?rpt=nasil/invoice_print_hd_all&id={{$invoice->invoice_id}}&lang=ar&skinName=highcontrast"--}}
                                        {{--title="{{trans('Print')}}"--}}
                                        {{--class="btn btn-circle btn-default red-flamingo" id="showReport"--}}
                                        {{--target="_blank">--}}
                                        {{--{{trans('Print')}}--}}
                                        {{--</a>--}}

                                        {{--@else--}}


                                        {{--<a--}}
                                        {{--href="{{config('app.telerik_server')}}?rpt=perfect/invoice_print_hd&id={{$invoice->invoice_id}}&lang=ar&skinName=highcontrast"--}}
                                        {{--title="{{trans('Print')}}"--}}
                                        {{--class="btn btn-circle btn-default red-flamingo" id="showReport"--}}
                                        {{--target="_blank">--}}
                                        {{--{{trans('Print')}}--}}
                                        {{--</a>--}}
                                        {{--@endif--}}

                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- files part --}}
                <div class="tab-pane fade" id="files-grid" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-12">

                            <x-files.form>
                                <input type="hidden" name="transaction_id" value="{{ $invoice->invoice_id }}">
                                <input type="hidden" name="app_menu_id" value="106">
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
                                <input type="hidden" name="transaction_id" value="{{ $invoice->invoice_id }}">
                                <input type="hidden" name="app_menu_id" value="106">
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

@endsection

@section('scripts')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

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


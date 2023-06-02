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
    <div class="section-body py-4">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-md-12">

                    @include('Includes.flash-messages')
                    <div class="card">
                        <div class="card-header">
                            <h2 class="text-muted mb-4 text-center">@lang('invoice.add_invoice_credit')</h2>

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

                            <form action="{{ route('Invoices-acc.updateAccountsForInvoiceItems') }}"
                                  method="post">
                                @csrf

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
                                                        <h5> @lang('invoice.invoice_creat_no') :
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
                                                        <h5>  @lang('invoice.invoice_creat') :
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

                                {{--فواتير العميل--}}
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3"></div>
                                            @if(!$invoice->journalHdReturn)
                                                <div class="col-md-6">
                                                    <input type="hidden" name="invoice_id"
                                                           value="{{$invoice->invoice_id}}">
                                                    <p class="font-21 text-center text-bold">فواتير العميل</p>
                                                    <select class="selectpicker" data-live-search="true"
                                                            name="po_number"
                                                            @if($invoice->invoiceReturn) disabled @endif>
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach( $invoice->customer->invoices->where('invoice_type', '!=', 8) as $customer_invoice)
                                                            <option value="{{$customer_invoice->invoice_id}}"
                                                                    @if($invoice->invoiceReturn)
                                                                    @if($invoice->invoiceReturn->invoice_id == $customer_invoice->invoice_id)
                                                                    selected @endif @endif>{{$customer_invoice->invoice_no}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @else
                                                <div class="col-md-6">
                                                    <a href="{{ route('journal-entries.show',$invoice->journalHdReturn->journal_hd_id) }}"
                                                       class="btn btn-block btn-primary">
                                                        {{$invoice->journalHdReturn->journal_hd_code}}
                                                    </a>
                                                </div>
                                            @endif
                                            <div class="col-md-3"></div>
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

                                <div class="table-responsive">

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
                                            <input type="hidden" name="invoice_details_id[]"
                                                   value="{{$invoice_detail->invoice_details_id}}">
                                            <tr>
                                                <td>{{ $k+1 }}</td>

                                                <td>{{ app()->getLocale()=='ar' ? $invoice_detail->invoiceItemSetting->system_code_name_ar :
                                         $invoice_detail->invoiceItemSetting->system_code_name_en }}</td>

                                                <td>
                                                    <select class="selectpicker" name="item_account_id[]"
                                                            @if($invoice->journalHdReturn) disabled @endif
                                                            data-live-search="true" required>
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($accounts as $account)
                                                            <option value="{{$account->acc_id}}"
                                                                    @if($invoice_detail->account)  @if($invoice_detail->account->acc_id == $account->acc_id)
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


                                </div>
                                <button type="submit" class="btn btn-primary">@lang('home.save')</button>

                            </form>


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
                                           value="{{number_format($invoice->invoice_amount - $invoice->invoice_vat_amount,2)  }}">
                                </div>
                            </div>

                            <div class="mt-1">
                                <div class="line color-red">

                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-8 text-muted mb-2">
                                    <h6>  @lang('invoice.vat_amount')
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
                                           value="{{number_format($invoice->invoice_amount,2) }}">
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
                                           value="{{number_format($invoice->invoice_total_payment ,2)}}">
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
                                           value="{{number_format($invoice->invoice_amount - $invoice->invoice_total_payment,2)}} ">
                                </div>
                            </div>


                            <div>
                                @if($invoice->journalHdReturn)
                                    <a href="{{route('journal-entries.show',$invoice->journalHdReturn->journal_hd_id)}}"
                                       class="btn btn-primary" target="_blank">@lang('home.journal_code')
                                        : {{$invoice->journalHdReturn->journal_hd_code}}</a>

                                @else
                                    <form action="{{route('Invoices-credit.addInvoiceReturnJournal',$invoice->invoice_id)}}"
                                          method="post">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-primary btn-sm">@lang('home.add_journal')</button>

                                    </form>
                                @endif

                                {{--@if ($invoice->company_group = 13)--}}
                                {{--<a--}}
                                {{--href="{{config('app.telerik_server')}}?rpt=waqoodi/invoice_hd&id={{$invoice->invoice_id}}&lang=ar&skinName=highcontrast"--}}
                                {{--title="{{trans('Print')}}" class="btn btn-circle btn-default red-flamingo"--}}
                                {{--id="showReport" target="_blank">--}}
                                {{--{{trans('Print')}}--}}
                                {{--</a>--}}
                                {{--@elseif ($invoice->company_group_id = 4)--}}

                                {{--<a--}}
                                {{--href="{{config('app.telerik_server')}}?rpt=nasil/invoice_print_hd_all&id={{$invoice->invoice_id}}&lang=ar&skinName=highcontrast"--}}
                                {{--title="{{trans('Print')}}" class="btn btn-circle btn-default red-flamingo"--}}
                                {{--id="showReport" target="_blank">--}}
                                {{--{{trans('Print')}}--}}
                                {{--</a>--}}

                                {{--@else--}}


                                {{--<a--}}
                                {{--href="{{config('app.telerik_server')}}?rpt=perfect/invoice_print_hd&id={{$invoice->invoice_id}}&lang=ar&skinName=highcontrast"--}}
                                {{--title="{{trans('Print')}}" class="btn btn-circle btn-default red-flamingo"--}}
                                {{--id="showReport" target="_blank">--}}
                                {{--{{trans('Print')}}--}}
                                {{--</a>--}}
                                {{--@endif--}}

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
@endsection

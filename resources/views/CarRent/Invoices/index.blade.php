@extends('Layouts.master')
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

<style>
    .bootstrap-select {
        width: 100% !important;
    }
</style>
@section('content')

    <div class="section-body py-3">
        <div class="container-fluid">

            @include('Includes.form-errors')
            {{--  search part   --}}
            <div class="row mb-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" role="search">
                            <div class="row">

                            </div>

                            {{-- dates search --}}
                            <div class="row">

                                <div class="col-md-4">
                                    {{-- branches --}}
                                    <label>@lang('home.branches')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="branch_id[]" required>
                                        <option vaalue="">@lang('home.choose')</option>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->branch_id}}"
                                                    @if(session('branch')['branch_id'] == $branch->branch_id)
                                                    selected @endif
                                                    @if(request()->branch_id) @foreach(request()->branch_id as
                                                     $branch_id) @if($branch->branch_id == $branch_id) selected
                                                    @endif @endforeach @endif>
                                                {{app()->getLocale()=='ar' ? $branch->branch_name_ar :
                                                $branch->branch_name_en}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    {{-- customers  --}}
                                    <label>@lang('invoice.customer_name')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="customers_id[]">
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->customer_id }}"
                                                    @if(request()->customers_id) @foreach(request()->customers_id as
                                                     $customer_id) @if($customer->customer_id == $customer_id) selected @endif @endforeach @endif>
                                                {{app()->getLocale()=='ar' ? $customer->customer_name_full_ar
                                             : $customer->customer_name_full_en }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-4">
                                    {{-- status  --}}
                                    <label>@lang('invoice.invoice_status')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="statuses[]">

                                        <option value="121001"
                                                @if(request()->statuses) @foreach(request()->statuses as
                                                     $status) @if($status== 121001) selected @endif @endforeach @endif>
                                            @lang('invoice.ms_status')</option>

                                        <option value="121002"
                                                @if(request()->statuses) @foreach(request()->statuses as
                                                     $status) @if($status== 121002) selected @endif @endforeach @endif>
                                            @lang('invoice.mo_status')</option>

                                        <option value="121003"
                                                @if(request()->statuses) @foreach(request()->statuses as
                                                     $status) @if($status== 121003) selected @endif @endforeach @endif>
                                            @lang('invoice.inv_status')</option>


                                        <option value="121004"
                                                @if(request()->statuses) @foreach(request()->statuses as
                                                     $status) @if($status== 121004) selected @endif @endforeach @endif>
                                            @lang('invoice.paid_status')</option>

                                    </select>
                                </div>


                            </div>

                            <div class="row mt-3">

                                <div class="col-md-2">
                                    <label>@lang('invoice.invoice_from')</label>
                                    <input type="date" class="form-control" name="from_date"
                                           @if(request()->from_date) value="{{request()->from_date}}" @endif>
                                </div>

                                <div class="col-md-2">
                                    <label>@lang('invoice.invoice_to')</label>
                                    <input type="date" class="form-control" name="to_date"
                                           @if(request()->to_date) value="{{request()->to_date}}" @endif>
                                </div>

                                <div class="col-md-2">
                                    <label>@lang('home.due_date_from')</label>
                                    <input type="date" class="form-control" name="due_date_from"
                                           @if(request()->due_date_from) value="{{request()->due_date_from}}" @endif>
                                </div>

                                <div class="col-md-2">
                                    <label>@lang('home.due_date_to')</label>
                                    <input type="date" class="form-control" name="due_date_to"
                                           @if(request()->due_date_to) value="{{request()->due_date_to}}" @endif>
                                </div>

                                <div class="col-md-2">
                                    <label>@lang('invoice.invoice_no')</label>
                                    <input type="text" class="form-control" name="invoice_code"
                                           @if(request()->invoice_code) value="{{request()->invoice_code}}" @endif>
                                </div>


                                <div class="col-md-2">
                                    <button class="btn btn-primary mt-4" type="submit"><i
                                                class="fa fa-search"></i>@lang('home.search')
                                    </button>
                                </div>

                            </div>

                        </form>
                    </div>
                </div>

            </div>


            <div class="row clearfix">

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href="{{ route('car-rent.invoices.create') }}"
                                   class="btn btn-primary btn-sm text-white">{{ __('home.add_invoice') }}</a>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0"
                                       style="width:100%!important">
                                    <thead style="background-color: #ece5e7">
                                    <tr class="red" style="font-size: 16px;font-style: inherit">
                                        <th></th>
                                        <th>@if(app()->getLocale() == 'en')
                                                @sortablelink('invoice_no','Invoice Number') @else
                                                @sortablelink('invoice_no','رقم الفاتوره')  @endif</th>
                                        <th>
                                            @if(app()->getLocale() == 'en')
                                                @sortablelink('invoice_date','Invoice Date') @else
                                                @sortablelink('invoice_date','تاريخ الفاتوره')  @endif
                                        </th>
                                        <th>@lang('invoice.sub_company')</th>
                                        <th>
                                            @if(app()->getLocale() == 'en')
                                                @sortablelink('customer.customer_name_full_en','Customer Name') @else
                                                @sortablelink('customer.customer_name_full_ar',' اسم العميل
                                                ')  @endif</th>
                                        <th>@lang('invoice.invoice_due_date')</th>
                                        <th>@lang('invoice.invoice_total')</th>
                                        <th>@lang('invoice.invoice_payment')</th>
                                        <th>@lang('invoice.invoice_net')</th>
                                        <th>@lang('home.journal')</th>

                                        <th colspan="2"></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($invoices as $k=>$invoice)
                                        <tr>
                                            <td>{{ $k+1 }}</td>
                                            <td>{{ $invoice->invoice_no }}</td>
                                            <td>{{ $invoice->invoice_date }}</td>
                                            <td>{{ app()->getLocale()=='ar' ? $invoice->company->company_name_ar :
                                            $invoice->company->company_name_en }}</td>

                                            <td>{{app()->getLocale()=='ar' ? $invoice->customer->customer_name_full_ar :
                                     $invoice->customer->customer_name_full_en }}</td>
                                            <td>{{ $invoice->invoice_due_date }}</td>
                                            <td>{{number_format($invoice->invoice_amount,2 )}}</td>
                                            <td>{{number_format($invoice->invoice_total_payment,2 )}}</td>
                                            <td>{{number_format($invoice->invoice_amount - $invoice->invoice_total_payment,2 )}}</td>

                                            <td>
                                            <span class="tag tag-success">
                                                @if($invoice->invoice_status)
                                                    {{$invoice->invoicestatus ? $invoice->invoicestatus->system_code_name_ar :''}}

                                                @endif
                                                </span>
                                            </td>

                                            <td>@if($invoice->journalHdCars)
                                                    <a href="{{ route('journal-entries.show',$invoice->journalHdCars->journal_hd_id) }}"
                                                       class="btn btn-primary btn-sm">
                                                        @lang('home.journal_details')
                                                        {{$invoice->journalHdCars->journal_hd_code}}
                                                    </a>
                                                @endif
                                            </td>
                                            <td colspan="2">

                                                <a href="{{ route('car-rent.invoices.show',$invoice->invoice_id) }}"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.show')">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <a href="#"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.edit')">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                {{--<a href="{{ url('bonds-add/capture/create?invoice_id='.$invoice->invoice_id) }}"--}}
                                                {{--class="btn btn-primary btn-sm">--}}
                                                {{--<i class="fa fa-dollar"></i></a>--}}

                                                <a
                                                        href="{{config('app.telerik_server')}}?rpt={{$invoice->report_url_car->report_url}}&id={{$invoice->invoice_id}}&lang=ar&skinName=bootstrap"
                                                        title="{trans('Print')}" class="btn btn-primary btn-sm"
                                                        id="showReport" target="_blank">
                                                    <i class="fa fa-print"></i>
                                                </a>


                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>

                                <div class="row">
                                    <div class="col-md-6">
                                        {{ $invoices->appends($data)->links() }}
                                    </div>
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

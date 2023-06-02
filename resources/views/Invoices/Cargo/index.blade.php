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
    <div class="card-body">

        <div class="container-fluid">
            <div class="section-body mt-3" id="app">
                <div class="container-fluid">

                    @include('Includes.form-errors')
                    {{--  search part   --}}
                    <div class="row mb-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="" role="search">

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="font-25" bold>
                                                @lang('invoice.invoice_cargo')
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                    {{-- dates search --}}
                                    <div class="row">

                                    <div class="col-md-4">
                                            {{-- companies --}}
                                            <label>@lang('invoice.sub_company')</label>
                                            <select class="selectpicker" multiple data-live-search="true"  data-actions-box="true"
                                                    name="company_id[]" required>
                                                @foreach($companies as $company)
                                                    <option value="{{ $company->company_id }}"
                                                            @if(request()->company_id) @foreach(request()->company_id as
                                                     $company_id) @if($company->company_id == $company_id) selected @endif @endforeach @endif>
                                                        {{app()->getLocale()=='ar' ? $company->company_name_ar
                                                     : $company->company_name_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                       

                                        <div class="col-md-4">
                                            {{-- customers  --}}
                                            <label>@lang('invoice.customer_name')</label>
                                            <select class="selectpicker" multiple data-live-search="true"  data-actions-box="true"
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
                                            <select class="selectpicker" multiple data-live-search="true"  data-actions-box="true"
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

                                            <button class="btn btn-primary mt-4" type="submit">
                                            @lang('home.search')
                                                <i class="fa fa-search fa-fw"></i>
                                            </button>
                                        </div>

                                        <div hidden class="col-md-3">

                                            <a class="btn btn-primary" href="{{route('invoicesCargo2')}}"
                                            >@lang('home.cancel_filter')</a>
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
                                    @if($job_permission->app_menu_id == 91 && $job_permission->permission_add)
                                        <a href="{{route('Invoices.cargo.create')}}" class="btn btn-primary">
                                            <i class="fe fe-plus mr-2"></i>@lang('invoice.add_invoice')
                                        </a>
                                    @endif
                                @endforeach
                            @else
                                <a href="{{route('Invoices.cargo.create')}}" class="btn btn-primary">
                                    <i class="fe fe-plus mr-2"></i>@lang('invoice.add_invoice')
                                </a>
                            @endif
                        </div>
                    </div>


                    <div class="mt-3">
                        <div class="table-responsive">
                            <table class="table table-bordered bg-white">
                                <thead style="background-color: #ece5e7">
                                <tr class="red" style="font-size: 16px;font-style: inherit">
                                    <th></th>
                                    <th>@lang('invoice.invoice_no')</th>
                                    <th>@lang('invoice.invoice_date')</th>
                                    <th>@lang('invoice.sub_company')</th>
                                    <th>@lang('invoice.customer_name')</th>
                                    <th>@lang('invoice.invoice_due_date')</th>
                                    <th>@lang('invoice.invoice_total')</th>
                                    <th>@lang('home.journal')</th>
                                    <th>@lang('invoice.invoice_status')</th>
                                    <th>اشعار خصم</th>
                                    <th>@lang('home.action')</th>
                                </tr>
                                </thead>
                                <tbody>

                                
                                @foreach($invoices as $k=>$invoice)
                                    <tr>
                                        <td>{{ $k+1 }}</td>
                                        <td><a href="{{ route('invoices.Cargo.show',$invoice->invoice_id) }}"
                                                   class="btn btn-primary btn-sm text-nowrap">
                                                    {{ $invoice->invoice_no }}
                                                       </a></td>
                                        <td>{{ date('d-m-y', strtotime($invoice->invoice_date)) }}</td>
                                        <td>{{ app()->getLocale()=='ar' ? $invoice->company->company_name_ar :
                                            $invoice->company->company_name_en }}</td>

                                        <td>{{app()->getLocale()=='ar' ? $invoice->customer->customer_name_full_ar :
                                     $invoice->customer->customer_name_full_en }}</td>
                                        <td>{{date('d-m-y', strtotime( $invoice->invoice_due_date)) }}</td>
                                        <td>{{ number_format($invoice->invoice_amount,2) }}</td>
                                        
                                        <td>@if($invoice->journalHdCars)
                                                <a href="{{ route('journal-entries.show',$invoice->journalHdCars->journal_hd_id) }}"
                                                   class="btn btn-primary btn-sm text-nowrap">
                                                    {{$invoice->journalHdCars->journal_hd_code}}
                                                </a>
                                            @endif
                                        </td>
                                        
                                        <td>

                                        <span class="tag tag-success text-nowrap">
                                        @if($invoice->invoice_status)
                                        {{$invoice->invoicestatus ? $invoice->invoicestatus->system_code_name_ar :''}}

                                       @endif
                                        </span>

                                        </td>

                                        <td>
                                        @if($invoice->discountInvoice)
                                                <a href="{{route('invoices-credit.show',$invoice->invoice_id)}}"
                                                   class=" btn btn-sm btn-info text-nowrap">
                                                    {{$invoice->discountInvoice->invoice_no}}
                                                </a>

                                            @else
                                            <span class="tag tag-success text-nowrap"> 

                                                لا يوجد 
                                                </span>  
                                            @endif
                                        </td>
                                        <td>
                                        <a href="{{ route('Invoices.cargo.edit',$invoice->invoice_id) }}"
                                               class="btn btn-primary btn-sm text-nowrap"
                                               title="@lang('home.edit')">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                           
                                            <a
                                                    href="{{config('app.telerik_server')}}?rpt={{$invoice->report_url_cargo_smal_dt->report_url}}&id={{$invoice->invoice_id}}&lang=ar&skinName=bootstrap"
                                                    title="{trans('Print')}" class="btn btn-primary btn-sm"
                                                    id="showReport" target="_blank">
                                                <i class="fa fa-print"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="4"><span
                                                style="text-align:center;font-weight: bold">
                                            @lang('home.total') </span></td>
                                    <td colspan="4"><span
                                                style="text-align:center;font-weight: bold"> {{ $total_amount }}</span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            {{ $invoices->appends($data)->links() }}
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

    <script type="text/javascript">

        function show(el) {
            var x = el.id;
            $("#app-" + x).css("display", "block");
            $("#app-" + x).siblings().css('display', 'none')
        }
    </script>

@endsection

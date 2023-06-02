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

                                    <div class="row">

                                    </div>

                                    {{-- dates search --}}
                                    <div class="row">

                                        <div class="col-md-4">
                                            {{-- companies --}}
                                            <label>@lang('invoice.sub_company')</label>
                                            <select class="selectpicker" multiple data-live-search="true"
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

                                                <option value="1"
                                                        @if(request()->statuses) @foreach(request()->statuses as
                                                     $status) @if($status== 1) selected @endif @endforeach @endif>
                                                    @lang('home.paid')</option>

                                                <option value="0"
                                                        @if(request()->statuses) @foreach(request()->statuses as
                                                     $status) @if($status== 0) selected @endif @endforeach @endif>
                                                    @lang('home.un_paid')</option>

                                                <option value="2"
                                                        @if(request()->statuses) @foreach(request()->statuses as
                                                     $status) @if($status== 2) selected @endif @endforeach @endif>
                                                    @lang('home.shifted')</option>


                                                <option value="3"
                                                        @if(request()->statuses) @foreach(request()->statuses as
                                                     $status) @if($status== 3) selected @endif @endforeach @endif>
                                                    @lang('home.in_shifted')</option>

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


                                        <div class="col-md-3">
                                            <button class="btn btn-primary mt-4" type="submit"><i
                                                        class="fa fa-search"></i>@lang('home.search')
                                            </button>
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
                                    @if($job_permission->app_menu_id == 108 && $job_permission->permission_add)
                                        <a href="{{route('Invoices-debit.create')}}" class="btn btn-primary">
                                            <i class="fe fe-plus mr-2"></i>@lang('invoice.add_invoice_debit')
                                        </a>
                                    @endif
                                @endforeach
                            @else
                                <a href="{{route('Invoices-debit.create')}}" class="btn btn-primary">
                                    <i class="fe fe-plus mr-2"></i>@lang('invoice.add_invoice_debit')
                                </a>
                            @endif
                        </div>
                        <div class="col-md-7">

                        </div>

                        <div class="col-md-2">
                            <form action="{{route('invoices-exportdebit') }}">
                                @if(request()->company_id)
                                    @foreach(request()->company_id as $company_id)
                                        <input type="hidden" name="company_id[]" value="{{ $company_id }}">
                                    @endforeach
                                @endif
                                @if (request()->from_date && request()->to_date)
                                    <input type="hidden" name="created_date_from"
                                           value="{{request()->from_date}}">
                                    <input type="hidden" name="to_date" value="{{request()->to_date}}">
                                @endif

                                @if (request()->customers_id)
                                    @foreach(request()->customers_id as $customer_id)
                                        <input type="hidden" name="customers_id[]" value="{{$customer_id}}">
                                    @endforeach
                                @endif

                                @if (request()->statuses)
                                    @foreach(request()->statuses as $status_id)
                                        <input type="hidden" name="statuses[]" value="{{$status_id}}">
                                    @endforeach
                                @endif

                                @if (request()->due_date_from && request()->due_date_to)
                                    <input type="hidden" name="due_date_from"
                                           value="{{request()->due_date_from}}">
                                    <input type="hidden" name="due_date_to"
                                           value="{{request()->due_date_to}}">
                                @endif

                                <button href="{{ route('invoices-exportdebit') }}" type="submit"
                                        class="btn btn-primary">@lang('home.export')
                                    <i class="fa fa-file-excel-o"></i></button>
                            </form>
                        </div>
                    </div>


                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0" style="width:100%!important">
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
                                            @sortablelink('customer.customer_name_full_ar',' اسم العميل ')  @endif</th>
                                    <th>@lang('invoice.ref_number')</th>
                                    <th>@lang('invoice.invoice_total')</th>
                                    <th>@lang('invoice.invoice_payment')</th>
                                    <th>@lang('invoice.invoice_net')</th>
                                    <th>@lang('invoice.invoice_status')</th>
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
                                       
                                        <td>
                                        {{optional( $invoice->Waybilltickno)->waybill_ticket_no}}
                                        </td>
                                        <td>{{number_format($invoice->invoice_amount,2 )}}</td>
                                        <td>{{number_format($invoice->invoice_total_payment,2 )}}</td>
                                        <td>{{number_format($invoice->invoice_amount - $invoice->invoice_total_payment,2 )}}</td>
                                        <td>{{ $invoice->invoice_is_payment }}</td>
                                        <td colspan="2">

                                                <a href="{{ route('invoices-debit.show',$invoice->invoice_id) }}"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.show')">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ url('bonds-add/capture/create?invoice_id='.$invoice->invoice_id) }}" class="btn btn-primary btn-sm">
                                                    @lang('home.add_bond')</a>

                                                   
    
                                            <a
                                            href="{{config('app.telerik_server')}}?rpt={{$invoice->report_url_debit->report_url}}&id={{$invoice->invoice_id}}&lang=ar&skinName=bootstrap"
                                                    title="{trans('Print')}" class="btn btn-primary btn-sm" id="showReport" target="_blank">
                                                    {{trans('Print')}}
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

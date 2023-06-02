@extends('Layouts.master2')

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
                        <div class="col-md-3">


                            @if(auth()->user()->user_type_id != 1)
                                @foreach(session('job')->permissions as $job_permission)
                                    @if($job_permission->app_menu_id == 89 && $job_permission->permission_add)
                                        <a href="{{route('Invoices.sales.create')}}" class="btn btn-primary">
                                            <i class="fe fe-plus mr-2"></i>@lang('invoice.add_invoice')
                                        </a>
                                    @endif
                                @endforeach
                            @else
                                <a href="{{route('Invoices.sales.create')}}" class="btn btn-primary">
                                    <i class="fe fe-plus mr-2"></i>@lang('invoice.add_invoice')
                                </a>
                            @endif
                        </div>
                    </div>


                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="width:100%!important">
                                <thead style="background-color: #ece5e7">
                                <tr class="red" style="font-size: 16px;font-style: inherit">
                                    <th></th>
                                    <th >@lang('invoice.invoice_no')</th>
                                    <th>@lang('invoice.invoice_date')</th>

                                    <th>@lang('invoice.invoice_total')</th>

                                    <th>@lang('home.action')</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($invoices as $k=>$invoice)
                                    <tr>
                                        <td>{{ $k+1 }}</td>
                                        <td>{{ $invoice->invoice_no }}</td>
                                        <td>{{date('H:i d-m',strtotime( $invoice->invoice_date) )}}</td>

                                        <td>{{ number_format($invoice->invoice_amount,2) }}</td>
                                        <td>
                                     {{--   <a href="{{ route('invoices.sales.show',$invoice->invoice_id) }}"
                                               class="btn btn-icon js-sweetalert"
                                               title="@lang('home.show')">
                                                <i class="fa fa-eye text-danger"></i>
                                            </a>--}}
                                        
                                            @if(app()->getLocale()=='ar')


                                            <a
                                            href="{{config('app.telerik_server')}}?rpt={{$invoice->report_sample->report_url}}&id={{$invoice->invoice_id}}&lang=ar&skinName=bootstrap"
                                                    title="{trans('Print')}" class="btn btn-primary btn-sm" id="showReport" target="_blank">
                                                    {{trans('Print')}}
                                         </a>
                                         @else
                                            <a
                                            href="{{config('app.telerik_server')}}?rpt=perfect/invoice_sample&id={{$invoice->invoice_id}}&lang=ar&skinName=bootstrap"
                                                    title="{trans('Print')}" class="btn btn-primary btn-sm" id="showReport" target="_blank">
                                                    {{trans('Print')}}
                                         </a>
                                         @endif
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

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

                                    </div>

                                   

                                </form>
                            </div>
                        </div>

                    </div>


                    

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0" style="width:100%!important">
                                <thead style="background-color: #ece5e7">
                                <tr class="red" style="font-size: 16px;font-style: inherit">
                                    <th></th>
                                    <th>@if(app()->getLocale() == 'en')
                                            @sortablelink('invoice_no','id') @else
                                            @sortablelink('invoice_no','الكود')  @endif</th>
                                    <th>
                                        @if(app()->getLocale() == 'en')
                                            @sortablelink('invoice_date',' Date') @else
                                            @sortablelink('invoice_date','الشهـــر')  @endif
                                    </th>
                                    <th>@lang('invoice.period_name')</th>
                                    <th>
                                        @if(app()->getLocale() == 'en')
                                            @sortablelink('customer.customer_name_full_en','Company Name') @else
                                            @sortablelink('customer.customer_name_full_ar',' اسم الشركه ')  @endif</th>
                                
                                    <th>@lang('invoice.inv_serial')</th>
                                    <th>@lang('invoice.inv_r_serial')</th>
                                    <th>@lang('invoice.period_rwateb')</th>
                                    <th>@lang('invoice.period_status')</th>
                                    <th colspan="2"></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($periods as $k=>$period)
                                    <tr>
                                        <td>{{ $k+1 }}</td>
                                        <td>{{ $period->acc_period_id }}</td>
                                        <td>{{ $period->acc_period_month }}</td>
                                        

                                        <td>{{ $period->acc_period_name_ar }}</td>
                                        <td>{{ app()->getLocale()=='ar' ? $period->company->company_name_ar :
                                            $period->company->company_name_en }}</td>

                                            <td>{{ $period->acc_invoice_serial }}</td>
                                            <td>{{ $period->acc_invoice_disc_serial }}</td>  
                                            <td> @if($period->acc_period_is_payroll == 0) 
                                            <span class="tag tag-danger">@lang('invoice.rwateb_opend')</span>
                                                @else
                                             <span class="tag tag-success"> @lang('invoice.rwateb_closes')</span>

                                                @endif
                                            </td>
                                     
                                           
                                            <td>@if($period->acc_period_is_active == 1) <i class="fa fa-check"></i> @else <i
                                                class="fa fa-remove"></i> @endif</td>
                                                <td>
                                            
                                        <td colspan="2">

                                                <a href="{{ route('accounts.storeperiod',$period->acc_period_id) }}"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.show')">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                              
                                                 
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

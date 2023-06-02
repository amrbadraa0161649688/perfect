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

{{--------------------------------تقادير مبيعات السيارات  -------------------}}
    <div class="container-fluid">


        <div class="section-body mt-3" id="app">
            <div class="container-fluid">

                @include('Includes.form-errors')

                <div class="col-md-12">
                            <div class="row">
                                
                                <div class="col-md-3">
                                    <div class="font-25">
                                        @lang('reports.salescar_report')
                                    </div>
                                </div>
                            </div>
                        </div>

                <form action="">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                {{--الشركات--}}
                                <div hidden class="col-md-4">
                                    <label>@lang('home.companies')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="company_id[]">
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

                                <div class="col-md-4">
                                    {{-- الفروع   --}}
                                    <label>@lang('reports.branch_select')</label>
                                    <select class="selectpicker"  multiple data-live-search="true"    data-actions-box="true"
                                            name="branchs_id[]" id="branchs_id" >
                                        @foreach($branch_lits as $branch_lit)
                                            <option value="{{ $branch_lit->branch_id }}">
                                             {{app()->getLocale()=='ar' ? $branch_lit->branch_name_ar
                                             : $branch_lit->branch_name_en }}</option>
                                        @endforeach
                                    </select>

                                    </div> 

                                    {{--المستودعات--}}
                                <div class="col-md-4">
                                   
                                    <label>@lang('reports.store_lits')</label>
                                    <select class="selectpicker"  data-live-search="true" 
                                            name="store_id" id="store_id" > 
                                        @foreach($store_lits as $store_lit)
                                            <option value="{{ $store_lit->system_code_id }}" >  
                                               @if(app()->getLocale()=='ar') 
                                               {{$store_lit->system_code_name_ar}}
                                               @else
                                             {{$store_lit->system_code_name_en}}
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                 </div>    
                                <div class="row">

                                {{--العملاء--}}
                                <div class="col-md-4">
                                    {{-- customers  --}}
                                    <label>@lang('reports.customer_select')</label>
                                    <select class="selectpicker" multiple data-live-search="true"  data-actions-box="true"
                                            name="customers_ids[]" id='customers_ids'>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->customer_id }}"
                                                   >
                                                {{app()->getLocale()=='ar' ? $customer->customer_name_full_ar
                                             : $customer->customer_name_full_en }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--الحالات--}}
                                <div class="col-md-4">
                                    {{-- customers  --}}
                                    <label>@lang('reports.items')</label>
                                    <select class="selectpicker"  multiple data-live-search="true"  data-actions-box="true"
                                            name="status_id[]" name="status_id[]">
                                        @foreach($status_lits as $status_lits)
                                            <option value="{{ $status_lits->system_code_id }}"
                                            >  
                                               @if(app()->getLocale()=='ar') 
                                               {{$status_lits->system_code_name_ar}}
                                               @else
                                               {{$status_lits->system_code_name_en}}
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{--الموقع--}}
                                <div hidden class="col-md-4">
                                    {{-- customers  --}}
                                    <label>@lang('reports.location')</label>
                                    <input type="text" class="form-control" 
                                            name="location">
                                       
                                    
                                </div>

                            </div>

                            <div class="row">

                            <div class="col-md-4">
                                    {{-- اختيار التقرير   --}}
                                    <label>@lang('reports.report_select')</label>
                                    <select class="selectpicker"  data-live-search="true"
                                            name="report_id">
                                        @foreach($report_acc_lits as $report_acc_lit)
                                            <option value="{{ $report_acc_lit->system_code }}"
                                                    @if(request()->statuses_id) @foreach(request()->statuses_id as
                                                     $status_id) @if($report_acc_lit->system_code == $status_id)
                                                    selected @endif @endforeach @endif>
                                                {{app()->getLocale()=='ar' ? $report_acc_lit->system_code_name_ar
                                             : $report_acc_lit->system_code_name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{--طرق الدفع--}}
                                <div  class="col-md-4">
                                    {{-- اختيار طريقة الدفع   --}}
                                    <label>@lang('reports.bond_type_select')</label>
                                    <select class="selectpicker" multiple data-live-search="true"  data-actions-box="true"
                                            name="bond_type_id[]" id="bond_type_id"  >
                                        @foreach($bond_type_lits as $bond_type_lit)
                                            <option value="{{ $bond_type_lit->system_code }}">
                                                   
                                                {{app()->getLocale()=='ar' ? $bond_type_lit->system_code_name_ar
                                             : $bond_type_lit->system_code_name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>





                                <div hidden class="col-md-4">
                                    {{-- اختيار الحساب   --}}
                                    <label>@lang('reports.account_select')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="account_id[]">
                                        @foreach($accountL as $accountLs)
                                            <option value="{{ $accountLs->acc_id }}"
                                                    @if(request()->statuses_id) @foreach(request()->statuses_id as
                                                     $status_id) @if($accountLs->acc_id == $status_id)
                                                    selected @endif @endforeach @endif>
                                                {{app()->getLocale()=='ar' ? $accountLs->acc_name_ar
                                             : $accountLs->acc_name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            
                            <div class="row">

                                {{--تاريخ اانشاء من والي--}}
                                <div class="col-md-4">
                                    <label>@lang('reports.from_date')</label>
                                    <input type="datetime-local" class="form-control" name="created_date_from"
                                           @if(request()->created_date_from) value="{{request()->created_date_from}}"
                                            @endif>
                                </div>
                                <div class="col-md-4">
                                    <label>@lang('reports.to_date')</label>
                                    <input type="datetime-local" class="form-control" name="created_date_to"
                                           @if(request()->created_date_to) value="{{request()->created_date_to}}" @endif>
                                </div>

                                {{--تاريخ   من والي--}}
                                <div hidden class="col-md-2">
                                    <label>@lang('home.expected_date_from')</label>
                                    <input type="date" class="form-control" name="expected_date_from"
                                           @if(request()->expected_date_from) value="{{request()->expected_date_from}}"
                                            @endif>
                                </div>
                                <div hidden class="col-md-2">
                                    <label>@lang('home.expected_date_to')</label>
                                    <input type="date" class="form-control" name="expected_date_to"
                                           @if(request()->expected_date_to) value="{{request()->expected_date_to}}" @endif>
                                </div>

                                <div hidden class="col-md-2">
                                    <button class="btn btn-primary mt-4" type="submit">@lang('home.search')
                                        <i class="fa fa-search"></i></button>
                                </div>


                                <div class="col-md-4" hidden>
                                    @if(session('company_group'))
                                        <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                                        {{ session('company_group')['company_group_ar'] }} @else
                                        {{ session('company_group')['company_group_en']}} @endif" readonly>
                                    @else
                                        <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                                        {{ auth()->user()->companyGroup->company_group_ar }} @else
                                        {{ auth()->user()->companyGroup->company_group_en }} @endif" readonly>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                            <button class="btn btn-primary mt-4" type="submit">                                           
                                                @lang('home.search')
                                                <i class="fa fa-search fa-fw"></i>
                                            </button>
                                           
                                        </div> 

                            </div>


                        </div>
                    </div>
            </form>
        </div>

       



  
    
                <div class="card-footer row">
                 
               
                            {{--تقرير المشتريات --}}
                            @if ( request()->report_id == 103001) 
                            @foreach($report_url_ins as $report_url_ins_rs)  
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                            <a href="{{config('app.telerik_server')}}?rpt={{$report_url_ins_rs->report_url}}&id={{implode(',',request()->input('branchs_id',[]))}}&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&report_type=104003&store_id={{request()->store_id}}&cus_id={{implode(',',request()->input('customers_ids',[]))}}&pay_id={{implode(',',request()->input('bond_type_id',[]))}}&lang=ar&skinName=bootstrap"
                                                class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                                >@lang('home.print')</a>
                            </div>
                            @endforeach 
                            @endif 
                             
                            {{--تقرير المبيعات --}} 
                            @if ( request()->report_id == 103002) 
                            @foreach($report_url_inv as $report_url_inv_rs) 
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                            <a href="{{config('app.telerik_server')}}?rpt={{$report_url_inv_rs->report_url}}&id={{implode(',',request()->input('branchs_id',[]))}}&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&report_type=104005&store_id={{request()->store_id}}&cus_id={{implode(',',request()->input('customers_ids',[]))}}&pay_id={{implode(',',request()->input('bond_type_id',[]))}}&lang=ar&skinName=bootstrap"
                                                class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                               >@lang('home.print')</a>
                            </div>
                            @endforeach
                            @endif    
                             
                            {{--تقرير مرتجع المشتريات --}}
                            @if ( request()->report_id == 103003) 
                            @foreach($report_url_ins as $report_url_ins_rs)  
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                            <a href="{{config('app.telerik_server')}}?rpt={{$report_url_ins_rs->report_url}}&id={{implode(',',request()->input('branchs_id',[]))}}&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&report_type=104007&store_id={{request()->store_id}}&cus_id={{implode(',',request()->input('customers_ids',[]))}}&pay_id={{implode(',',request()->input('bond_type_id',[]))}}&lang=ar&skinName=bootstrap"
                                                class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                                >@lang('home.print')</a>
                            </div>
                            @endforeach 
                            @endif 
                             
                            {{--تقرير مرتجع  المبيعات --}} 
                            @if ( request()->report_id == 103004) 
                            @foreach($report_url_inv as $report_url_inv_rs) 
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                            <a href="{{config('app.telerik_server')}}?rpt={{$report_url_inv_rs->report_url}}&id={{implode(',',request()->input('branchs_id',[]))}}&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&report_type=104006&store_id={{request()->store_id}}&cus_id={{implode(',',request()->input('customers_ids',[]))}}&pay_id={{implode(',',request()->input('bond_type_id',[]))}}&lang=ar&skinName=bootstrap"
                                                class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                               >@lang('home.print')</a>
                            </div>
                            @endforeach
                            @endif    
                              
                            {{--تقرير   كارت صنف  --}} 
                            @if ( request()->report_id == 103005) 
                            @foreach($report_url_item as $report_url_item_rs) 
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{config('app.telerik_server')}}?rpt={{$report_url_item_rs->report_url}}&branch_id={{implode(',',request()->input('branchs_id',[]))}}&store_id={{request()->store_id}}&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&item_id={{request()->item_id}}&lang=ar&skinName=bootstrap"
                                                class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                               >@lang('home.print')</a>
                            </div>
                            @endforeach
                            @endif    
                    
                            {{--تقرير   ارصدة الاصناف  --}} 
                            @if ( request()->report_id == 103006) 
                            @foreach($report_url_items as $report_url_items_rs) 
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{config('app.telerik_server')}}?rpt={{$report_url_items_rs->report_url}}&id={{implode(',',request()->input('branchs_id',[]))}}&store_id={{request()->store_id}}&pay_id={{implode(',',request()->input('status_id',[]))}}&lang=ar&skinName=bootstrap"
                                                class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                               >@lang('home.print')</a>
                            </div>
                            @endforeach
                            @endif    

                            {{--تقرير   ارصدة الاصناف بالموقع --}} 
                            @if ( request()->report_id == 103009) 
                            @foreach($report_url_location as $report_url_location_rs) 
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{config('app.telerik_server')}}?rpt={{$report_url_location_rs->report_url}}&branch_id={{implode(',',request()->input('branchs_id',[]))}}&store_id={{request()->store_id}}&location={{request()->location}}&lang=ar&skinName=bootstrap"
                                                class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                               >@lang('home.print')</a>
                            </div>
                            @endforeach
                            @endif 
                            
                            {{--تقرير فواتير الحساب --}} 
                            @if ( request()->report_id == 103097) 
                            @foreach($report_url_97 as $report_url_979) 
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{config('app.telerik_server')}}?rpt={{$report_url_979->report_url}}&id={{implode(',',request()->input('customers_ids',[]))}}&&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&lang=ar&skinName=bootstrap"
                                                class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                               >@lang('home.print')</a>
                            </div>
                            @endforeach
                            @endif    

                            {{--تقرير فواتير استحقاقات العملاء --}} 
                            @if ( request()->report_id == 103098) 
                            @foreach($report_url_98 as $report_url_989) 
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{config('app.telerik_server')}}?rpt={{$report_url_989->report_url}}&&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&branch_id={{implode(',',request()->input('branchs_id',[]))}}&lang=ar&skinName=bootstrap"
                                                class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                               >@lang('home.print')</a>
                            </div>
                            @endforeach
                            @endif  


                            {{--تقرير فواتير استحقاقات الموردين --}} 
                            @if ( request()->report_id == 103099) 
                            @foreach($report_url_99 as $report_url_999) 
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{config('app.telerik_server')}}?rpt={{$report_url_999->report_url}}&&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&branch_id={{implode(',',request()->input('branchs_id',[]))}}&lang=ar&skinName=bootstrap"
                                                class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                               >@lang('home.print')</a>
                            </div>
                            @endforeach
                            @endif  


                    <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">	
                        <a href="{{ route('home') }}" class="btn btn-secondary btn-block"> @lang('storeItem.back_button') </a>
                    </div>
                  
                    </div>
               
           
            
             


            <div class="card-body">


             
                    
               
               
            </div>

        
    </div>

@endsection

@section('scripts')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/multiple-select@1.3.1/dist/multiple-select.min.js"></script>
    <script src="{{asset('jquery-datetime\jquery.datetimepicker.full.min.js')}}"></script>
   
    <script>
        $(function(){
            $('.datepicker').datetimepicker({
                format:'Y/m/d H:i',
            });
           
            var branchSelect = $('#branchSelect')

                    branchSelect.html(ops.join(''));
                    branchSelect.multipleSelect('refresh');
                });
            });

            $('.select').multipleSelect({
                minimumCountSelected:6,
                formatSelectAll:function(){
                    return 'إختار الكل';
                },
                formatAllSelected:function(){
                    return 'الكل';
                },
                container: '.outer',
                filter:true
            });

            var showReportBtn = $('#showReport');
            var originalHref = showReportBtn.prop('href');
            
            showReportBtn.on('click',function(e){
               
                
                var report_id = $('#report_id').find(':selected');
                var fromSelect = $('#created_date_from');
                var toSelect = $('#created_date_to');
              //  var bond_type_id = $('#bond_type_id');
                var href = originalHref;
                // var employee = $('#employee');
                var branch = $('#branchSelect');
                href = href.replace('$R$',$('#report_id').val());
                href = href.replace('$FM$',fromSelect.val());
                href = href.replace('$TD$',toSelect.val());
                href = href.replace('$BR$',$('#branchSelect').val().join(','));
                href = href.replace('$USR$',$('#user').val().join(','));
             //   href = href.replace('$bondtype$',$('#bond_type_id').val().join(','));
                // href = href.replace('$TY$',toSelect.data('y'));
                // href = href.replace('$FILTER$',"branch="+$('#branchSelect').val().join(','));
                showReportBtn.prop('href',href);
                return true;

            });

        });
    </script>
@endpush
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

{{--------------------------------تقادير السندات المحاسبيه -------------------}}
    <div class="container-fluid">


        <div class="section-body mt-3" id="app">
            <div class="container-fluid">

                @include('Includes.form-errors')

                <div class="col-md-12">
                            <div class="row">
                                
                                <div class="col-md-3">
                                    <div class="font-25">
                                        @lang('reports.bond_report')
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

                                @if($flag==0) 
                                <div hidden class="col-md-4">
                                    {{-- الفروع   --}}
                                   
                                    <label>@lang('reports.branch_select')</label>
                                   
                                    <select class="selectpicker"  data-live-search="true"  data-actions-box="true"
                                            name="branch_id" id="branch_id" >
                                            @foreach($branch_lits as $branch_lit)
                                                    <option value="{{ $branch_lit->branch_id }}"
                                                            @if($flag==0) @if(session('branch')['branch_id'] == $branch_lit->branch_id ) selected
                                                            @endif @endif
                                                            value="{{ $branch_lit->branch_id }}">
                                                            {{ app()->getLocale()=='ar' ?
                                                     $branch_lit->branch_name_ar : $branch_lit->branch_name_en }}</option>
                                               
                                                     @endforeach
                                      
                                    </select>
                                </div>
                                @else
                                <div  class="col-md-4">
                                    {{-- الفروع   --}}
                                   
                                    <label>@lang('reports.branch_select')</label>
                                   
                                    <select class="selectpicker"  data-live-search="true"  data-actions-box="true"
                                            name="branch_id" id="branch_id" >
                                            @foreach($branch_lits as $branch_lit)
                                                    <option value="{{ $branch_lit->branch_id }}"
                                                           >
                                                            {{ app()->getLocale()=='ar' ?
                                                     $branch_lit->branch_name_ar : $branch_lit->branch_name_en }}</option>
                                               
                                                     @endforeach
                                      
                                    </select>
                                </div>
                                @endif

                               
                         </div>    
                                <div class="row">

                                {{--العملاء--}}
                                <div class="col-md-4">
                                    {{-- customers  --}}
                                    <label>@lang('reports.customer_select')</label>
                                    <select class="selectpicker"  data-live-search="true"
                                            name="customers_id" id="customers_id">
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->customer_id }}"
                                                   >
                                                {{app()->getLocale()=='ar' ? $customer->customer_name_full_ar
                                             : $customer->customer_name_full_en }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div  class="col-md-4">
                                    {{-- اختيار نوع الصرف   --}}
                                    <label>@lang('reports. ')</label>
                                    <select class="selectpicker" multiple data-live-search="true"  data-actions-box="true"
                                            name="bond_type_s_id[]" id="bond_type_s_id"  >
                                        @foreach($bond_type_s as $bond_type_ss)
                                            <option value="{{ $bond_type_ss->system_code_id }}">
                                                   
                                                {{app()->getLocale()=='ar' ? $bond_type_ss->system_code_name_ar
                                             : $bond_type_ss->system_code_name_en }}</option>
                                        @endforeach
                                    </select>
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
                                            >
                                                {{app()->getLocale()=='ar' ? $report_acc_lit->system_code_name_ar
                                             : $report_acc_lit->system_code_name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>

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

                            </div>

                            <div class="row">

                                {{--تاريخ اانشاء من والي--}}
                                <div class="col-md-4">
                                    <label>@lang('reports.from_date')</label>
                                    <input type="date" class="form-control" name="created_date_from"
                                           @if(request()->created_date_from) value="{{request()->created_date_from}}"
                                            @endif>
                                </div>
                                <div class="col-md-4">
                                    <label>@lang('reports.to_date')</label>
                                    <input type="date" class="form-control" name="created_date_to"
                                           @if(request()->created_date_to) value="{{request()->created_date_to}}" @endif>
                                </div>

                                

                                {{--تاريخ التوصيل المتوقع من والي--}}
                                <div hidden class="col-md-2">
                                    <label>@lang('home.expected_date_from')</label>
                                    <input type="date" class="form-control" name="expected_date_from"
                                           @if(request()->expected_date_from) value="{{request()->expected_date_from}}"
                                            @endif>
                                </div>
                                <div hidden class="col-md-2">
                                    <label>@lang('home.expected_date_to')</label>
                                    <input type="date-local" class="form-control" name="expected_date_to"
                                           @if(request()->expected_date_to) value="{{request()->expected_date_to}}" @endif>
                                </div>

                                <div class="col-md-12">
                                            <button class="btn btn-primary mt-4" type="submit"><i
                                                        class="fa fa-search"></i>@lang('home.search')
                                            </button>
                                           
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


                            </div>


                        </div>
                    </div>

                  
                    </form> 

                    <div class="card-footer row">
                       
                    
                    @if ( request()->report_id == 94001) 
                                @foreach($report_url_bond_p as $report_url_bond_ps)  
                                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{config('app.telerik_server')}}?rpt={{$report_url_bond_ps->report_url}}&id={{request()->branch_id}}&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&pay_type={{implode(',',request()->input('bond_type_id',[]))}}&bond_type=1&lang=ar&skinName=bootstrap"
                                                    class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                                    >@lang('home.print')</a>
                                </div>
                               
                                @endforeach 
                                @endif 
                                 
                                @if ( request()->report_id == 94002) 
                                @foreach($report_url_bond_r as $report_url_bond_rs)  
                                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                    <a href="{{config('app.telerik_server')}}?rpt={{$report_url_bond_rs->report_url}}&id={{request()->branch_id}}&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&pay_type={{implode(',',request()->input('bond_type_id',[]))}}&bond_type={{implode(',',request()->input('bond_type_s_id',[]))}}&lang=ar&skinName=bootstrap"
                                                    class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                                   >@lang('home.print')</a>
                                </div>
                                @endforeach 
                                @endif                      
                               

                                
                                @if ( request()->report_id == 94003) 
                                @foreach($report_url_way_branch as $report_url_way_branchs) 
                                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                    <a href="{{config('app.telerik_server')}}?rpt={{$report_url_way_branchs->report_url}}&id=45&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&lang=ar&skinName=bootstrap"
                                                    class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                                   >@lang('home.print')</a>
                                </div>
                                                    
                                @endforeach 
                                @endif 

                                @if ( request()->report_id == 94004) 
                                @foreach($report_url_bond_branch as $report_url_bond_branchs) 
                                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                    <a href="{{config('app.telerik_server')}}?rpt={{$report_url_bond_branchs->report_url}}&company_id=45&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&lang=ar&skinName=bootstrap"
                                                    class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                                   >@lang('home.print')</a>
                                </div>
                                @endforeach 
                                @endif                      
                                

                                @if ( request()->report_id == 94005) 
                                @foreach($report_url_bond as $report_url_bond_rs)  
                                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                    <a href="{{config('app.telerik_server')}}?rpt={{$report_url_bond_rs->report_url}}&id={{request()->branch_id}}&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&lang=ar&skinName=bootstrap"
                                                    class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                                   >@lang('home.print')</a>
                                </div>
                                @endforeach   
                                @endif    
                                
                                @if ( request()->report_id == 94006) 
                                @foreach($report_url_bond_cus as $report_url_bond_cuss)  
                                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{config('app.telerik_server')}}?rpt={{$report_url_bond_cuss->report_url}}&id={{request()->customers_id}}&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&pay_type={{implode(',',request()->input('bond_type_id',[]))}}&lang=ar&skinName=bootstrap"
                                                    class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                                    >@lang('home.print')</a>
                                </div>
                                @endforeach 
                                @endif 


                                

                                @if ( request()->report_id == 94007) 
                                @foreach($report_url_bond_today as $report_url_bond_todays)  
                                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{config('app.telerik_server')}}?rpt={{$report_url_bond_todays->report_url}}&id={{request()->branch_id}}&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&lang=ar&skinName=bootstrap"
                                                    class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                                   >@lang('home.print')</a>
                                </div>
                                @endforeach 
                                @endif 
                        
                                @if ( request()->report_id == 94008) 
                                @foreach($report_url_bond_s as $report_url_bond_ss)  
                                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                    <a href="{{config('app.telerik_server')}}?rpt={{$report_url_bond_ss->report_url}}&id={{request()->branch_id}}&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&pay_type={{implode(',',request()->input('bond_type_s_id',[]))}}&bond_type=2&lang=ar&skinName=bootstrap"
                                                    class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                                   >@lang('home.print')</a>
                                </div>
                                @endforeach 
                                @endif   
                                
                                
                            

                        <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">	
                            <a href="{{ route('home') }}" class="btn btn-secondary btn-block"> @lang('storeItem.back_button') </a>
                        </div>

                    </div>
     
           
        </div>

        <div class="row mb-12">





            <div class="card-body">


             
                    
               
               
            </div>

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
    <script src="{{asset('jquery-datetime\jquery.datetimepicker')}}"></script>
   
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
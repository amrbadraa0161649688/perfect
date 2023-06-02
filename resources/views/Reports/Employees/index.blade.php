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

{{--------------------------------تقارير الموظفين    -------------------}}
    <div class="container-fluid">


        <div class="section-body mt-3" id="app">
            <div class="container-fluid">

                @include('Includes.form-errors')

                <div class="col-md-12">
                            <div class="row">
                                
                                <div class="col-md-3">
                                    <div class="font-25">
                                        @lang('reports.emp_report')
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                {{--الشركات--}}
                                <div  class="col-md-6">
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

                                <div class="col-md-6">
                                    {{-- الفروع   --}}
                                    <label>@lang('reports.branch_select')</label>
                                    <select class="selectpicker"   multiple data-live-search="true"
                                    data-actions-box="true"
                                            name="branch_id[]" id="branch_id" >
                                        @foreach($branch_lits as $branch_lit)
                                            <option value="{{ $branch_lit->branch_id }}">
                                             {{app()->getLocale()=='ar' ? $branch_lit->branch_name_ar
                                             : $branch_lit->branch_name_en }}</option>
                                        @endforeach
                                    </select>

                                    </div> 

                                    {{--الجنسيات--}}
                                <div hidden class="col-md-6">
                                   
                                    <label>@lang('reports.emp_national')</label>
                                    <select class="selectpicker"   multiple data-live-search="true"
                                    data-actions-box="true"
                                            name="loc_from[]" id="loc_from" > 
                                        @foreach($loc_lits as $loc_lit)
                                            <option value="{{ $loc_lit->system_code_id }}" >  
                                               @if(app()->getLocale()=='ar') 
                                               {{$loc_lit->system_code_name_ar}}
                                               @else
                                             {{$loc_lit->system_code_name_en}}
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                 </div>    
                                <div class="row">

                                {{--الوظائف--}}
                                <div class="col-md-6">
                                    {{-- customers  --}}
                                    <label>@lang('reports.emp_job')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                             data-actions-box="true"
                                            name="customers_id[]" id='customers_id'>
                                        @foreach($jobs as $job)
                                            <option value="{{ $job->job_id }}"
                                                   >
                                                {{app()->getLocale()=='ar' ? $job->job_name_ar
                                             : $job->job_name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--خاله الموظفين--}}
                                <div class="col-md-6">
                                   
                                    <label>@lang('reports.emp_status')</label>
                                    <select class="selectpicker"   multiple data-live-search="true"
                                    data-actions-box="true"
                                            name="status_id[]" id="status_id" > 
                                        @foreach($sys_codes_emp_status as $sys_codes_emp_statu)
                                            <option value="{{ $sys_codes_emp_statu->system_code_id }}" >  
                                               @if(app()->getLocale()=='ar') 
                                               {{$sys_codes_emp_statu->system_code_name_ar}}
                                               @else
                                             {{$sys_codes_emp_statu->system_code_name_en}}
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">

                            <div class="col-md-6">
                                    {{-- اختيار التقرير   --}}
                                    <label>@lang('reports.report_select')</label>
                                    <select class="selectpicker"  data-live-search="true"
                                            name="report_id" id = "report_id">
                                        @foreach($report_acc_lits as $report_acc_lit)
                                            <option value="{{ $report_acc_lit->system_code }}"
                                                    @if(request()->statuses_id) @foreach(request()->statuses_id as
                                                     $status_id) @if($report_acc_lit->system_code_id == $status_id)
                                                    selected @endif @endforeach @endif>
                                                {{app()->getLocale()=='ar' ? $report_acc_lit->system_code_name_ar
                                             : $report_acc_lit->system_code_name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div  class="col-md-6">
                                    {{-- اختيار المستند   --}}
                                    <label>@lang('reports.emp_doc')</label>
                                    <select class="selectpicker"  data-live-search="true"
                                            name="att_id" id="att_id">
                                        @foreach($emp_doc as $emp_docs)
                                            
                                            <option value="{{ $emp_docs->system_code }}" >  
                                               @if(app()->getLocale()=='ar') 
                                               {{$emp_docs->system_code_name_ar}}
                                               @else
                                             {{$emp_docs->system_code_name_en}}
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            
                            <div class="row">

                                {{--تاريخ اانشاء من والي--}}
                                <div class="col-md-6">
                                    <label>@lang('reports.from_date')</label>
                                    <input type="datetime-local" class="form-control" name="created_date_from" id= "created_date_from"
                                           @if(request()->created_date_from) value="{{request()->created_date_from}}"
                                            @endif>
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('reports.to_date')</label>
                                    <input type="datetime-local" class="form-control" name="created_date_to" id= "created_date_to"
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
                                            <button class="btn btn-primary mt-4" type="submit"><i
                                                        class="fa fa-search"></i>@lang('home.search')
                                            </button>
                                           
                                        </div> 

                            </div>


                        </div>
                    </div>
            </form>
        </div>

       



  
    
                <div class="card-footer row">
                 
               
                            {{--تقرير  الموظفين--}}
                            @if ( request()->report_id == 95001) 
                            @foreach($report_url_emp_all as $report_url_emp_all_1)  
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                            <a href="{{config('app.telerik_server')}}?rpt={{$report_url_emp_all_1->report_url}}&comp_id={{implode(',',request()->input('company_id',[]))}}&status_id={{implode(',',request()->input('status_id',[]))}}&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&lang=ar&skinName=bootstrap"
                                                class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                                >@lang('home.print')</a>
                            </div>
                            @endforeach 
                            @endif 
                             
                           
                            {{--تقرير   المستندات--}}
                            @if ( request()->report_id == 95002) 
                            @foreach($report_url_emp_95002 as $report_url_emp_2)  
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                            <a href="{{config('app.telerik_server')}}?rpt={{$report_url_emp_2->report_url}}&comp_id={{implode(',',request()->input('company_id',[]))}}&status_id={{implode(',',request()->input('status_id',[]))}}&att_id={{request()->att_id}}&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&lang=ar&skinName=bootstrap"
                                                class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                                >@lang('home.print')</a>
                            </div>
                            @endforeach 
                            @endif 
                             

                            {{--تقرير   بيانات الرواتب--}}
                            @if ( request()->report_id == 95003) 
                            @foreach($report_url_emp_95003 as $report_url_emp_3)  
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                            <a href="{{config('app.telerik_server')}}?rpt={{$report_url_emp_3->report_url}}&comp_id={{implode(',',request()->input('company_id',[]))}}&date_to={{request()->created_date_to}}&lang=ar&skinName=bootstrap"
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
           
           


            var showReportBtn = $('#showReport');
            var originalHref = showReportBtn.prop('href');
            
            showReportBtn.on('click',function(e){
               
                
                var report_id = $('#report_id').find(':selected');
                var fromSelect = $('#created_date_from');
                var toSelect = $('#created_date_to');
              //  var bond_type_id = $('#bond_type_id');
                var href = originalHref;
                // var employee = $('#employee');
                var customer = $('#customer_id');

                href = href.replace('$R$',$('#report_id').val());
                href = href.replace('$FM$',fromSelect.val());
                href = href.replace('$TD$',toSelect.val());
                href = href.replace('$BR$',$('#customer_id').val().join(','));
                
             //   href = href.replace('$bondtype$',$('#bond_type_id').val().join(','));
                // href = href.replace('$TY$',toSelect.data('y'));
                // href = href.replace('$FILTER$',"branch="+$('#branchSelect').val().join(','));
                showReportBtn.prop('href',href);
                return true;

            });

        });
    </script>
@endpush
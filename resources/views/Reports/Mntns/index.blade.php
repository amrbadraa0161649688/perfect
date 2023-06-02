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
                                        @lang('reports.mntns_report')
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

                                    <div class="col-md-6">
                                            <label>  @lang('maintenanceType.mntns_card_plate') </label>
                                        

                                            <select class="selectpicker"   multiple data-live-search="true"
                                            data-actions-box="true"
                                                    name="truck_id[]" id="truck_id" >
                                                @foreach($car_lits as $car_lit)
                                                    <option value="{{ $car_lit->mntns_cars_id }}">
                                                    {{app()->getLocale()=='ar' ? $car_lit->mntns_cars_type
                                                    : $car_lit->mntns_cars_type }}</option>
                                                @endforeach
                                            </select>

                                     </div>

                                </div>    
                                <div class="row">

                                {{--العملاء--}}
                                <div class="col-md-6">
                                    {{-- customers  --}}
                                    <label>@lang('reports.customer_select')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                             data-actions-box="true"
                                            name="customers_id[]" id='customers_id'>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->customer_id }}"
                                                   >
                                                {{app()->getLocale()=='ar' ? $customer->customer_name_full_ar
                                             : $customer->customer_name_full_en }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div  class="col-md-6">
                                    {{-- اختيار حاله كارت الصيانه   --}}
                                    <label>@lang('reports.mntns_card_select')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                    data-actions-box="true"
                                            name="mntns_status[]" id="mntns_status"  >
                                        @foreach($card_type_ids as $card_type_id)
                                            <option value="{{ $card_type_id->system_code }}">
                                                   
                                                {{app()->getLocale()=='ar' ? $card_type_id->system_code_name_ar
                                             : $card_type_id->system_code_name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>

                               
                            </div>

                            <div class="row">

                            <div class="col-md-6">
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

                                <div  class="col-md-6">
                                    {{-- اختيار طريقة الدفع   --}}
                                    <label>@lang('reports.bond_type_select')</label>
                                    <select class="selectpicker"  data-live-search="true"
                                    data-actions-box="true"
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
                                <div class="col-md-6">
                                    <label>@lang('reports.from_date')</label>
                                    <input type="datetime-local" class="form-control" name="created_date_from"
                                           @if(request()->created_date_from) value="{{request()->created_date_from}}"
                                            @endif>
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('reports.to_date')</label>
                                    <input type="datetime-local" class="form-control" name="created_date_to"
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

                       
                    {{--تقرير جميع البوالص--}}
                            @if ( request()->report_id == 102001) 
                            @foreach($report_url_mntns as $report_url_mntns_a)  
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                            <a href="{{config('app.telerik_server')}}?rpt={{$report_url_mntns_a->report_url}}&id={{implode(',',request()->input('branch_id',[]))}}&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&mntns_status={{implode(',',request()->input('mntns_status',[]))}}&lang=ar&skinName=bootstrap"
                                                class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                                >@lang('home.print')</a>
                            </div>
                            @endforeach 
                            @endif 
                
                            {{--تقرير كروت عميل  البوالص--}}
                            @if ( request()->report_id == 102002) 
                            @foreach($report_url_mntns_cus as $report_url_mntns_cus_a)  
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                            <a href="{{config('app.telerik_server')}}?rpt={{$report_url_mntns_cus_a->report_url}}&id={{implode(',',request()->input('branch_id',[]))}}&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&customer_id={{implode(',',request()->input('customers_id',[]))}}&mntns_status={{implode(',',request()->input('mntns_status',[]))}}&lang=ar&skinName=bootstrap"
                                                class="btn btn-warning btn-block" id='showReport'  target="_blank" 
                                                >@lang('home.print')</a>
                            </div>
                            @endforeach 
                            @endif 
                                      
                            {{--تقرير كروت   سياره--}}
                            @if ( request()->report_id == 102004) 
                            @foreach($report_url_mntns_car as $report_url_mntns_car_a)  
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                            <a href="{{config('app.telerik_server')}}?rpt={{$report_url_mntns_car_a->report_url}}&id={{implode(',',request()->input('branch_id',[]))}}&date_from={{request()->created_date_from}}&date_to={{request()->created_date_to}}&customer_id={{implode(',',request()->input('truck_id',[]))}}&mntns_status={{implode(',',request()->input('mntns_status',[]))}}&lang=ar&skinName=bootstrap"
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
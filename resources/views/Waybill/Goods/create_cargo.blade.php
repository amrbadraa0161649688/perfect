@extends('Layouts.master')

    @section('style')
        <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.css')}}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css"/>
    @endsection


    @section('content')
            <div class="section-body">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center">
                        
                            <div class="card">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                                    <div class="col-md-1">
                                                        
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="font-25">
                                                        @lang('waybill.add_new_waybill')
                                                    </div>
                                             </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="section-body mt-6" id="app">
                <div class="container-fluid">
                    <div class="tab-content mt-6">

                        {{-- Basic information --}}
                        <div class="tab-pane fade show active " id="data-grid" role="tabpanel">

                            {{-- Form To Create Waybill--}}
                               <form class="card" id="validate-form" action="{{ route('Waybill.store') }}"
                                        method="post"
                                        enctype="multipart/form-data" id="submit_user_form">
                                        @csrf

                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-md-12">
    
                                                                        <div class="row">
                                                                            
                                                                            <div class="col-md-3">
                                                                                <label for="recipient"
                                                                                    class="col-form-label"> @lang('trucks.sub_company') </label>
                                                                                <select class="form-select form-control is-invalid"
                                                                                        name="company_id"
                                                                                        id="company_id" 
                                                                                        @change="getBranches()" v-model="company_id"required>
                                                                                    <option value="" selected>Choose</option>
                                                                                    @foreach($companies as $company)
                                                                                        <option value="{{ $company->company_id }}">
                                                                                            @if(app()->getLocale()=='ar')
                                                                                                {{ $company->company_name_ar }}
                                                                                            @else
                                                                                                {{ $company->company_name_en }}
                                                                                            @endif
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="col-md-3">
                                                                                <label for="recipient-name"
                                                                                    class="col-form-label"> @lang('waybill.waybill_type') </label>
                                                                                <select class="form-select form-control is-invalid" name="truck_type"
                                                                                        id="truck_type" required>
                                                                                        <option value="" selected></option>
                                                                                        @foreach($sys_codes_item as $sys_code_item)
                                                                                            <option value="{{$sys_code_item->sys_code_item}}">
                                                                                                @if(app()->getLocale() == 'ar')
                                                                                                    {{$sys_code_item->system_code_name_ar}}
                                                                                                @else
                                                                                                    {{$sys_code_item->system_code_name_en}}
                                                                                                @endif
                                                                                            </option>  
                                                                                        @endforeach 
                                                                                </select>
                                        
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">@lang('home.created_date')</label>
                                                                                    <input id="date" type="text" class="form-control" readonly>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">@lang('home.user')</label>
                                                                                    <input type="text" calss="form-control" readonly class="form-control"
                                                                                        value="@if(app()->getLocale()=='ar') {{ auth()->user()->user_name_ar }}
                                                                                        @else {{ auth()->user()->user_name_en }} @endif">
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">

                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                        <label for="recipient-name"
                                                                                            class="col-form-label" style="text-decoration: underline;"> @lang('waybill.customer_name') </label>
                                                                                        <select class="form-select form-control" name="truck_supplier"
                                                                                                id="truck_supplier">
                                                                                            <option value="" selected>choose</option>
                                                                                            @foreach($suppliers as $supplier)
                                                                                                <option value="{{$supplier->emp_id }}">
                                                
                                                                                                    @if(app()->getLocale() == 'ar')
                                                                                                        {{ $supplier->customer_name_full_ar }}
                                                                                                    @else
                                                                                                        {{ $supplier->customer_name_full_en }}
                                                                                                    @endif
                                                
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <label for="recipient-name"
                                                                                    class="col-form-label"> @lang('waybill.waybil_request_no') </label>
                                                                                <input type="text" class="form-control"
                                                                                    v-bind:class="{ 'is-invalid' : full_en }"
                                                                                    name="truck_name" v-model="truck_name"
                                                                                    id="truck_name" placeholder="@lang('waybill.waybil_request_no')" 
                                                                                        required>
                                                                            </div>

                                                                            <div class="col-md-3">
                                                                                <label for="recipient"
                                                                                    class="col-form-label"> @lang('waybill.waybill_received_date') </label>
                                                                                <input type="date" class="form-control" name="truck_purchase_date"
                                                                                    id="truck_purchase_date" placeholder="@lang('waybill.waybill_received_date')"
                                                                                    onchange="getDateBirthday()">
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <label for="recipient-name"
                                                                                    class="col-form-label"> @lang('waybill.waybill_payment_method') </label>
                                                                                <select class="form-select form-control is-invalid" name="truck_type"
                                                                                        id="truck_type" required>
                                                                                        <option value="" selected></option>
                                                                                        @foreach($sys_codes_item as $sys_code_item)
                                                                                            <option value="{{$sys_code_item->sys_code_item}}">
                                                                                                @if(app()->getLocale() == 'ar')
                                                                                                    {{$sys_code_item->system_code_name_ar}}
                                                                                                @else
                                                                                                    {{$sys_code_item->system_code_name_en}}
                                                                                                @endif
                                                                                            </option>  
                                                                                        @endforeach 
                                                                                </select>
                                        
                                                                            </div>
                                                                            
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <label for="recipient-name"
                                                                                    class="col-form-label"> @lang('waybill.waybill_origin') </label>
                                                                                <input type="text" class="form-control"
                                                                                    v-bind:class="{ 'is-invalid' : full_en }"
                                                                                    name="truck_name" v-model="truck_name"
                                                                                    id="truck_name" placeholder="@lang('waybill.waybill_origin')" 
                                                                                        required>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label for="recipient-name"
                                                                                    class="col-form-label"> @lang('waybill.waybill_destination') </label>
                                                                                <input type="text" class="form-control"
                                                                                    v-bind:class="{ 'is-invalid' : full_en }"
                                                                                    name="truck_name" v-model="truck_name"
                                                                                    id="truck_name" placeholder="@lang('waybill.waybill_destination')" 
                                                                                        required>
                                                                            </div>

                                                                        </div>
                                                </div>
                                            </div>    
                                        </div> 

                                        <div class="container-fluid">

                                            <div class="row">

                                                <div class="col-md-6 col-lg-6">
                                                        <div class="card">
                                                                        <div class="row">
                                                                            
                                                                            <div class="col-md-6">
                                                                                <label for="recipient-name"
                                                                                    class="col-form-label"> @lang('waybill.sender_name') </label>
                                                                                <input type="text" class="form-control is-invalid" name="emp_work_mobile"
                                                                                    id="emp_work_mobile" placeholder="@lang('waybill.sender_name')" required>
                                        
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="recipient-name"
                                                                                    class="col-form-label"> @lang('waybill.sender_company') </label>
                                                                                <input type="text" class="form-control is-invalid" name="emp_work_mobile"
                                                                                    id="emp_work_mobile" placeholder="@lang('waybill.sender_company')" required>
                                        
                                                                                </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            
                                                                            <div class="col-md-6">
                                                                                <label for="recipient-name"
                                                                                    class="col-form-label"> @lang('waybill.sender_address') </label>
                                                                                <input type="text" class="form-control is-invalid" name="emp_work_mobile"
                                                                                    id="emp_work_mobile" placeholder="@lang('waybill.sender_address')" required>
                                        
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="recipient-name"
                                                                                    class="col-form-label"> @lang('waybill.sender_phone') </label>
                                                                                <input type="text" class="form-control is-invalid" name="emp_work_mobile"
                                                                                    id="emp_work_mobile" placeholder="@lang('waybill.sender_phone')" required>
                                        
                                                                                </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            
                                                                            <div class="col-md-6">
                                                                                <label for="recipient-name"
                                                                                    class="col-form-label"> @lang('waybill.sender_mobile') </label>
                                                                                <input type="text" class="form-control is-invalid" name="emp_work_mobile"
                                                                                    id="emp_work_mobile" placeholder="@lang('waybill.sender_mobile')" required>
                                        
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="recipient-name"
                                                                                    class="col-form-label"> @lang('waybill.sender_box_no') </label>
                                                                                <input type="text" class="form-control is-invalid" name="emp_work_mobile"
                                                                                    id="emp_work_mobile" placeholder="@lang('waybill.sender_box_no')" required>
                                        
                                                                                </div>
                                                                        </div>
                                                        </div>
                                                </div>
                                                <div class="col-md-6 col-lg-6">
                                                    <div class="card">
                                                        <div class="row">
                                                                            
                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                    class="col-form-label"> @lang('waybill.receiver_name') </label>
                                                                <input type="text" class="form-control is-invalid" name="emp_work_mobile"
                                                                    id="emp_work_mobile" placeholder="@lang('waybill.receiver_name')" required>
                        
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                    class="col-form-label"> @lang('waybill.sender_company') </label>
                                                                <input type="text" class="form-control is-invalid" name="emp_work_mobile"
                                                                    id="emp_work_mobile" placeholder="@lang('waybill.sender_company')" required>
                        
                                                                </div>
                                                        </div>

                                                        <div class="row">
                                                            
                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                    class="col-form-label"> @lang('waybill.sender_address') </label>
                                                                <input type="text" class="form-control is-invalid" name="emp_work_mobile"
                                                                    id="emp_work_mobile" placeholder="@lang('waybill.sender_address')" required>
                        
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                    class="col-form-label"> @lang('waybill.sender_phone') </label>
                                                                <input type="text" class="form-control is-invalid" name="emp_work_mobile"
                                                                    id="emp_work_mobile" placeholder="@lang('waybill.sender_phone')" required>
                        
                                                                </div>
                                                        </div>
                                                        <div class="row">
                                                            
                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                    class="col-form-label"> @lang('waybill.sender_mobile') </label>
                                                                <input type="text" class="form-control is-invalid" name="emp_work_mobile"
                                                                    id="emp_work_mobile" placeholder="@lang('waybill.sender_mobile')" required>
                        
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                    class="col-form-label"> @lang('waybill.sender_box_no') </label>
                                                                <input type="text" class="form-control is-invalid" name="emp_work_mobile"
                                                                    id="emp_work_mobile" placeholder="@lang('waybill.sender_box_no')" required>
                        
                                                                </div>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>  


                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                    class="col-form-label"> @lang('waybill.waybill_item') </label>
                                                <select class="form-select form-control is-invalid" name="truck_type"
                                                        id="truck_type" required>
                                                        <option value="" selected></option>
                                                        @foreach($sys_codes_item as $sys_code_item)
                                                            <option value="{{$sys_code_item->sys_code_item}}">
                                                                @if(app()->getLocale() == 'ar')
                                                                    {{$sys_code_item->system_code_name_ar}}
                                                                @else
                                                                    {{$sys_code_item->system_code_name_en}}
                                                                @endif
                                                            </option>  
                                                        @endforeach 
                                                </select>
        
                                            </div>

                                                <div class="col-md-2">
                                                    <label for="recipient-name"
                                                        class="col-form-label"> @lang('waybill.waybill_qut_actual') </label>
                                                    <input type="number" class="form-control is-invalid" name="emp_work_mobile"
                                                        id="emp_work_mobile" placeholder="@lang('waybill.waybill_qut_actual')" required>
            
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="recipient-name"
                                                        class="col-form-label"> @lang('waybill.waybill_price') </label>
                                                    <input type="number" class="form-control is-invalid" name="emp_work_mobile"
                                                        id="emp_work_mobile" placeholder="@lang('waybill.waybill_price')" required>
            
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="recipient-name"
                                                        class="col-form-label"> @lang('waybill.waybil_servic_charge') </label>
                                                    <input type="number" class="form-control is-invalid" name="emp_work_mobile"
                                                        id="emp_work_mobile" placeholder="@lang('waybill.waybil_servic_charge')" required>
            
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="recipient-name"
                                                        class="col-form-label"> @lang('waybill.waybil_insurance_charge') </label>
                                                    <input type="number" class="form-control is-invalid" name="emp_work_mobile"
                                                        id="emp_work_mobile" placeholder="@lang('waybill.waybil_insurance_charge')" required>
            
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="recipient-name"
                                                        class="col-form-label"> @lang('waybill.waybill_amount') </label>
                                                    <input type="number" class="form-control is-invalid" name="waybill_total"
                                                        id="emp_work_mobile" placeholder="@lang('waybill.waybill_amount')" readonly>
            
                                                </div>


                                            </div> 

                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label for="recipient-name"
                                                        class="col-form-label"> @lang('waybill.waybil_declared_value') </label>
                                                    <input type="number" class="form-control is-invalid" name="emp_work_mobile"
                                                        id="emp_work_mobile" placeholder="@lang('waybill.waybil_declared_value')" required>
            
                                                </div>
                                               
                                                <div class="col-md-2">
                                                    <label for="recipient-name"
                                                        class="col-form-label"> @lang('waybill.waybil_item_unit') </label>
                                                    <select class="form-select form-control is-invalid" name="truck_type"
                                                            id="truck_type" required>
                                                            <option value="" selected></option>
                                                            @foreach($sys_codes_item as $sys_code_item)
                                                                <option value="{{$sys_code_item->sys_code_item}}">
                                                                    @if(app()->getLocale() == 'ar')
                                                                        {{$sys_code_item->system_code_name_ar}}
                                                                    @else
                                                                        {{$sys_code_item->system_code_name_en}}
                                                                    @endif
                                                                </option>  
                                                            @endforeach 
                                                    </select>
            
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="recipient-name"
                                                        class="col-form-label"> @lang('waybill.waybil_weight') </label>
                                                    <input type="number" class="form-control is-invalid" name="emp_work_mobile"
                                                        id="emp_work_mobile" placeholder="@lang('waybill.waybil_weight')" required>
            
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="recipient-name"
                                                        class="col-form-label"> @lang('waybill.waybill_vat') </label>
                                                    <input type="number" class="form-control is-invalid" name="emp_work_mobile"
                                                        id="emp_work_mobile" placeholder="@lang('waybill.waybill_vat')" required>
            
                                                </div>
                                                    <div class="col-md-2">
                                                        <label for="recipient-name"
                                                            class="col-form-label"> @lang('waybill.waybill_vat_amount') </label>
                                                        <input type="number" class="form-control is-invalid" name="emp_work_mobile"
                                                            id="emp_work_mobile" placeholder="@lang('waybill.waybill_vat_amount')" readonly>
                
                                                    </div>
                                                    
                                                   
                                                    <div class="col-md-2">
                                                        <label for="recipient-name"
                                                            class="col-form-label"> @lang('waybill.waybill_total') </label>
                                                        <input type="number" class="form-control is-invalid" name="waybill_total"
                                                            id="emp_work_mobile" placeholder="@lang('waybill.waybill_total')" readonly>
                
                                                    </div>
    
    
                                                </div> 
    
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <label for="recipient-name"
                                                            class="col-form-label"> @lang('waybill.waybill_driver') </label>
                                                        <input type="number" class="form-control is-invalid" name="emp_work_mobile"
                                                            id="emp_work_mobile" placeholder="@lang('waybill.waybill_driver')" required>
                
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="recipient-name"
                                                            class="col-form-label"> @lang('waybill.waybill_truck') </label>
                                                        <input type="number" class="form-control is-invalid" name="emp_work_mobile"
                                                            id="emp_work_mobile" placeholder="@lang('waybill.waybill_truck')" required>
                
                                                        </div>
                                                    </div>   
            





                                     </div> 


                                </form>   
                        </div>
        
                    </div>
                </div>
            </div>                                      
     @endsection    

            @section('scripts')
                <script src="{{asset('assets/plugins/dropify/js/dropify.min.js')}}"></script>
                        <script src="{{asset('assets/js/form/form-advanced.js')}}"></script>
                        <script>
                        $(document).ready(function () {
                    
                        })
                </script>
             @endsection
        
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
        <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
        <script type="text/javascript"></script>


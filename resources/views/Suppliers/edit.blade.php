@extends('Layouts.master')

@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css"/>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>

    @endsection

@section('content')
    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <ul class="nav nav-tabs page-header-tab">
                    <li class="nav-item @if(!request()->qr) active @endif ">
                        <a class="nav-link" href="#data-grid" data-toggle="tab">@lang('home.basic_information')</a>
                    </li>
                    <li class="nav-item @if(request()->qr == 'contracts') active @endif ">
                        <a class="nav-link" href="#contracts-grid" data-toggle="tab">@lang('home.contract_data')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="tab">@lang('customer.customer_account')</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#"
                                            data-toggle="tab">@lang('customer.customer_document')</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"
                                            data-toggle="tab">@lang('customer.customer_notes')</a></li>
                   
                </ul>
                <div class="header-action">

                </div>
            </div>
        </div>
    </div>





    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">

                     {{-- Basic information --}}

                    @include('Includes.form-errors')
                    {{-- Form To Create Employee--}}
                    <form class="card" id="validate-form" action="{{ route('Suppliers.update',$customer->customer_id) }}"
                          method="post"
                          enctype="multipart/form-data" id="submit_user_form">
                        @csrf
                        @method('put')
                        
                        <div class="card-body">
                            {{--inputs and photo--}}
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="font-25" style="text-decoration: underline">
                                                @lang('customer.changed_supplier')
                                            </div>
                                        </div>
                                    </div>
                                </div>     
                                <div class="col-md-9">
                                    
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label " readonly> @lang('customer.supplier_code')  </label>
                                                <input type="text" class="form-control"  name="customer_id"
                                                       id="customer_id" readonly value="{{$customer->customer_id}}">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('home.identity') </label>
                                                <input type="number" class="form-control" name="customer_identity"
                                                       id="customer_identity" placeholder="@lang('home.identity')" readonly
                                                       value="{{$customer->customer_identity}}" required>
                                            </div>
                                          
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.first_name_ar') </label>
                                                <input type="text" class="form-control" name="customer_name_1_ar"
                                                       id="customer_name_1_ar"
                                                       value="{{$customer->customer_name_1_ar}}"
                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.second_name_ar') </label>
                                                <input type="text" class="form-control" name="customer_name_2_ar"
                                                       id="customer_name_2_ar" value="{{$customer->customer_name_2_ar}}"
                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.third_name_ar') </label>
                                                <input type="text" class="form-control" name="customer_name_3_ar"
                                                       id="customer_name_3_ar" value="{{$customer->customer_name_3_ar}}"
                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.fourth_name_ar') </label>
                                                <input type="text" class="form-control" name="customer_name_4_ar"
                                                       id="customer_name_4_ar" value="{{$customer->customer_name_4_ar}}"
                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');">
                                            </div>


                                        </div>
                                    </div> 
                               

                               
                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                    class="col-form-label"> @lang('home.first_name_en') </label>
                                                <input type="text" class="form-control" name="customer_name_1_en"
                                                    id="customer_name_1_en"  value="{{$customer->customer_name_1_en}}"
                                                    
                                                    oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');" >
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                    class="col-form-label"> @lang('home.second_name_en') </label>
                                                <input type="text" class="form-control" name="customer_name_2_en"
                                                    id="customer_name_2_en" placeholder="@lang('home.second_name_en')"
                                                    value="{{$customer->customer_name_2_en}}"
                                                    oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');" >
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                    class="col-form-label"> @lang('home.third_name_en') </label>
                                                <input type="text" class="form-control" name="customer_name_3_en"
                                                    id="customer_name_3_en" placeholder="@lang('home.third_name_en')"
                                                    value="{{$customer->customer_name_3_en}}"
                                                    oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');" >
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                    class="col-form-label"> @lang('home.fourth_name_en') </label>
                                                <input type="text" class="form-control" name="customer_name_4_en"
                                                    id="customer_name_4_en" placeholder="@lang('home.fourth_name_en')"
                                                    value="{{$customer->customer_name_4_en}}"
                                                    oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');" >
                                            </div>


                                         </div>
                                    </div>
                            

                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">

                                        <div class="card">

                                            <div class="card-header">
                                                <div class="avatar avatar-blue"  data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="" data-original-title="Avatar Name">
                                                    <img class="round"src="{{$customer->customer_photo}}">
                                               </div>
                                               
                                            </div>
                                            <div class="card-body">
                                                <input type="file" id="dropify-event"
                                                       name="customer_photo">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('home.nationality') </label>
                                            <select class="form-select form-control"
                                                    name="customer_nationality"
                                                    id="customer_nationality" required>
                                                <option value= "" selected> </option>
                                                @foreach($sys_codes_nationality_country as $sys_code_nationality_country)
                                                    <option value="{{ $sys_code_nationality_country->system_code_id }}" 
                                                        @if($sys_code_nationality_country->system_code_id == $customer->customer_nationality)
                                                        selected
                                                        @endif
                                                        >
                                                        @if(app()->getLocale()=='ar')
                                                            {{$sys_code_nationality_country->system_code_name_ar}}
                                                        @else
                                                            {{$sys_code_nationality_country->system_code_name_en}}
                                                        @endif

                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('home.gender') </label>
                                            <select class="form-select form-control" name="customer_type"
                                                    id="customer_type" required>
                                                    <option value="" selected></option>
                                                    @foreach($sys_codes_type as $sys_code_type)
                                                        <option value="{{$sys_code_type->system_code_id}}" 
                                                            @if($sys_code_type->system_code_id == $customer->customer_type)
                                                            selected
                                                            @endif
                                                            >
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$sys_code_type->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_type->system_code_name_en}}
                                                            @endif
                                                        </option>  
                                                    @endforeach 
                                            </select>
    
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('home.status') </label>
                                            <select class="form-select form-control" name="customer_status"
                                                    aria-label="Default select example" id="customer_status" required>
                                                <option value="" selected></option>
                                                @foreach($sys_codes_status as $sys_code_status)
                                                    <option value="{{$sys_code_status->system_code_id}}"
                                                        @if($sys_code_status->system_code_id == $customer->customer_status)
                                                        selected
                                                        @endif
                                                        >
                                                        @if(app()->getLocale() == 'ar')
                                                            {{$sys_code_status->system_code_name_ar}}
                                                        @else
                                                            {{$sys_code_status->system_code_name_en}}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('customer.private_mobile') </label>
                                            <input type="number" class="form-control" name="customer_mobile"
                                                   id="customer_mobile" value="{{$customer->customer_mobile}}" required>
    
                                        </div>
                                        <div class="col-md-4">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('customer.country_code') </label>
                                            <select class="form-select form-control" name="customer_mobile_code"
                                                    id="customer_mobile_code"  >
                                                <option value="" selected> </option>
                                                @foreach($sys_codes_countries as $sys_code_country)
                                                    <option value="{{$sys_code_country->system_code_id}}"
                                                        @if($sys_code_country->system_code_id == $customer->customer_mobile_code)
                                                        selected
                                                        @endif
                                                        >
                                                        @if(app()->getLocale() == 'ar')
                                                            {{$sys_code_country->system_code_name_ar}}
                                                        @else
                                                            {{$sys_code_country->system_code_name_en}}
                                                        @endif
                                                    </option>
                                                @endforeach
    
                                            </select>
    
                                        </div>
                                        <div class="col-md-4">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('customer.vat_no') </label>
                                            <input type="text" class="form-control" name="customer_vat_no"
                                                   id="customer_vat_no" value="{{$customer->customer_vat_no}}" required>
                                        </div>
                                    </div>
                                </div> 
                                        <div class="mb-3">
                                            <div class="row">
                                        <div class="col-md-4">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('customer.work_mobile') </label>
                                            <input type="number" class="form-control" name="customer_phone"
                                                   id="customer_phone" value="{{$customer->customer_phone}}" >
    
                                        </div>
    
                                        <div class="col-md-4">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('home.po_box_postal') </label>
                                            <input type="text" class="form-control" name="customer_address_2"
                                                   id="customer_address_2" value="{{$customer->customer_address_2}}" >
                                        </div>
    
                                        <div class="col-md-4">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('customer.lemet_balance') </label>
                                            <input type="text" class="form-control" name="customer_credit_limit"
                                                   id="customer_credit_limit" value="{{$customer->customer_credit_limit}}" >
                                        </div>
                                    </div>
                               
    
                               
    
                                <div class="mb-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('customer.customer_address') </label>
                                            <input type="text" class="form-control" name="customer_address_1"
                                                   id="customer_address_1" value="{{$customer->customer_address_1}}" >
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('customer.email_work') </label>
                                            <input type="email" class="form-control" name="customer_email"
                                                   id="customer_email" value="{{$customer->customer_email}}" >
                                        </div>
                                        
                                        <div class="col-md-4">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('home.system_code_acc_id') </label>
                                                    <select class="selectpicker"  data-live-search="true" name="customer_account_id"
                                                            id="customer_account_id" >
                                                        <option value="" selected>@lang('home.choose')</option>
                                                        @foreach($accountL as $accountLs)
                                                            <option value="{{$accountLs->acc_id}}"
                                                            @if($customer->customer_account_id == $accountLs->acc_id)
                                                            selected @endif>
                                                                @if(app()->getLocale() == 'ar')
                                                                    {{ $accountLs->acc_name_ar }}
                                                                @else
                                                                    {{ $accountLs->acc_name_en }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>

                                    </div>
                                </div>

                            
                           
                        </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary mr-4"
                                        id="create_customer">@lang('home.save')</button>
                            </div>
                        </div>
                   
                    
                </form>
            </div>
        </div>
    </div>
                {{-- ----------contracts-grid----------}}
                
                @endsection       
                @section('scripts')
    <script src="{{asset('assets/plugins/dropify/js/dropify.min.js')}}"></script>
    <script src="{{asset('assets/js/form/form-advanced.js')}}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
<script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
<script type="text/javascript">

   
            <script>

                    new Vue({
                        el: '#app',
                        data: {
                            company_id: '',
                            branches: {}
                        },
                        methods: {
                            getBranches() {
                                $.ajax({
                                    type: 'GET',
                                    data: {company_id: this.company_id},
                                    url: '{{ route("api.company.branches") }}'
                                }).then(response => {
                                    this.branches = response.data
                                })

                            }
                        }
                    });
                </script>


@endsection

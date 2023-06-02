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

                <div class="header-action"></div>
            </div>
        </div>
    </div>


    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">

                {{-- Basic information --}}
                <div class="tab-pane fade show active " id="data-grid" role="tabpanel">

                    {{-- Form To Create Customer--}}
                    <form class="card" id="validate-form" action="{{ route('Customers.store') }}"
                          method="post"
                          enctype="multipart/form-data" id="submit_user_form">
                        @csrf

                        <div class="card-body">
                            {{--inputs and photo--}}
                            <div class="font-25">
                                @lang('customer.add_new_customer')
                            </div>
                            <div class="row">
                                <div class="col-md-9">

                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.name_ar') </label>
                                                <input type="text" class="form-control"
                                                       v-bind:class="{ 'is-invalid' : full_ar }"
                                                       name="customer_name_full_ar" v-model="customer_name_full_ar"
                                                       id="customer_name_full_ar" placeholder="@lang('home.name_ar')"
                                                       readonly
                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                                       required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.name_en') </label>
                                                <input type="text" class="form-control"
                                                       v-bind:class="{ 'is-invalid' : full_en }"
                                                       name="customer_name_full_en" v-model="customer_name_full_en"
                                                       id="customer_name_full_en" placeholder="@lang('home.name_en')"
                                                       readonly
                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                                       required>

                                            </div>
                                            <div class="col-md-4">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('home.identity') </label>
                                                <input type="number" class="form-control is-invalid"
                                                       name="customer_identity"
                                                       id="customer_identity" placeholder="@lang('home.identity')"
                                                       required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.first_name_ar') </label>
                                                <input type="text" class="form-control is-invalid"
                                                       name="customer_name_1_ar"
                                                       autocomplete="off"
                                                       id="customer_name_1_ar" placeholder="@lang('home.first_name_ar')"
                                                       v-model="customer_name_1_ar"
                                                       required>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.second_name_ar') </label>
                                                <input type="text" class="form-control is-invalid"
                                                       name="customer_name_2_ar"
                                                       autocomplete="off"
                                                       id="customer_name_2_ar"
                                                       placeholder="@lang('home.second_name_ar')"
                                                       v-model="customer_name_2_ar"
                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                                       >
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.third_name_ar') </label>
                                                <input type="text" class="form-control" name="customer_name_3_ar"
                                                       autocomplete="off"
                                                       id="customer_name_3_ar" placeholder="@lang('home.third_name_ar')"
                                                       v-model="customer_name_3_ar"
                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                                       >
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.fourth_name_ar') </label>
                                                <input type="text" class="form-control is-invalid"
                                                       name="customer_name_4_ar"
                                                       autocomplete="off"
                                                       id="customer_name_4_ar"
                                                       placeholder="@lang('home.fourth_name_ar')"
                                                       v-model="customer_name_4_ar"
                                                       >
                                            </div>


                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.first_name_en') </label>
                                                <input type="text" class="form-control " name="customer_name_1_en"
                                                       id="customer_name_1_en" placeholder="@lang('home.first_name_en')"
                                                       v-model="customer_name_1_en"
                                                       >
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.second_name_en') </label>
                                                <input type="text" class="form-control "
                                                       name="customer_name_2_en"
                                                       id="customer_name_2_en"
                                                       placeholder="@lang('home.second_name_en')"
                                                       v-model="customer_name_2_en"
                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                                       >
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.third_name_en') </label>
                                                <input type="text" class="form-control" name="customer_name_3_en"
                                                       id="customer_name_3_en" placeholder="@lang('home.third_name_en')"
                                                       v-model="customer_name_3_en"
                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                                       >
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.fourth_name_en') </label>
                                                <input type="text" class="form-control " name="customer_name_4_en"
                                                       id="customer_name_4_en"
                                                       placeholder="@lang('home.fourth_name_en')"
                                                       v-model="customer_name_4_en"
                                                       >
                                            </div>


                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.nationality') </label>
                                                <select class="form-select form-control is-invalid"
                                                        name="customer_nationality"
                                                        id="customer_nationality" required>
                                                    <option value="" selected></option>
                                                    @foreach($sys_codes_nationality_country as $sys_code_nationality_country)
                                                        <option value="{{ $sys_code_nationality_country->system_code_id }}">
                                                            @if(app()->getLocale()=='ar')
                                                                {{$sys_code_nationality_country->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_nationality_country->system_code_name_en}}
                                                            @endif

                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.gender') </label>
                                                <select class="form-select form-control is-invalid" name="customer_type"
                                                        id="customer_type" required>
                                                    <option value="" selected></option>
                                                    @foreach($sys_codes_type as $sys_code_type)
                                                        <option value="{{$sys_code_type->system_code_id}}">
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$sys_code_type->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_type->system_code_name_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('home.status') </label>
                                                <select class="form-select form-control is-invalid"
                                                        name="customer_status"
                                                        aria-label="Default select example" id="customer_status"
                                                        required>
                                                    <option value="" selected></option>
                                                    @foreach($sys_codes_status as $sys_code_status)
                                                        <option value="{{$sys_code_status->system_code_id}}">
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$sys_code_status->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_status->system_code_name_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('customer.customer_categ') </label>
                                                <select class="form-select form-control is-invalid"
                                                        name="customer_category"
                                                        aria-label="Default select example" id="customer_category"
                                                        required>
                                                    <option value="" selected></option>
                                                    @foreach($sys_customer_category as $sys_customer_categorys)
                                                        <option value="{{$sys_customer_categorys->system_code}}">
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$sys_customer_categorys->system_code_name_ar}}
                                                            @else
                                                                {{$sys_customer_categorys->system_code_name_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">

                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">@lang('home.add_photo')</h3>
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
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('customer.private_mobile') </label>
                                        <input type="number" class="form-control is-invalid" name="customer_mobile"
                                               id="customer_mobile" placeholder="@lang('customer.private_mobile')"
                                               required>

                                    </div>
                                    <div hidden class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('customer.country_code') </label>
                                        <select class="form-select form-control is-invalid" name="customer_mobile_code"
                                                id="customer_mobile_code" >
                                            <option value="" selected></option>
                                            @foreach($sys_codes_nationality_country as $sys_code_nationality_country)
                                                <option value="{{ $sys_code_nationality_country->system_code_id }}">
                                                    @if(app()->getLocale()=='ar')
                                                        {{$sys_code_nationality_country->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_nationality_country->system_code_name_en}}
                                                    @endif

                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                   


                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('customer.vat_no') </label>
                                        <input type="text" class="form-control is-invalid" name="customer_vat_no"
                                               id="customer_vat_no" placeholder="@lang('customer.vat_no')" required>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('customer.vat_rate') </label>
                                        <input type="text" class="form-control is-invalid" name="customer_vat_rate" value = .15
                                               id="customer_vat_rate" placeholder="@lang('customer.vat_rate')" required>
                                    </div>
                                             

                                   
                                            <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('home.system_code_acc_id') </label>
                                                    <select class="selectpicker"  data-live-search="true" name="customer_account_id"
                                                            id="customer_account_id" required>
                                                        <option value="" selected>@lang('home.choose')</option>
                                                        @foreach($accountL as $accountLs)
                                                            <option value="{{ $accountLs->acc_id }}"
                                                           >
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
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('customer.work_mobile') </label>
                                        <input type="number" class="form-control" name="customer_phone"
                                               id="customer_phone" placeholder="@lang('customer.work_mobile')" required>

                                    </div>

                                    <div class="col-md-2">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('customer.email_work') </label>
                                        <input type="email" class="form-control" name="customer_email"
                                               id="customer_email" placeholder="@lang('customer.email_work')" >
                                    </div>

                                    <div class="col-md-2">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('customer.lemet_balance') </label>
                                        <input type="text" class="form-control" name="customer_credit_limit"
                                               id="customer_credit_limit" placeholder="@lang('customer.lemet_balance')"
                                               >
                                    </div>

                                    <div class="col-md-2">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.po_box_postal') </label>
                                        <input type="text" class="form-control" name="postal_box"
                                               id="postal_box" placeholder="@lang('home.po_box_postal')"
                                               >
                                    </div>

                                    <div class="col-md-2">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('customer.city') </label>
                                        <input type="text" class="form-control" name="customer_address_2"
                                               id="customer_address_2" placeholder="@lang('customer.city')"
                                               >
                                    </div>

                                </div>
                            </div>


                            <div class="mb-2">
                            <div class="row">

                            
                                    <div class="col-md-6">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('customer.customer_address_ar') </label>
                                        <input type="text" class="form-control" name="customer_address_1"
                                               id="customer_address_1" placeholder="@lang('customer.customer_address')"
                                               >
                                    </div>
                                    <div class="col-md-2">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.postal_code') </label>
                                        <input type="text" class="form-control" name="postal_code"
                                               id="postal_code" placeholder="@lang('home.postal_code')"
                                               >
                                    </div>
                                    <div class="col-md-2">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('customer.build_no') </label>
                                        <input type="text" class="form-control " name="build_no"
                                               id="build_no" placeholder="@lang('customer.build_no')" >
                                    </div>

                                    <div class="col-md-2">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('customer.unit_no') </label>
                                        <input type="text" class="form-control" name="unit_no"
                                               id="unit_no" placeholder="@lang('customer.unit_no')" >
                                    </div>
                                             
                                    <div class="col-md-12">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('customer.customer_address_en') </label>
                                        <input type="text" class="form-control" name="customer_address_en"
                                               id="customer_address_en" placeholder="@lang('customer.customer_address_en')" >
                                    </div>

                                </div>
                                <div class="row">
                               


                                    </div> 
                            </div>


                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary mr-2"
                                        data-bs-dismiss="modal" id="create_emp">@lang('home.save')</button>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script>
    $(document).ready(function () {

        //     //    validation to create customer

        $('#customer_name_full_ar').change(function () {
            if ($('#customer_name_full_ar').val().length < 3) {
                $('#customer_name_full_ar').addClass('is-invalid')
                $('#create_emp').attr('disabled', 'disabled')
            } else {
                $('#customer_name_full_ar').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            }
        });

       

        $('#customer_name_1_ar').keyup(function () {
            if ($('#customer_name_1_ar').val().length < 3) {
                $('#customer_name_1_ar').addClass('is-invalid')
                $('#create_emp').attr('disabled', 'disabled')
            } else {
                $('#customer_name_1_ar').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            }
        });
       
        $('#customer_name_4_ar').keyup(function () {
            if ($('#customer_name_4_ar').val().length < 3) {
                $('#customer_name_4_ar').addClass('is-invalid')
                $('#create_emp').attr('disabled', 'disabled')
            } else {
                $('#customer_name_4_ar').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            }
        });

       


        $('#customer_nationality').change(function () {
            if (!$('#customer_nationality').val()) {
                $('#customer_nationality').addClass('is-invalid')
                $('#create_emp').attr('disabled', 'disabled')
            } else {
                $('#customer_nationality').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            }
        });

        $('#customer_identity').keyup(function () {
            if ($('#customer_identity').val().length < 9) {
                $('#customer_identity').addClass('is-invalid')
                $('#create_emp').attr('disabled', 'disabled')
            } else {
                $('#customer_identity').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            }
        });

        $('#customer_status').change(function () {
            if (!$('#customer_status').val()) {
                $('#customer_status').addClass('is-invalid')
                $('#create_emp').attr('disabled', 'disabled')
            } else {
                $('#customer_status').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            }
        });



        $('#customer_mobile').keyup(function () {
            if ($('#customer_mobile').val().length < 9) {
                $('#customer_mobile').addClass('is-invalid')
                $('#create_emp').attr('disabled', 'disabled')
            } else {
                $('#customer_mobile').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            }
        });


        $('#customer_credit_limit').keyup(function () {
            if ($('#customer_credit_limit').val().length < 4) {
                $('#customer_credit_limit').addClass('is-invalid')
                $('#create_emp').attr('disabled', 'disabled')
            } else {
                $('#customer_credit_limit').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            }
        });


        $('#customer_mobile_code').change(function () {
            if (!$('#customer_mobile_code').val()) {
                $('#customer_mobile_code').addClass('is-invalid')
                $('#create_emp').attr('disabled', 'disabled')
            } else {
                $('#customer_mobile_code').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            }
        });



        $('#customer_type').change(function () {
            if (!$('#customer_type').val()) {
                $('#customer_type').addClass('is-invalid')
                $('#create_emp').attr('disabled', 'disabled')
            } else {
                $('#customer_type').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            }
        });


    function validEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
})
</script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
<script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
<script>

    new Vue({
        el: '#app',
        data: {
            customer_name_1_ar: '',
            customer_name_2_ar: '',
            customer_name_3_ar: '',
            customer_name_4_ar: '',

            customer_name_1_en: '',
            customer_name_2_en: '',
            customer_name_3_en: '',
            customer_name_4_en: '',

            customer_category: 2 ,
            full_ar: true,
            full_en: true,
            company_id: '',
            branches:{}
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
        },
        computed: {
            customer_name_full_ar: function () {
                // `this` points to the vm instance

                var str = this.customer_name_1_ar + ' ' + this.customer_name_2_ar + ' ' + this.customer_name_3_ar + ' ' + this.customer_name_4_ar
                if (str.trim().length > 0) {
                    this.full_ar = false;
                } else {
                    this.full_ar = true
                }
                return str;
            },

            customer_name_full_en: function () {
                // `this` points to the vm instance
                this.full_en = false
                var str = this.customer_name_1_en + ' ' + this.customer_name_2_en + ' ' + this.customer_name_3_en + ' ' + this.customer_name_4_en
                if (str.trim().length > 0) {
                    this.full_en = false;
                } else {
                    this.full_en = true
                }
                return str;
            },
        }
    })

</script>
@endsection

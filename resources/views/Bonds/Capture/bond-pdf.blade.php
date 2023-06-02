<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            box-sizing: border-box;
        }

        input[type=text], select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }

        label {
            padding: 12px 12px 12px 0;
            display: inline-block;
        }

        input[type=submit] {
            background-color: #04AA6D;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        .container {
            border-radius: 5px;
            background-color: #f2f2f2;
            padding: 20px;
        }

        .col-25 {
            float: left;
            width: 25%;
            margin-top: 6px;
        }

        .col-75 {
            float: left;
            width: 75%;
            margin-top: 6px;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* Responsive layout - when the screen is less than 600px wide, make the two columns stack on top of each other instead of next to each other */
        @media screen and (max-width: 600px) {
            .col-25, .col-75, input[type=submit] {
                width: 100%;
                margin-top: 0;
            }
        }
    </style>
</head>
<body>

<div class="section-body mt-3" id="app">
    <div class="container-fluid">
        <div class="row clearfix">

            <div class="col-lg-12">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">@lang('home.sub_company')</label>
                                <input type="text" class="form-control" disabled=""
                                       value="{{ app()->getLocale()=='ar' ? $bond->company->company_name_ar :
                                               $bond->company->company_name_en }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">@lang('home.branch')</label>
                                <input type="text" class="form-control" disabled=""
                                       value="{{ app()->getLocale()=='ar' ? $bond->
                                               branch->branch_name_ar : $bond->branch->branch_name_en }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">@lang('home.created_date')</label>
                                <input type="text" value="{{ $bond->bond_date }}" class="form-control"
                                       disabled="">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">@lang('home.user')</label>
                                <input type="text" class="form-control" disabled=""
                                       value="{{ app()->getLocale()=='ar' ? $bond->userCreated->user_name_ar :
                                                    $bond->userCreated->user_name_en }}">
                            </div>
                        </div>

                        {{--النشاط--}}
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('home.bonds_activity')</label>
                                <input type="text" disabled="" value="{{ app()->getLocale()=='ar' ?
                                         $bond->transactionType->app_menu_name_ar : $bond->transactionType->app_menu_name_en }}"
                                       class="form-control">

                            </div>
                        </div>

                        {{--الرقم المرجعي--}}
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('home.reference_number')</label>
                                <input type="text" class="form-control" disabled=""
                                       value="{{ $bond->bond_ref_no }}" name="bond_ref_no" required>
                            </div>
                        </div>


                        {{--نوع الحساب--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.account_type')</label>
                                @if($bond->customer_type == 'customer')

                                    <input type="text" disabled="" class="form-control" value="{{app()->getLocale()=='ar' ?
                                     $bond->customer->cus_type->system_code_name_ar : $bond->customer->cus_type->system_code_name_en }}">
                                @elseif($bond->customer_type == 'employee')
                                    <input type="text" disabled="" class="form-control" value="{{app()->getLocale()=='ar' ?
                                              'موظف' : 'employee'}}">
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">

                                <label class="form-label">@lang('home.customer')</label>

                                @if($bond->customer_type == 'customer')
                                    <input type="text" disabled="" class="form-control" value="{{ app()->getLocale()=='ar' ?
                                             $bond->customer->customer_name_full_ar :    $bond->customer->customer_name_full_en }}">
                                @elseif($bond->customer_type == 'employee')
                                    <input type="text" disabled="" value="{{ app()->getLocale()=='ar' ?
                                             $bond->customer->emp_name_full_ar :    $bond->customer->emp_name_full_en }}">
                                @endif
                            </div>
                        </div>

                        {{--{قم الحساب --}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.account_code')</label>
                                <input type="text" class="form-control" disabled=""
                                       name="bond_acc_id" {{ $bond->bond_acc_id ? $bond->bond_acc_id : ''}}>
                            </div>
                        </div>

                        {{--انواع الايرادات--}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('home.revenue_types')</label>

                                <input type="text" class="form-control" disabled="" value="{{app()->getLocale()=='ar' ? $bond->bondDocType->system_code_name_ar :
                                        $bond->bondDocType->system_code_name_en }}">
                            </div>
                        </div>

                        {{--طرق الدفع--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.payment_method')</label>
                                <input type="text" class="form-control" disabled=""
                                       value="{{ app()->getLocale()=='ar' ? $bond->paymentMethod->system_code_name_ar :
                                         $bond->paymentMethod->system_code_name_en }}">
                            </div>
                        </div>

                        {{--رقم العمليه--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.process_number')</label>
                                <input type="text" class="form-control" disabled=""
                                       value="{{ $bond->bond_check_no ? $bond->bond_check_no : ''}}">
                            </div>
                        </div>

                        {{--البنك--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.bank')</label>
                                <input type="text" class="form-control" disabled=""
                                       value="{{  $bond->bank ? $bond->bank->system_code_name_en : ''}}">
                            </div>
                        </div>

                        {{--القيمه--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.value')</label>
                                <input type="text" class="form-control" disabled=""
                                       value="{{ $bond->bond_amount_debit}}" name="bond_amount_debit">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script>
    new Vue({
        el: "#app",
        mounted() {
            window.print();
        }
    })
</script>

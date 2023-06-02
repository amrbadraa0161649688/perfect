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

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="font-25">
                                        {{__('cars deliver')}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">

                            {{--@include('Includes.flash-messages')--}}
                            @if(Session()->get('error'))
                                <div class="alert alert-danger">
                                    {{Session()->get('error')}}
                                </div>
                            @endif

                            <form action="{{route('WaybillCarDeliver.store')}}" method="post">
                                @csrf
                                <input type="hidden" name="customer_type_code" :value="customer_type_code">
                                <input type="hidden" name="customer_id" :value="customer_id">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">@lang('home.companies')</label>
                                                    <select class="form-control" v-model="company_id" name="company_id"
                                                            required>
                                                        <option value="">@lang('home.choose')</option>
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
                                                <div class="col-md-4">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('invoice.customer_name') </label>
                                                    <select class="selectpicker" name="customer_id"
                                                            id="customer_id" required
                                                            data-live-search="true"
                                                            v-model="customer_id"
                                                            @change="getCarWaybills()">
                                                        <option value="" selected>@lang('home.choose')</option>
                                                        @foreach($customers as $customer)
                                                            <option value="{{$customer->customer_id }}">
                                                                @if(app()->getLocale() == 'ar')
                                                                    {{ $customer->customer_name_full_ar }}
                                                                @else
                                                                    {{ $customer->customer_name_full_en }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">@lang('home.user')</label>
                                                    <input type="text" calss="form-control" readonly
                                                           class="form-control"
                                                           value="@if(app()->getLocale()=='ar') {{ auth()->user()->user_name_ar }}
                                                           @else {{ auth()->user()->user_name_en }} @endif">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">

                                    <div class="col-md-4">
                                        <input type="date" class="form-control"
                                               @change="getCarWaybills()"
                                               name="from_date" v-model="from_date">
                                        <small v-if="date_error_message" class="text-danger">@{{
                                            date_error_message }}
                                        </small>
                                    </div>

                                    <div class="col-md-4">
                                        <input type="date" class="form-control" name="to_date"
                                               v-model="to_date"
                                               @change="getCarWaybills()">
                                        <small v-if="date_error_message" class="text-danger">@{{
                                            date_error_message }}
                                        </small>
                                    </div>

                                    <div class="col-md-4">
                                        <select v-if="customer_type_code==538" required name="payment_method_id"
                                                class="form-control">
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($sys_codes_payment_methods as $sys_codes_payment_method)
                                                <option value="{{$sys_codes_payment_method->system_code_id}}">
                                                    {{app()->getLocale()=='ar' ? $sys_codes_payment_method->system_code_name_ar :
                                                    $sys_codes_payment_method->system_code_name_en}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4"
                                         id="waybill_receiver_name_2">
                                        <label for="recipient-name"
                                               class="form-label"> @lang('waybill.receiver_name') </label>
                                        <input type="text" class="form-control"
                                               id="receiver_name" name="receiver_name" required>

                                    </div>

                                    <div class="col-md-4"
                                         id="waybill_receiver_mobile_code_2">
                                        <label for="recipient-name"
                                               class="form-label"> @lang('home.receiver_identity') </label>
                                        <input type="number" class="form-control"
                                               id="receiver_id" name="receiver_id" required>
                                    </div>

                                    <div class="col-md-4" v-if="customer_type_code == 538">
                                        <label for="recipient-name"
                                               class="form-label"> {{__('total')}} </label>
                                        <input type="number" class="form-control" name="total" :value="total" readonly>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-condensed">
                                            <thead class="thead-light">
                                            <tr>
                                                <th>
                                                    <label>@lang('home.select_all')</label>
                                                    <input type="checkbox" id="selectall">
                                                </th>
                                                <th style="width:250px"
                                                    class="text-center">@lang('home.waybill_no')</th>
                                                <th style="width:150px"
                                                    class="text-center">@lang('invoice.load_date')</th>
                                                <th style="font-size: 14px ;font-weight: bold;color: blue ;width:50px"
                                                    class="text-center">@lang('home.trips')</th>
                                                <th style="width:150px"
                                                    class="text-center">@lang('home.waybill_item')</th>
                                                <th style="width:150px"
                                                    class="text-center">@lang('home.from')</th>
                                                <th style="width:150px"
                                                    class="text-center">@lang('home.to')</th>
                                                <th style="width:150px"
                                                    class="text-center">@lang('home.waybill_car_chase')</th>
                                                <th style="font-size: 16px ;font-weight: bold;width:150px"
                                                    class="text-center">@lang('home.waybill_car_plate')</th>
                                                <th style="width:150px"
                                                    class="text-center">@lang('home.waybill_car_desc')</th>
                                                <th hidden style="width:140px"
                                                    class="text-center">@lang('home.waybill_item_amount')</th>
                                                <th hidden style="width:140px"
                                                    class="text-center">@lang('home.waybill_vat_amount')</th>

                                                <th style="width:160px"
                                                    class="text-center"
                                                    v-if="customer_type_code == 538">{{__('due_amount')}}</th>

                                                <th style="width:160px"
                                                    class="text-center">@lang('invoice.total')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td></td>
                                                <td><input type="text" class="form-control" v-model="waybill_code"
                                                           placeholder="@lang('home.waybill_no')"></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><input type="text" class="form-control"
                                                           v-model="waybill_loc_from"
                                                           placeholder="@lang('home.from')"></td>
                                                <td><input type="text" class="form-control"
                                                           v-model="waybill_loc_to"
                                                           placeholder="@lang('home.to')"></td>
                                                <td></td>
                                                <td><input type="text" class="form-control" v-model="waybill_car_plate"
                                                           placeholder="@lang('home.waybill_car_plate')"></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="clone" v-for="waybill in filteredWaybills4">

                                                <td>
                                                    <input type="checkbox" :value="waybill.waybill_id"
                                                           v-model="waybill_id"
                                                           name="waybill_id[]" class="checkboxSelection">
                                                </td>
                                                <td>
                                                    <a :href="'{{env("APP_URL")}}'+'/Waybillcar-add/' + waybill.waybill_id +'/edit' "
                                                       class="btn btn-link btn-sm" target="_blank">
                                                        @{{ waybill.waybill_code }}
                                                    </a>


                                                </td>
                                                <td style="font-size: 14px ;font-weight: bold">
                                                    @{{waybill.waybill_load_date }}
                                                </td>
                                                <td style="font-size: 12px ;font-weight: bold;color: blue ;width:50px">
                                                    @{{ waybill.waybill_trip_id }}
                                                </td>
                                                <td style="font-size: 10px ;font-weight: bold">
                                                    @if(app()->getLocale()=='ar')
                                                        @{{ waybill.item_name_ar }}
                                                    @else
                                                        @{{ waybill.item_name_en }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(app()->getLocale() == 'ar')
                                                        @{{ waybill.waybill_loc_from_ar }}
                                                    @else
                                                        @{{ waybill.waybill_loc_from_en }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(app()->getLocale() == 'ar')
                                                        @{{ waybill.waybill_loc_to_ar }}
                                                    @else
                                                        @{{ waybill.waybill_loc_to_en }}
                                                    @endif
                                                </td>
                                                <td> @{{ waybill.waybill_car_chase }}</td>
                                                <td style="font-size: 16px ;font-weight: bold"> @{{
                                                    waybill.waybill_car_plate }}
                                                </td>
                                                <td> @{{ waybill.waybill_car_desc }}</td>
                                                <td hidden><input hidden type="number" class="form-control"
                                                                  name="waybill_item_amount[]" readonly
                                                                  :value="(waybill.waybill_item_amount * waybill.waybill_item_quantity)">
                                                </td>
                                                <td hidden><input hidden type="number" class="form-control"
                                                                  name="waybill_vat_amount[]" readonly
                                                                  :value="waybill.waybill_vat_amount"></td>


                                                <td style="width:160px"
                                                    class="text-center"
                                                    v-if="customer_type_code == 538">
                                                    <input type="number" class="form-control"
                                                           name="waybill_due_amount[]" readonly
                                                           :value="waybill.waybill_due_amount">
                                                </td>

                                                <td><input type="number" class="form-control"
                                                           name="waybill_total_amount[]"
                                                           style="font-size: 14px ;font-weight: bold" step="0.01"
                                                           :value="waybill.waybill_total_amount" readonly></td>

                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <button class="btn btn-primary mt-2" type="submit"
                                                :disabled="disable_button">
                                            @lang('home.save')</button>
                                    </div>
                                </div>

                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function () {


            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (day < 10 ? '0' : '') + day + '/' +
                (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
            ;
            $('#invoice_date').val(output)


            $("#selectall").change(function () {
                if ($(this).is(":checked")) {
                    $(".checkboxSelection").each(function () {
                        $(this).prop('checked', true);
                    });
                }
                else {
                    $(".checkboxSelection").each(function () {
                        $(this).prop('checked', false);
                    });
                }
            });


            $(".checkboxes").change(function () {
                if ($(this).is(":checked")) {
                    $(".checkboxes").each(function () {
                        $(this).prop('required', false);
                    });
                }
                else {
                    $(".checkboxes").each(function () {
                        $(this).prop('required', true);
                    });
                }
            });

        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>

    <script>
        new Vue({
            el: '#app',
            data: {
                customer_id: '',
                company_id: '',
                waybills: [],
                from_date: '',
                to_date: '',
                date_error_message: '',
                isLoaded: false,
                waybill_code: '',
                waybill_loc_from: '',
                waybill_loc_to: '',
                waybill_car_plate: '',
                disable_button: true,
                customer_type_code: '',
                waybill_id: []
            },
            methods: {
                getCarWaybills() {
                    this.date_error_message = ''

                    this.waybills = []
                    this.disable_button = true
                    if (!this.from_date || !this.to_date) {
                        this.date_error_message = 'برجاء ادخال التاريخ'
                        this.disable_button = true
                    } else {
                        this.date_error_message = ''
                        this.disable_button = false

                        if (this.company_id && this.customer_id && this.from_date && this.to_date) {
                            $.ajax({
                                type: 'GET',
                                data: {
                                    customer_id: this.customer_id, company_id: this.company_id,
                                    from_date: this.from_date, to_date: this.to_date
                                },
                                url: '{{route('WaybillCarDeliver.getCustomerWaybills')}}'
                            }).then(response => {
                                this.isLoaded = true
                                this.waybills = response.data
                                this.customer_type_code = response.customer_type_code
                                if (this.waybills.length > 0) {
                                    this.disable_button = false
                                } else {
                                    this.disable_button = true
                                }
                            })
                        }
                    }
                }
            },
            computed: {
                filteredWaybills: function () {
                    if (this.isLoaded == true) {
                        return this.waybills.filter(waybill => {
                            return waybill.waybill_code.match(this.waybill_code)
                        })
                    }
                },
                filteredWaybills2: function () {
                    if (this.isLoaded == true) {
                        return this.filteredWaybills.filter(waybill => {
                            return waybill.waybill_loc_from_ar.match(this.waybill_loc_from)
                        })
                    }
                },
                filteredWaybills3: function () {
                    if (this.isLoaded == true) {
                        return this.filteredWaybills2.filter(waybill => {
                            return waybill.waybill_loc_to_ar.match(this.waybill_loc_to)
                        })
                    }
                },
                filteredWaybills4: function () {
                    if (this.isLoaded == true) {
                        return this.filteredWaybills3.filter(waybill => {
                            return waybill.waybill_car_plate.match(this.waybill_car_plate)
                        })
                    }
                },
                total: function () {
                    if (this.isLoaded == true) {
                        let results = this.filteredWaybills4.filter(f => this.waybill_id.indexOf(f.waybill_id) > -1);

                        let total = 0;
                        Object.entries(results).forEach(([key, val]) => {
                            total += (parseFloat(val.waybill_due_amount))
                        });
                        return total.toFixed(2);
                    } else {
                        return 0;
                    }

                }
            }
        })
    </script>
@endsection
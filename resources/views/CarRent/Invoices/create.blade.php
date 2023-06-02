@extends('Layouts.master')
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

<style>
    .bootstrap-select {
        width: 100% !important;
    }
</style>
@section('content')

    <div class="section-body py-3" id="app">
        <div class="container-fluid">

            @include('Includes.form-errors')
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="font-25" bold>
                                        @lang('invoice.add_new_invoice')
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">

                            <form action="{{ route('car-rent.invoices.store') }}" method="post">
                                @csrf

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="row">

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('home.companies')</label>
                                                    <select class="form-control" v-model="company_id" name="company_id"
                                                            @change="getAccountPeriodsOfCompany()"
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

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('home.account_periods')</label>
                                                    <select class="form-control"
                                                            name="acc_period_id" required>
                                                        <option value="">@lang('home.choose')</option>
                                                        <option v-for="account_period in account_periods"
                                                                :value="account_period.acc_period_id">
                                                            @if(app()->getLocale() == 'ar')
                                                                @{{ account_period.acc_period_name_ar }}
                                                            @else
                                                                @{{ account_period.acc_period_name_en }}
                                                            @endif
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('home.created_date')</label>
                                                    <input type="text" class="form-control" name="invoice_date"
                                                           id="invoice_date"
                                                           placeholder="@lang('invoice.invoice_date')" readonly>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('home.user')</label>
                                                    <input type="text" readonly
                                                           class="form-control"
                                                           value="@if(app()->getLocale()=='ar') {{ auth()->user()->user_name_ar }}
                                                           @else {{ auth()->user()->user_name_en }} @endif">
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="form-label"> @lang('invoice.customer_name') </label>
                                        <select class="selectpicker" name="customer_id"
                                                id="customer_id" required
                                                data-live-search="true"
                                                v-model="customer_id"
                                                @change="getcustomertype();getCustomerContracts()">
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

                                    <div class="col-md-3">
                                        <label class="form-label">@lang('invoice.invoice_due_date')</label>
                                        <input type="date" class="form-control" name="invoice_due_date"
                                               id="invoice_due_date"
                                               placeholder="@lang('invoice.invoice_due_date')" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label>@lang('home.invoice_notes')</label>
                                        <textarea class="form-control" name="invoice_notes"></textarea>
                                    </div>

                                </div>


                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('invoice.customer_name') </label>
                                        <input type="text" class="form-control is-invalid"
                                               name="customer_name"
                                               id="customer_name" :value="customer_name"
                                               placeholder="@lang('invoice.customer_name')" required>

                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('invoice.customer_address') </label>
                                        <input type="text" class="form-control is-invalid"
                                               name="customer_address"
                                               id="customer_address" :value="customer_address"
                                               placeholder="@lang('invoice.customer_address')" required>

                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('invoice.customer_tax_no') </label>
                                        <input type="text" class="form-control is-invalid"
                                               name="customer_tax_no"
                                               id="customer_tax_no" :value="customer_tax_no"
                                               placeholder="@lang('invoice.customer_tax_no')" required>

                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('invoice.customer_phone') </label>
                                        <input type="text" class="form-control is-invalid"
                                               name="customer_phone"
                                               id="customer_phone" :value="customer_phone"
                                               placeholder="@lang('invoice.customer_phone')" required>

                                    </div>
                                </div>


                                {{--جدول العقود الفمتوحه--}}

                                <div class="row clearfix">
                                    <div class="col-lg-12">
                                        <p>@lang('home.opened_contracts')</p>
                                    </div>
                                </div>

                                <div class="row clearfix">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0">
                                                        <thead>
                                                        <tr class="table-info">
                                                            <th></th>
                                                            <th>@lang('home.contract_code')</th>
                                                            <th>@lang('home.contract_start_date_time')</th>
                                                            <th>@lang('carrent.car_plate')</th>
                                                            <th>@lang('carrent.car_model')</th>

                                                            {{--<th>@lang('carrent.car_model_year')</th>--}}
                                                            <th>@lang('home.contract_type')</th>
                                                            <th>@lang('home.daily_cost')</th>
                                                            <th>@lang('home.from_date')</th>
                                                            <th>@lang('home.to_date')</th>
                                                            <th>@lang('home.contract_period')</th>
                                                            <th>@lang('home.total_rent')</th>
                                                            {{--<th>@lang('home.discount_value')</th>--}}
                                                            <th style="width:25%">@lang('home.attachment_data')</th>
                                                            <th style="width:25%">@lang('home.vat_amount')</th>

                                                            <th>
                                                                @lang('home.total_due')</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        <p v-if="opened_contracts_message">
                                                            @{{opened_contracts_message}}</p>
                                                        <tr v-for="opened_contract,index in opened_contracts">
                                                            <input type="hidden" name="contract_id[]"
                                                                   :value="opened_contract.contract_id">
                                                            <td><input type="checkbox"
                                                                       :value="opened_contract.contract_id"></td>

                                                            <td>@{{ opened_contract.contract_code }}</td>
                                                            <td>@{{opened_contract.contractStartDate}}</td>
                                                            <td>@{{opened_contract.full_car_plate}}</td>
                                                            <td>
                                                                @if(app()->getLocale()=='ar')
                                                                    @{{opened_contract.model_name_ar}}
                                                                @else
                                                                    @{{opened_contract.model_name_ar}} @endif
                                                            </td>

                                                            {{--<td>@{{opened_contract.car_model_year}}</td>--}}

                                                            <td>
                                                                @if(app()->getLocale()=='ar')
                                                                    @{{opened_contract.contract_type_ar}}
                                                                @else
                                                                    @{{opened_contract.contract_type_en}} @endif
                                                            </td>

                                                            <td> @{{opened_contract.rentDayCost}}</td>
                                                            <td>
                                                                <input type="date" class="form-control"
                                                                       v-model="opened_contracts[index]['from_date']"
                                                                       @change="getDifferenceDate(index)">
                                                            </td>
                                                            <td>
                                                                <input type="date" class="form-control"
                                                                       v-model="opened_contracts[index]['to_date']"
                                                                       @change="getDifferenceDate(index)">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       v-model="opened_contracts[index]['days_count']"
                                                                       name="days_count[]" readonly>

                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       v-model="opened_contracts[index]['total_daily_cost']"
                                                                       name="invoice_item_amount[]" readonly>
                                                            </td>

                                                            {{--<td> @{{opened_contract.discount_value}}</td>--}}

                                                            <td style="width:25%">
                                                                <textarea class="form-control"
                                                                          name="invoice_item_notes[]"
                                                                          required></textarea>
                                                            </td>

                                                            <td>
                                                                {{--@{{ opened_contracts[index]['vat_rate'] }}--}}
                                                                {{----}}
                                                                <input type="text" class="form-control"
                                                                       v-model="opened_contracts[index]['vat_amount']"
                                                                       name="invoice_vat_amount[]" readonly>
                                                            </td>

                                                            <td>
                                                                <input type="text" class="form-control" readonly
                                                                       name="invoice_amount[]"
                                                                       :value="parseInt(opened_contracts[index]['total_daily_cost'] )+
                                                                       parseInt(opened_contracts[index]['vat_amount'])">

                                                            </td>

                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>



                                {{--جدول العقود المغلقه--}}

                                <div class="row clearfix">
                                    <div class="col-lg-12">
                                        <p>@lang('home.closed_contracts')</p>
                                    </div>
                                </div>

                                <div class="row clearfix">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0">
                                                        <thead>
                                                        <tr class="table-info">
                                                            <th></th>
                                                            <th>@lang('home.contract_code')</th>
                                                            <th>@lang('home.contract_start_date_time')</th>
                                                            <th>@lang('carrent.car_plate')</th>
                                                            <th>@lang('carrent.car_model')</th>
                                                            <th>@lang('home.contract_type')</th>
                                                            <th>@lang('home.daily_cost')</th>
                                                            <th>@lang('home.from_date')</th>
                                                            <th>@lang('home.to_date')</th>
                                                            <th>@lang('home.contract_period')</th>
                                                            <th>@lang('home.total_rent')</th>

                                                            {{--<th>@lang('home.discount_value')</th>--}}
                                                            <th style="width:25%">@lang('home.attachment_data')</th>
                                                            <th style="width:25%">@lang('home.vat_amount')</th>

                                                            <th>
                                                                @lang('home.total_due')</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        <tr v-if="closed_contracts_message">
                                                            <td colspan="14">
                                                                <p class="text-danger font-weight-bolder font-17">
                                                                    @{{closed_contracts_message}}</p>
                                                            </td>

                                                        </tr>

                                                        <tr v-for="closed_contract,index in closed_contracts">
                                                            <input type="hidden" name="contract_id[]"
                                                                   :value="closed_contract.contract_id">
                                                            <td><input type="checkbox"
                                                                       :value="closed_contract.contract_id"></td>

                                                            <td>@{{ closed_contract.contract_code }}</td>
                                                            <td>@{{closed_contract.contractStartDate}}</td>
                                                            <td>@{{closed_contract.full_car_plate}}</td>
                                                            <td>
                                                                @if(app()->getLocale()=='ar')
                                                                    @{{closed_contract.model_name_ar}}
                                                                @else
                                                                    @{{closed_contract.model_name_ar}} @endif
                                                            </td>

                                                            {{--<td>@{{opened_contract.car_model_year}}</td>--}}

                                                            <td>
                                                                @if(app()->getLocale()=='ar')
                                                                    @{{closed_contract.contract_type_ar}}
                                                                @else
                                                                    @{{closed_contract.contract_type_en}} @endif
                                                            </td>

                                                            <td> @{{closed_contract.rentDayCost}}</td>
                                                            <td>
                                                                <input type="date" class="form-control"
                                                                       v-model="closed_contracts[index]['from_date']"
                                                                       @change="getDifferenceDateClosed(index)">
                                                            </td>
                                                            <td>
                                                                <input type="date" class="form-control"
                                                                       v-model="closed_contracts[index]['closed_date']"
                                                                       @change="getDifferenceDateClosed(index)">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       v-model="closed_contracts[index]['days_count']"
                                                                       name="days_count[]" readonly>

                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       v-model="closed_contracts[index]['total_daily_cost']"
                                                                       name="invoice_item_amount[]" readonly>
                                                            </td>

                                                            {{--<td> @{{opened_contract.discount_value}}</td>--}}

                                                            <td style="width:25%">
                                                                <textarea class="form-control"
                                                                          name="invoice_item_notes[]"
                                                                          required></textarea>
                                                            </td>

                                                            <td>
                                                                {{--@{{ opened_contracts[index]['vat_rate'] }}--}}
                                                                {{----}}
                                                                <input type="text" class="form-control"
                                                                       v-model="closed_contracts[index]['vat_amount']"
                                                                       name="invoice_vat_amount[]" readonly>
                                                            </td>

                                                            <td>
                                                                <input type="text" class="form-control" readonly
                                                                       name="invoice_amount[]"
                                                                       :value="parseInt(closed_contracts[index]['total_daily_cost'] )+
                                                                       parseInt(closed_contracts[index]['vat_amount'])">

                                                            </td>

                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>



                                <div class="row clearfix">
                                    <div class="col-lg-12">
                                        <button class="btn btn-primary" type="submit">@lang('home.save')</button>
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
        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>

    <script>
        new Vue({
            el: '#app',
            data: {
                customer_id: '',
                company_id: '',
                account_periods: {},
                customer_tax_no: '',
                customer_address: '',
                customer_name: '',
                customer_phone: '',
                customer_type_obj: '',
                opened_contracts: [],
                opened_contracts_message: '',
                closed_contracts_message: '',
                closed_contracts: [],

            },
            methods: {
                getAccountPeriodsOfCompany() {
                    $.ajax({
                        type: 'GET',
                        data: {company_id: this.company_id},
                        url: '{{route('waybills-car.getCompanyAccountPeriods')}}'
                    }).then(response => {
                        this.account_periods = response.data
                    })
                },
                getcustomertype() {
                    this.customer_tax_no = ''
                    this.customer_address = ''
                    this.customer_name = ''
                    this.customer_phone = ''
                    this.customer_type_obj = {}

                    $.ajax({
                        type: 'GET',
                        data: {customer_id: this.customer_id},
                        url: '{{ route("api.waybill.customer.type") }}'
                    }).then(response => {
                        this.customer_tax_no = response.customer_tax_no
                        this.customer_address = response.customer_address
                        this.customer_name = response.customer_name
                        this.customer_phone = response.customer_mobile

                        this.customer_type_obj = response.data
                    })
                },
                getCustomerContracts() {
                    this.opened_contracts_message = ''
                    this.closed_contracts_message = ''
                    this.opened_contracts = []
                    this.closed_contracts = []
                    $.ajax({
                        type: 'GET',
                        data: {customer_id: this.customer_id},
                        url: '{{ route("car-rent.invoices.getCustomerContracts") }}'
                    }).then(response => {
                        this.opened_contracts = response.data
                        this.closed_contracts = response.closed_contracts

                        if (this.opened_contracts.length == 0) {
                            this.opened_contracts_message = 'لا يوجد عقود مفتوحه للعميل'
                        }
                        if (this.closed_contracts.length == 0) {
                            this.closed_contracts_message = 'لا يوجد عقود مغلقه للعميل'
                        }
                    })
                },
                getDifferenceDate(index) {
                    $.ajax({
                        type: 'GET',
                        data: {
                            from_date: this.opened_contracts[index]['from_date'],
                            to_date: this.opened_contracts[index]['to_date']
                        },
                        url: '{{ route("car-rent.invoices.getDifferenceDate") }}'
                    }).then(response => {
                        this.opened_contracts[index]['days_count'] = response.data;

                        this.opened_contracts[index]['total_daily_cost'] = parseInt(this.opened_contracts[index]['days_count']) *
                            parseInt(this.opened_contracts[index]['rentDayCost']);

                        this.opened_contracts[index]['vat_amount'] = ((this.opened_contracts[index]['vat_rate'] / 100) *
                            this.opened_contracts[index]['total_daily_cost']).toFixed(2)
                    })
                },
                getDifferenceDateClosed(index) {
                    $.ajax({
                        type: 'GET',
                        data: {
                            from_date: this.closed_contracts[index]['from_date'],
                            to_date: this.closed_contracts[index]['to_date']
                        },
                        url: '{{ route("car-rent.invoices.getDifferenceDate") }}'
                    }).then(response => {
                        this.closed_contracts[index]['days_count'] = response.data;

                        this.closed_contracts[index]['total_daily_cost'] = parseInt(this.closed_contracts[index]['days_count']) *
                            parseInt(this.closed_contracts[index]['rentDayCost']);

                        this.closed_contracts[index]['vat_amount'] = ((this.closed_contracts[index]['vat_rate'] / 100) *
                            this.closed_contracts[index]['total_daily_cost']).toFixed(2)
                    })
                }
            }
        });

    </script>

@endsection
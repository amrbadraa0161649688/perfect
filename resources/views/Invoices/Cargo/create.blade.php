@extends('Layouts.master')
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
                                    <div class="font-25" bold>
                                        @lang('invoice.add_new_invoice')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                            <form action="{{ route('Invoices.cargo.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="row">

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('home.companies')</label>
                                                    <select class="form-control" v-model="company_id" name="company_id"
                                                            @change="getAccountPeriodsOfCompany(); getCarWaybills()"
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
                                                    <input type="text" calss="form-control" readonly
                                                           class="form-control"
                                                           value="@if(app()->getLocale()=='ar') {{ auth()->user()->user_name_ar }}
                                                           @else {{ auth()->user()->user_name_en }} @endif">
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="row">

                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('invoice.customer_name') </label>
                                                    <select class="form-select form-control" name="customer_id"
                                                            v-model="customer_id"
                                                            id="customer_id" required @change="getCarWaybills()">
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
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-condensed">
                                            <thead class="bg-blue font-whait ">
                                            <tr>
                                                <th style="width:60px">
                                                    <label>@lang('home.select_all')</label>
                                                    <input type="checkbox" id="selectall">
                                                </th>
                                                <th style="width:160px"
                                                    class="text-center">@lang('home.waybill_no')</th>
                                                <th style="width:150px"
                                                    class="text-center">@lang('home.waybill_item')</th>

                                                <th style="width:200px"
                                                    class="text-center">@lang('invoice.waybill_no')</th>
                                                <th style="width:130px"
                                                    class="text-center">@lang('invoice.item_price')</th>
                                                <th style="width:130px"
                                                    class="text-center">@lang('invoice.item_amount')</th>
                                                <th style="width:130px"
                                                    class="text-center">@lang('home.waybill_item_amount')</th>
                                                <th style="width:130px"
                                                    class="text-center">@lang('home.waybill_vat_amount')</th>

                                                <th style="width:130px"
                                                    class="text-center">@lang('invoice.total')</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <tr class="clone" v-for="waybill in waybills">

                                                <td>
                                                    <input type="checkbox" :value="waybill.waybill_id"
                                                           name="waybill_id[]" class="checkboxSelection">
                                                </td>
                                                <td>
                                                    <a :href="'{{env("APP_URL")}}'+'/Waybillcargo2-add/' + waybill.waybill_id +'/edit' "
                                                       class="btn btn-link btn-sm" target="_blank">
                                                        @{{ waybill.waybill_code }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if(app()->getLocale()=='ar')
                                                        @{{ waybill.item_name_ar }}
                                                    @else
                                                        @{{ waybill.item_name_en }}
                                                    @endif
                                                </td>

                                                <td> @{{ waybill.waybill_ticket_no }}</td>
                                                <td> @{{waybill.waybill_item_amount }}</td>
                                                <td>@{{ waybill.waybill_add_amount }}</td>


                                                <td><input type="text" class="form-control"
                                                           name="waybill_item_amount[]" readonly
                                                           :value="waybill.waybill_total_amount - waybill.waybill_vat_amount">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                           name="waybill_vat_amount[]" readonly
                                                           :value="waybill.waybill_vat_amount"></td>
                                                <td><input type="text" class="form-control"
                                                           name="waybill_total_amount[]"
                                                           :value="waybill.waybill_total_amount" readonly></td>

                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-3">
                                        <button class="btn btn-primary mt-2" id="create_inv" type="submit">
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

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>

@section('scripts')
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

        })


    </script>


    <script>

        new Vue({
            el: '#app',
            data: {
                customer_id: '',
                company_id: '',
                waybills: {},
                account_periods: {}
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
                getCarWaybills() {
                    if (this.company_id && this.customer_id) {
                        $.ajax({
                            type: 'GET',
                            data: {customer_id: this.customer_id, company_id: this.company_id},
                            url: '{{route('waybills-custcargo.getWaybills')}}'
                        }).then(response => {
                            this.waybills = response.data
                        })
                    }


                }
            },
        });
    </script>
@endsection


@extends('Layouts.master')
@section('style')

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">

    <style lang="">
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200&display=swap');

        .v-application {
            font-family: "Cairo" !important;
        }
    </style>
@endsection

@section('content')

    <div class="section-body mt-3" id="app" style="font-family: 'Cairo', sans-serif">
        <v-app>
            <div class="container-fluid">
                <div class="row clearfix">
                    <div class="col-lg-12">
                        <form action="{{ route('journal-entries.store') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <h3 class="card-title">@lang('home.add_daily_restrictions')</h3>

                                <div class="row">
                                    {{--الشركات--}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.companies')</label>
                                            <select class="form-control" name="company_id"
                                                    v-model="company_id" @change="getBranches()" required>
                                                <option>@lang('home.choose')</option>
                                                @foreach($companies as $company_d)
                                                    <option value="{{$company_d->company_id}}">{{app()->getLocale() == 'ar' ?
                                                $company_d->company_name_ar : $company_d->company_name_en}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{--الفروع--}}
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.branch')</label>
                                            {{--<v-autocomplete--}}
                                            {{--required--}}
                                            {{--name="branch_id"--}}
                                            {{--:items="branches"--}}
                                            {{--item-value="branch_id"--}}
                                            {{--item-text="branch_name_ar"--}}
                                            {{--label="@lang('home.branches')"--}}
                                            {{--></v-autocomplete>--}}

                                            <select class="form-control" name="branch_id" v-model="branch_id">

                                                <option v-for="branch in branches" :value="branch.branch_id">
                                                    @if(app()->getLocale()=='ar')
                                                        @{{ branch.branch_name_ar }}
                                                    @else
                                                        @{{ branch.branch_name_en }}
                                                    @endif
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    {{--انواع يوميات قيود الحسابات--}}
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.daily_accounts_type')</label>
                                            <select name="journal_type_id" class="form-control" required>
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($journal_types as $journal_type)
                                                    <option value="{{ $journal_type->system_code_id }}"
                                                            @if($journal_type->system_code == 801) selected @endif>
                                                        {{ app()->getLocale()=='ar' ?
                                                    $journal_type->system_code_name_ar :
                                                    $journal_type->system_code_name_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{--تاريخ اليوم--}}
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.date')</label>
                                            <input type="text" id="restriction_date"
                                                   class="form-control" disabled="">
                                        </div>
                                    </div>

                                    {{--تاريخ اليوميه--}}
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.date')</label>
                                            <input type="date" class="form-control" name="journal_hd_date" required>
                                        </div>
                                    </div>

                                    {{--رقم الملف--}}
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.file_serial')</label>
                                            <input type="number" class="form-control" placeholder="10001" value=0
                                                   name="journal_file_no" required>
                                        </div>
                                    </div>

                                    {{--حاله القيود اليوميه--}}
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.restriction_account_status')</label>
                                            @if($flag>0)
                                                <select class="custom-select" name="journal_status" required>
                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($journal_statuses as $journal_status)
                                                        <option value="{{$journal_status->system_code_id}}">
                                                            {{app()->getLocale()=='ar' ? $journal_status->system_code_name_ar
                                                            : $journal_status->system_code_name_en}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else

                                                <input type="text" class="form-control" value="{{$journal_statuses
                                            ->where('system_code',902)->first()->system_code_name_ar}}" readonly>
                                            @endif
                                        </div>
                                    </div>

                                    {{--المستخدم--}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.user')</label>
                                            <input type="text" class="form-control"
                                                   value="{{ app()->getLocale() == 'ar' ? auth()->user()->user_name_ar :
                                                auth()->user()->user_name_en }}" disabled="">
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12">
                                        <label>@lang('home.notes')</label>
                                        <textarea class="form-control" name="journal_hd_notes"
                                        > </textarea>
                                    </div>

                                </div>
                            </div>

                            <div class="card">

                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="pl-0" style="width: 350px">@lang('home.account_name')</th>
                                                <th></th>
                                                <th colspan="2" style="width: 600px">@lang('home.notes')</th>

                                                <th class="pr-0">@lang('home.debit')</th>
                                                <th class="pr-0">@lang('home.credit')</th>
                                                <th class="pr-0"></th>
                                            </tr>
                                            </thead>

                                            <tr v-for="(journal,index) in journals">
                                                <td>
                                                    <br>
                                                    <br>
                                                    @{{index+1}}
                                                </td>
                                                <td class="pl-0">
                                                    {{--<small v-if="journals[index]['account'].length > 0"--}}
                                                    {{--class="text-danger">--}}
                                                    {{--@{{ journals[index]['account'][0].acc_code }}--}}
                                                    {{--</small>--}}

                                                    <v-autocomplete
                                                            required
                                                            v-model="journals[index]['account_id']"
                                                            :items="accounts"
                                                            @change="getSelectedAccount(index)"
                                                            item-value="acc_id"
                                                            item-text="acc_name_ar"
                                                            :label="journals[index]['account'].length > 0 ?
                                                             journals[index]['account'][0].acc_code : 'accounts'"
                                                    ></v-autocomplete>

                                                    <input type="hidden" name="account_id[]"
                                                           v-model="journals[index]['account_id']">
                                                </td>

                                                <td>
                                                    <br>
                                                    <br>
                                                    <button class="btn btn-link" type="button" style="font-weight: bold"
                                                            data-toggle="modal"
                                                            :data-target="'#exampleModalCenter'+index">
                                                        <i class="fa fa-paperclip fa-2x"></i>
                                                    </button>
                                                </td>

                                                {{--الملاحظات--}}
                                                <td colspan="2">
                                                    <label v-if="journals[index]['show_customers']">@lang('home.customers')
                                                        @{{journals[index]['cc_customer'][0] ?
                                                        journals[index]['cc_customer'][0].customer_name_full_ar :
                                                        ''}}</label>
                                                    <label v-if="journals[index]['show_employees']">@lang('home.employees')
                                                        @{{journals[index]['cc_employee'][0] ?
                                                        journals[index]['cc_employee'][0].emp_name_full_ar :
                                                        ''}}</label>
                                                    <label v-if="journals[index]['show_suppliers']">@lang('home.suppliers')
                                                        @{{journals[index]['cc_supplier'][0] ?
                                                        journals[index]['cc_supplier'][0].customer_name_full_ar :
                                                        ''}}</label>
                                                    <label v-if="journals[index]['show_cars']">@lang('home.cars')
                                                        @{{journals[index]['cc_car'][0] ?
                                                        journals[index]['cc_car'][0].truck_name :
                                                        ''}}</label>
                                                    <label v-if="journals[index]['show_branches']">@lang('home.branches')
                                                        @{{journals[index]['cc_branch'][0] ?
                                                        journals[index]['cc_branch'][0].branch_name_ar :
                                                        journals[index]['cc_branch'].branch_name_ar}}</label>
                                                    <input type="text" class="form-control" name="journal_dt_notes[]"
                                                           v-model="journals[index]['journal_dt_notes']">

                                                    <small class="text-danger"
                                                           v-if="!journals[index]['cost_center_type_id']">
                                                        البيانات غير كامله
                                                    </small>


                                                    <small style="color:#dc3545" v-if="journals[index]['show_suppliers'] &&
                                                     !journals[index]['cc_supplier_id']">البيانات غير كامله
                                                    </small>

                                                    <small style="color:#dc3545" v-if="journals[index]['show_customers'] &&
                                                     !journals[index]['cc_customer_id']">البيانات غير كامله
                                                    </small>

                                                    <small style="color:#dc3545" v-if="journals[index]['show_employees'] &&
                                                     !journals[index]['cc_employee_id']">البيانات غير كامله
                                                    </small>

                                                    <small style="color:#dc3545" v-if="journals[index]['show_cars'] &&
                                                     !journals[index]['cc_car_id']">البيانات غير كامله
                                                    </small>

                                                    <small style="color:#dc3545" v-if="journals[index]['show_branches'] &&
                                                     !journals[index]['cc_branch_id']">البيانات غير كامله
                                                    </small>
                                                </td>

                                                {{--دائن--}}
                                                <td class="pr-0">
                                                    <br>
                                                    <br>
                                                    <input type="number" class="form-control" name="journal_dt_debit[]"
                                                           step="0.01"
                                                           v-model="journals[index]['journal_dt_debit']" value="0.000"
                                                           required>
                                                </td>

                                                {{--مدين--}}
                                                <td class="pr-0">
                                                    <br>
                                                    <br>
                                                    <input type="number" class="form-control" name="journal_dt_credit[]"
                                                           step="0.01"
                                                           v-model="journals[index]['journal_dt_credit']" value="0.000"
                                                           required>
                                                </td>


                                                <th class="pr-0">
                                                    <br>
                                                    <button type="button" class="btn btn-icon"
                                                            @click="addRow()">
                                                        <i class="fa fa-plus"></i></button>
                                                    <button type="button" class="btn btn-icon"
                                                            @click="subRow(index)" v-if="index>0">
                                                        <i class="fa fa-minus"></i></button>
                                                </th>
                                            </tr>

                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <label>@lang('home.difference')</label>
                                                    <input type="text" name="total_difference" class="form-control"
                                                           v-model="total_difference" readonly>
                                                </td>
                                                <td>
                                                    <label>@lang('home.debit')</label>
                                                    <input type="text" name="journal_hd_debit" class="form-control"
                                                           v-model="total_debit" readonly>
                                                </td>
                                                <td>
                                                    <label>@lang('home.credit')</label>
                                                    <input type="text" name="journal_hd_credit" class="form-control"
                                                           v-model="total_credit" readonly>
                                                </td>


                                            </tr>

                                            {{--form--}}
                                            <template v-for="(journal,index) in journals">
                                                <div class="modal fade bd-example-modal-lg"
                                                     :id="'exampleModalCenter'+index"
                                                     tabindex="-1" role="dialog"
                                                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered"
                                                         role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle">
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                {{--مركز التكلفه--}}
                                                                <div class="row">
                                                                    <select class="form-control"
                                                                            name="cost_center_type_id[]" required
                                                                            @change="putPropRequired(index)"
                                                                            v-model="journals[index]['cost_center_type_id']">
                                                                        <option value="">@lang('home.choose')</option>
                                                                        @foreach($account_types as $type)
                                                                            <option value="{{$type->system_code}}">
                                                                                {{app()->getLocale()=='ar' ? $type->system_code_name_ar :
                                                                                $type->system_code_name_en }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="customers p-4 mt-1"
                                                                     v-show="journals[index]['show_customers']">
                                                                    <div class="row">
                                                                        {{--العملاء--}}
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">

                                                                                <v-autocomplete
                                                                                        @change="getData(index) ;getSelectedCostCenter(index)"
                                                                                        :required="journals[index]['cc_customer_required']"
                                                                                        v-model="journals[index]['cc_customer_id']"
                                                                                        :items="customers"
                                                                                        item-value="customer_id"
                                                                                        item-text="customer_name_full_ar"
                                                                                        label="@lang('home.customers')"
                                                                                ></v-autocomplete>

                                                                                <input type="hidden"
                                                                                       name="cc_customer_id[]"
                                                                                       :value="journals[index]['cc_customer_id']">

                                                                            </div>
                                                                        </div>

                                                                        {{--امر شراء--}}
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="recipient-name"
                                                                                       class="col-form-label">
                                                                                    @lang('home.type')</label>
                                                                                <select class="form-control customers_cost_center"
                                                                                        name="customer_cost_center_id[]"
                                                                                        {{--:required="journals[index]['cost_center_customer_required']"--}}
                                                                                        @change="getData(index)"
                                                                                        v-model="journals[index]['customer_cost_center_id']">
                                                                                    <option value="">@lang('home.choose')</option>
                                                                                    <option value="70">@lang('home.waybill')</option>
                                                                                    <option value="73">@lang('home.invoice')</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        {{--البوالص او الفواتير--}}
                                                                        <div class="col-md-4">
                                                                            <label class=""> @lang('home.bond')</label>
                                                                            <select name="customer_cc_voucher_id[]"
                                                                                    class="form-control"
                                                                                    {{--:required="journals[index]['cc_customer_voucher_required']"--}}
                                                                                    v-model="journals[index]['customer_cc_voucher_id']">
                                                                                <option value="">@lang('home.choose')</option>
                                                                                <option v-if="Object.keys(journals[index]['waybills']).length > 0"
                                                                                        :value="waybill.waybill_id"
                                                                                        v-for="waybill in journals[index]['waybills']">
                                                                                    @{{ waybill.waybill_code }} +
                                                                                    @{{waybill.waybill_total_amount}}
                                                                                </option>

                                                                                <option v-if="Object.keys(journals[index]['invoices']).length > 0"
                                                                                        :value="invoice.invoice_id"
                                                                                        v-for="invoice in journals[index]['invoices']">
                                                                                    @{{ invoice.invoice_code }} +
                                                                                    @{{invoice.invoice_no}}
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="suppliers  p-4 mt-1"
                                                                     v-show="journals[index]['show_suppliers']">
                                                                    <div class="row">
                                                                        {{--الموردين--}}
                                                                        <div class="col-md-4">
                                                                            <v-autocomplete
                                                                                    v-model="journals[index]['cc_supplier_id']"
                                                                                    :required="journals[index]['cc_supplier_required']"
                                                                                    @change="getData(index); getSelectedCostCenter(index)"
                                                                                    :items="suppliers"
                                                                                    item-value="customer_id"
                                                                                    item-text="customer_name_full_ar"
                                                                                    label="@lang('home.suppliers')"
                                                                            ></v-autocomplete>


                                                                            <input type="hidden"
                                                                                   name="cc_supplier_id[]"
                                                                                   :value="journals[index]['cc_supplier_id']">

                                                                        </div>

                                                                        {{--امر شراء--}}
                                                                        <div class="col-md-4">
                                                                            <label>@lang('home.buy_command')</label>
                                                                            <select class="form-control"
                                                                                    name="supplier_cost_center_id[]"
                                                                                    @change="getData(index)"
                                                                                    {{--:required="journals[index]['cost_center_required']"--}}
                                                                                    v-model="journals[index]['supplier_cost_center_id']">
                                                                                <option value="">@lang('home.choose')</option>
                                                                                <option value="70">@lang('home.waybill')</option>
                                                                                <option value="73">@lang('home.invoice')</option>
                                                                            </select>
                                                                        </div>

                                                                        {{--الفواتير او البوالص--}}
                                                                        <div class="col-md-4">
                                                                            <label>@lang('home.number')</label>
                                                                            <select class="form-control"
                                                                                    name="supplier_cc_voucher_id[]"
                                                                                    {{--:required="journals[index]['cc_voucher_required']"--}}
                                                                                    v-model="journals[index]['supplier_cc_voucher_id']">

                                                                                <option>@lang('home.choose')</option>
                                                                                <option v-if="Object.keys(journals[index]['waybills']).length > 0"
                                                                                        :value="waybill.waybill_id"
                                                                                        v-for="waybill in journals[index]['waybills']">
                                                                                    @{{ waybill.waybill_code }} +
                                                                                    @{{waybill.waybill_total_amount}}
                                                                                </option>

                                                                                <option v-if="Object.keys(journals[index]['invoices']).length > 0"
                                                                                        :value="invoice.invoice_id"
                                                                                        v-for="invoice in journals[index]['invoices']">
                                                                                    @{{ invoice.invoice_code }} +
                                                                                    @{{invoice.invoice_no}}
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="employees  p-4 mt-1"
                                                                     v-show="journals[index]['show_employees']">
                                                                    <div class="row">
                                                                        {{--الموظفين--}}
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">


                                                                                <v-autocomplete
                                                                                        v-model="journals[index]['cc_employee_id']"
                                                                                        :required="journals[index]['cc_employees_required']"
                                                                                        @change="getData(index);getSelectedCostCenter(index)"
                                                                                        :items="employees"
                                                                                        item-value="emp_id"
                                                                                        :item-text="employees => `${employees.emp_name_full_ar} ${employees.emp_identity}`"
                                                                                        label="@lang('home.employees')"
                                                                                ></v-autocomplete>

                                                                                <input type="hidden"
                                                                                       name="cc_employee_id[]"
                                                                                       :value="journals[index]['cc_employee_id']">

                                                                            </div>
                                                                        </div>

                                                                        {{--امر شراء--}}
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="recipient-name"
                                                                                       class="col-form-label">
                                                                                    @lang('home.type')</label>
                                                                                <select class="form-control"
                                                                                        name="employee_cost_center_id[]"
                                                                                        @change="getData(index)"
                                                                                        {{--:required="journals[index]['cost_center_employees_required']"--}}
                                                                                        v-model="journals[index]['employee_cost_center_id']">
                                                                                    <option value="">@lang('home.choose')</option>
                                                                                    <option value="70">@lang('home.waybill')</option>
                                                                                    <option value="73">@lang('home.invoice')</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        {{--البوليصه او الفاتوره--}}
                                                                        <div class="col-md-4">
                                                                            <label> @lang('home.bond')</label>
                                                                            <select class="form-control"
                                                                                    name="employee_cc_voucher_id[]"
                                                                                    {{--:required="journals[index]['cc_employees_voucher_required']"--}}
                                                                                    v-model="journals[index]['employee_cc_voucher_id']">
                                                                                <option value="">@lang('home.choose')</option>

                                                                                <option v-if="Object.keys(journals[index]['waybills']).length > 0"
                                                                                        :value="waybill.waybill_id"
                                                                                        v-for="waybill in journals[index]['waybills']">
                                                                                    @{{ waybill.waybill_code }} +
                                                                                    @{{waybill.waybill_total_amount}}
                                                                                </option>

                                                                                <option v-if="Object.keys(journals[index]['invoices']).length > 0"
                                                                                        :value="invoice.invoice_id"
                                                                                        v-for="invoice in journals[index]['invoices']">
                                                                                    @{{ invoice.invoice_code }} +
                                                                                    @{{invoice.invoice_no}}
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="cars p-4 mt-1"
                                                                     v-show="journals[index]['show_cars']">
                                                                    <div class="row">
                                                                        <div class="col-md-6">


                                                                            <v-autocomplete
                                                                                    v-model="journals[index]['cc_car_id']"
                                                                                    :items="trucks"
                                                                                    @change="getSelectedCostCenter(index)"
                                                                                    item-value="truck_id"
                                                                                    item-text="truck_name"
                                                                                    label="@lang('home.trucks')"
                                                                            ></v-autocomplete>

                                                                            <input type="hidden"
                                                                                   name="cc_car_id[]"
                                                                                   :value="journals[index]['cc_car_id']">

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="branches p-4 mt-1"
                                                                     v-show="journals[index]['show_branches']">
                                                                    <div class="row">

                                                                        {{--<label v-if="journals[index]['show_branches']">--}}
                                                                        {{--@{{journals[index]['cc_branch'][0] ?--}}
                                                                        {{--journals[index]['cc_branch'][0].branch_name_ar--}}
                                                                        {{--:--}}
                                                                        {{--journals[index]['cc_branch'].branch_name_ar}}</label>--}}

                                                                        <v-autocomplete
                                                                                v-model="journals[index]['cc_branch_id']"
                                                                                :items="branches"
                                                                                @change="getSelectedCostCenter(index)"
                                                                                item-value="branch_id"
                                                                                item-text="branch_name_ar"
                                                                                :label="journals[index]['cc_branch'][0] ?
                                                                                journals[index]['cc_branch'][0].branch_name_ar :
                                                                                journals[index]['cc_branch'].branch_name_ar">


                                                                        </v-autocomplete>

                                                                        <input type="hidden"
                                                                               name="cc_branch_id[]"
                                                                               :value="journals[index]['cc_branch_id']">


                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>

                                            {{--end form--}}


                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" id="submit"
                                            :disabled="disable_button || disable_button_2"
                                            class="btn btn-primary">@lang('home.add')</button>

                                    <div class="spinner-border" role="status" style="display: none">
                                        <span class="sr-only">Loading...</span>
                                    </div>

                                </div>


                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </v-app>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('form').submit(function () {

                $('#submit').css('display', 'none')
                $('.spinner-border').css('display', 'block')

            })

            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (day < 10 ? '0' : '') + day + '/' +
                (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
            ;
            $('#restriction_date').val(output)
        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data: {
                company_id: '',
                branches: [],
                branch_id: '{{$branch->branch_id}}',
                customers: [],
                suppliers: [],
                employees: [],
                accounts: [],
                journals: [{
                    'account_id': '',
                    'account': {},
                    'journal_dt_debit': 0.00,
                    'journal_dt_credit': 0.00,
                    'journal_dt_notes': '',
                    'cost_center_type_id': 56005,
                    'cc_customer_id': '',
                    'cc_customer': {},
                    'customer_cost_center_id': '',
                    'customer_cc_voucher_id': '',
                    'supplier_cost_center_id': '',
                    'supplier_cc_voucher_id': '',
                    'employee_cost_center_id': '',
                    'employee_cc_voucher_id': '',
                    'cc_supplier_id': '',
                    'cc_supplier': '',
                    'cc_employee_id': '',
                    'cc_employee': '',
                    'waybills': {},
                    'invoices': {},
                    'cc_supplier_required': false,
                    'cc_voucher_required': false,
                    'cost_center_required': false,
                    'cc_customer_required': false,
                    'show_customers': false,
                    'show_suppliers': false,
                    'show_employees': false,
                    'show_cars': false,
                    'show_branches': true,
                    'cc_customer_voucher_required': false,
                    'cost_center_customer_required': false,
                    'cc_employees_required': false,
                    'cc_employees_voucher_required': false,
                    'cost_center_employees_required': false,
                    'cars_required': false,
                    'cost_center_id': '',
                    'cc_car_id': '',
                    'cc_car': '',
                    'cc_branch_id': '{{$branch->branch_id}}',
                    'cc_branch': {!! $branch !!},
                    'branches_required':
                        false
                }],
                disable_button: false,
                trucks: [],
                isLoaded: false
            },
            mounted() {
                this.company_id = '{{$company->company_id}}'
                this.getBranches()
            },
            methods: {
                putPropRequired(index) {
                    //suppliers
                    if (this.journals[index]['cost_center_type_id'] == 56001) {
                        this.journals[index]['show_customers'] = false
                        this.journals[index]['show_suppliers'] = true
                        this.journals[index]['show_employees'] = false
                        this.journals[index]['show_cars'] = false
                        this.journals[index]['show_branches'] = false


                        this.journals[index]['cc_supplier_required'] = true
                        this.journals[index]['cc_voucher_required'] = false
                        this.journals[index]['cost_center_required'] = false

                        this.journals[index]['cc_customer_required'] = false
                        this.journals[index]['cc_customer_voucher_required'] = false
                        this.journals[index]['cost_center_customer_required'] = false
                        this.journals[index]['customer_cc_voucher_id'] = ''
                        this.journals[index]['customer_cost_center_id'] = ''
                        this.journals[index]['cc_customer_id'] = ''

                        this.journals[index]['cc_employees_required'] = false
                        this.journals[index]['cc_employees_voucher_required'] = false
                        this.journals[index]['cost_center_employees_required'] = false
                        this.journals[index]['employee_cc_voucher_id'] = ''
                        this.journals[index]['employee_cost_center_id'] = ''
                        this.journals[index]['cc_supplier_id'] = ''

                        this.journals[index]['cc_branch_id'] = ''
                        this.journals[index]['branches_required'] = false
                    }

                    //عميل
                    if (this.journals[index]['cost_center_type_id'] == 56002) {

                        this.journals[index]['show_customers'] = true
                        this.journals[index]['show_suppliers'] = false
                        this.journals[index]['show_employees'] = false
                        this.journals[index]['show_cars'] = false
                        this.journals[index]['show_branches'] = false

                        this.journals[index]['cc_customer_required'] = true
                        this.journals[index]['cc_customer_voucher_required'] = false
                        this.journals[index]['cost_center_customer_required'] = false

                        this.journals[index]['cc_supplier_required'] = false
                        this.journals[index]['cc_voucher_required'] = false
                        this.journals[index]['cost_center_required'] = false
                        this.journals[index]['supplier_cc_voucher_id'] = ''
                        this.journals[index]['supplier_cost_center_id'] = ''
                        this.journals[index]['cc_supplier_id'] = ''

                        this.journals[index]['cc_employees_required'] = false
                        this.journals[index]['cc_employees_voucher_required'] = false
                        this.journals[index]['cost_center_employees_required'] = false
                        this.journals[index]['employee_cc_voucher_id'] = ''
                        this.journals[index]['employee_cost_center_id'] = ''
                        this.journals[index]['cc_employee_id'] = ''

                        this.journals[index]['cc_branch_id'] = ''
                        this.journals[index]['branches_required'] = false
                    }

                    //موظف
                    if (this.journals[index]['cost_center_type_id'] == 56003) {
                        this.journals[index]['show_customers'] = false
                        this.journals[index]['show_suppliers'] = false
                        this.journals[index]['show_employees'] = true
                        this.journals[index]['show_cars'] = false
                        this.journals[index]['show_branches'] = false

                        this.journals[index]['cc_employees_required'] = true
                        this.journals[index]['cc_employees_voucher_required'] = true
                        this.journals[index]['cost_center_employees_required'] = true

                        this.journals[index]['cc_customer_required'] = false
                        this.journals[index]['cc_customer_voucher_required'] = false
                        this.journals[index]['cost_center_customer_required'] = false
                        this.journals[index]['customer_cc_voucher_id'] = ''
                        this.journals[index]['customer_cost_center_id'] = ''
                        this.journals[index]['cc_customer_id'] = ''

                        this.journals[index]['cc_supplier_required'] = false
                        this.journals[index]['cc_voucher_required'] = false
                        this.journals[index]['cost_center_required'] = false
                        this.journals[index]['supplier_cc_voucher_id'] = ''
                        this.journals[index]['supplier_cost_center_id'] = ''
                        this.journals[index]['cc_supplier_id'] = ''

                        this.journals[index]['cc_branch_id'] = ''
                        this.journals[index]['branches_required'] = false
                    }

                    //سياره
                    if (this.journals[index]['cost_center_type_id'] == 56004) {

                        this.journals[index]['show_customers'] = false
                        this.journals[index]['show_suppliers'] = false
                        this.journals[index]['show_employees'] = false
                        this.journals[index]['show_cars'] = true
                        this.journals[index]['show_branches'] = false

                        this.journals[index]['cars_required'] = true

                        this.journals[index]['cc_employees_required'] = false
                        this.journals[index]['cc_employees_voucher_required'] = false
                        this.journals[index]['cost_center_employees_required'] = false
                        this.journals[index]['employee_cc_voucher_id'] = ''
                        this.journals[index]['employee_cost_center_id'] = ''
                        this.journals[index]['cc_employee_id'] = ''

                        this.journals[index]['cc_customer_required'] = false
                        this.journals[index]['cc_customer_voucher_required'] = false
                        this.journals[index]['cost_center_customer_required'] = false
                        this.journals[index]['customer_cc_voucher_id'] = ''
                        this.journals[index]['customer_cost_center_id'] = ''
                        this.journals[index]['cc_customer_id'] = ''

                        this.journals[index]['cc_supplier_required'] = false
                        this.journals[index]['cc_voucher_required'] = false
                        this.journals[index]['cost_center_required'] = false
                        this.journals[index]['supplier_cc_voucher_id'] = ''
                        this.journals[index]['supplier_cost_center_id'] = ''
                        this.journals[index]['cc_supplier_id'] = ''

                        this.journals[index]['cc_branch_id'] = ''
                        this.journals[index]['branches_required'] = false
                    }
///فرع
                    if (this.journals[index]['cost_center_type_id'] == 56005) {
                        this.journals[index]['show_customers'] = false
                        this.journals[index]['show_suppliers'] = false
                        this.journals[index]['show_employees'] = false
                        this.journals[index]['show_cars'] = false
                        this.journals[index]['show_branches'] = true

                        this.journals[index]['branches_required'] = true

                        this.journals[index]['cc_employees_required'] = false
                        this.journals[index]['cc_employees_voucher_required'] = false
                        this.journals[index]['cost_center_employees_required'] = false
                        this.journals[index]['employee_cc_voucher_id'] = ''
                        this.journals[index]['employee_cost_center_id'] = ''
                        this.journals[index]['cc_employee_id'] = ''

                        this.journals[index]['cc_customer_required'] = false
                        this.journals[index]['cc_customer_voucher_required'] = false
                        this.journals[index]['cost_center_customer_required'] = false
                        this.journals[index]['customer_cc_voucher_id'] = ''
                        this.journals[index]['customer_cost_center_id'] = ''
                        this.journals[index]['cc_customer_id'] = ''

                        this.journals[index]['cc_supplier_required'] = false
                        this.journals[index]['cc_voucher_required'] = false
                        this.journals[index]['cost_center_required'] = false
                        this.journals[index]['supplier_cc_voucher_id'] = ''
                        this.journals[index]['supplier_cost_center_id'] = ''
                        this.journals[index]['cc_supplier_id'] = ''

                    }
                }
                ,
                getBranches() {
                    if (this.company_id) {
                        $.ajax({
                            type: 'GET',
                            data: {company_id: this.company_id},
                            url: '{{ route("api.journal-entries.company-data") }}'
                        }).then(response => {
                            this.branches = response.data
                            this.customers = response.customers
                            this.suppliers = response.suppliers
                            this.employees = response.employees
                            this.accounts = response.accounts
                            this.isLoaded = true
                            this.trucks = response.trucks
                            // this.branch_id = response.branch_id
                        })
                    }
                }
                ,
                addRow() {
                    this.journals.push({
                        'account_id': '', 'account': {}, 'journal_dt_debit': 0.00, 'journal_dt_credit': 0.00,
                        'journal_dt_notes': '', 'cost_center_type_id': 56005, 'cc_customer_id': '',
                        'cc_customer': {},
                        'customer_cost_center_id': '',
                        'customer_cc_voucher_id': '',
                        'supplier_cost_center_id': '',
                        'supplier_cc_voucher_id': '',
                        'employee_cost_center_id': '',
                        'employee_cc_voucher_id': '',
                        'cc_supplier_id': '',
                        'cc_supplier': {},
                        'cc_employee_id': '',
                        'cc_employee': {},
                        'waybills': {},
                        'invoices': {},
                        //supplier
                        'cc_supplier_required': false,
                        'cc_voucher_required': false,
                        'cost_center_required': false,
                        //customer
                        'cc_customer_required': false,
                        'show_customers': false,
                        'show_suppliers': false,
                        'show_employees': false,
                        'show_cars': false,
                        'show_branches': true,
                        'cc_customer_voucher_required': false,
                        'cost_center_customer_required': false,
                        ///employees
                        'cc_employees_required': false,
                        'cc_employees_voucher_required': false,
                        'cost_center_employees_required': false,
                        'cars_required': false,
                        'cc_car_id': '',
                        'cc_car': {},
                        'cc_branch_id': '{{$branch->branch_id}}',
                        'cc_branch': {!! $branch !!},
                        'branches_required': false
                    })
                }
                ,
                subRow(index) {
                    this.journals.splice(index, 1)
                }
                ,
                getData(index) {
                    this.journals[index]['waybills'] = {}
                    this.journals[index]['invoices'] = {}

                    if (this.journals[index]['supplier_cost_center_id'] || this.journals[index]['customer_cost_center_id']
                        || this.journals[index]['employee_cost_center_id']) {

                        if (this.journals[index]['cc_supplier_id'] || this.journals[index]['cc_customer_id']
                            || this.journals[index]['cc_employee_id']) {


                            if (this.journals[index]['supplier_cost_center_id']) {
                                this.cost_center_id = this.journals[index]['supplier_cost_center_id']
                            }

                            if (this.journals[index]['customer_cost_center_id']) {
                                this.cost_center_id = this.journals[index]['customer_cost_center_id']
                            }

                            if (this.journals[index]['employee_cost_center_id']) {
                                this.cost_center_id = this.journals[index]['employee_cost_center_id']
                            }

                            $.ajax({
                                type: 'GET',
                                data: {
                                    cc_customer_id: this.journals[index]['cc_customer_id'],
                                    cc_supplier_id: this.journals[index]['cc_supplier_id'],
                                    cc_employee_id: this.journals[index]['cc_employee_id'],
                                    cost_center_id: this.cost_center_id
                                },
                                url: '{{ route("api.journal-entries.get-data") }}'
                            }).then(response => {
                                if (response.invoices) {
                                    this.journals[index]['invoices'] = response.invoices
                                }
                                if (response.waybills) {
                                    this.journals[index]['waybills'] = response.waybills
                                }
                            })
                        }
                    }


                }
                ,
                getSelectedAccount(index) {
                    // this.journals[index]['account'] = this.accounts
                    if (this.isLoaded == true) {
                        this.journals[index]['account'] = this.accounts.filter((account) => {
                            return account.acc_id == this.journals[index]['account_id']
                        })
                    }

                }
                ,
                getSelectedCostCenter(index) {
                    if (this.isLoaded = true) {
                        if (this.journals[index]['show_customers']) {
                            this.journals[index]['cc_customer'] = this.customers.filter((customer) => {
                                return customer.customer_id == this.journals[index]['cc_customer_id']
                            })
                        }

                        if (this.journals[index]['show_suppliers']) {
                            this.journals[index]['cc_supplier'] = this.suppliers.filter((supplier) => {
                                return supplier.customer_id == this.journals[index]['cc_supplier_id']
                            })
                        }

                        if (this.journals[index]['show_branches']) {
                            this.journals[index]['cc_branch'] = this.branches.filter((branch) => {
                                return branch.branch_id == this.journals[index]['cc_branch_id']
                            })
                        }
                        if (this.journals[index]['show_cars']) {
                            this.journals[index]['cc_car'] = this.trucks.filter((truck) => {
                                return truck.truck_id == this.journals[index]['cc_car_id']
                            })
                        }

                        if (this.journals[index]['show_employees']) {
                            this.journals[index]['cc_employee'] = this.employees.filter((employee) => {
                                return employee.emp_id == this.journals[index]['cc_employee_id']
                            })
                        }
                    }
                }

            }
            ,
            computed: {
                total_credit: function () {
                    let total = 0;
                    Object.entries(this.journals).forEach(([key, val]) => {
                        total += (parseFloat(val.journal_dt_credit))
                    });
                    return total.toFixed(2);
                }
                ,
                total_debit: function () {
                    let total = 0;
                    Object.entries(this.journals).forEach(([key, val]) => {
                        total += (parseFloat(val.journal_dt_debit))
                    });
                    return total.toFixed(2);
                }
                ,
                total_difference: function () {
                    var td = this.total_credit - this.total_debit
                    if (td != 0) {
                        this.disable_button = true
                    } else {
                        this.disable_button = false
                    }
                    return td.toFixed(2);
                },
                disable_button_2: function () {

                    var x = 0;
                    Object.entries(this.journals).forEach(([key, val]) => {

                        if (!(val.cost_center_type_id)) {
                            x += 1;
                        }

                        if (val.cost_center_type_id == 56001) { //supplier
                            if (!val.cc_supplier_id) {
                                x += 1;
                            }

                            // if (!val.supplier_cost_center_id) {
                            //     x += 1;
                            // }
                            //
                            // if (!val.supplier_cc_voucher_id) {
                            //     x += 1;
                            // }
                        }


                        if (val.cost_center_type_id == 56002) { //customer
                            if (!val.cc_customer_id) {
                                x += 1;
                            }
                            // if (!val.customer_cost_center_id) {
                            //     x += 1;
                            // }
                            //
                            // if (!val.customer_cc_voucher_id) {
                            //     x += 1;
                            // }
                        }


                        if (val.cost_center_type_id == 56003) { ///employee{
                            if (!val.cc_employee_id) {
                                x += 1;
                            }
                        }

                        if (val.cost_center_type_id == 56004) { //car
                            if (!val.cc_car_id) {
                                x += 1;
                            }
                        }
                        if (val.cost_center_type_id == 56005) { //branch
                            if (!val.cc_branch_id) {
                                x += 1;
                            }
                        }
                    });


                    if (x > 0) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        })
    </script>
@endsection

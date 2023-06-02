@extends('Layouts.master')

@section('style')

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">


    <style lang="">
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200&display=swap');

        .v-application {
            font-family: "Cairo" !important;
        }
    </style>
@endsection

@section('content')
    <div class="section-body mt-3" id="app">
        <v-app>
            <div class="container-fluid">
                <div class="row clearfix">
                    <div class="col-lg-12">

                        @if (\Session::has('error'))
                            <div class="alert alert-danger">
                                <ul>
                                    <li>{!! \Session::get('error') !!}</li>
                                </ul>
                            </div>
                        @endif

                        <form action="{{route('journal-entries.update_2',$journal_hd->journal_hd_id)}}" method="post">
                            @csrf
                            @method('put')

                            <div class="card-body">
                                <h3 class="card-title">@lang('home.daily_restrictions_details')</h3>

                                <div class="row">
                                    {{--الشركات--}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.companies')</label>
                                            <input type="text" disabled="" class="form-control"
                                                   value="{{ app()->getLocale()=='ar' ? $journal_hd->company->company_name_ar :
                                        $journal_hd->company->company_name_en  }}">
                                        </div>
                                    </div>


                                    {{--الفروع--}}
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.branch')</label>
                                            <input type="text" disabled="" class="form-control"
                                                   value="{{ app()->getLocale()=='ar' ? $journal_hd->branch->branch_name_ar :
                                        $journal_hd->branch->branch_name_en  }}">
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.journal_code')</label>
                                            <input type="text" disabled="" class="form-control"
                                                   value="{{$journal_hd->journal_hd_code}}">
                                        </div>
                                    </div>


                                    {{--انواع يوميات قيود الحسابات--}}
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.daily_accounts_type')</label>
                                            <input type="text" disabled="" class="form-control"
                                                   value="{{$journal_hd->journalType->system_code_name_ar}}">

                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.date')</label>
                                            <input type="date" class="form-control" name="journal_hd_date"
                                                   value="{{$journal_hd->journal_hd_date}}">
                                        </div>
                                    </div>

                                    {{--رقم الملف--}}
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.file_serial')</label>
                                            <input type="number" class="form-control" placeholder="10001"
                                                   name="journal_file_no" value="{{$journal_hd->journal_file_no}}">
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
                                                        <option value="{{$journal_status->system_code_id}}"
                                                                @if($journal_hd->journal_status == $journal_status->system_code_id) selected @endif>
                                                            {{app()->getLocale()=='ar' ? $journal_status->system_code_name_ar
                                                            : $journal_status->system_code_name_en}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="text" class="form-control" readonly
                                                       value="{{$journal_hd->journalStatus->system_code_name_ar}}">

                                                <input type="hidden" class="form-control" readonly
                                                       value="{{$journal_hd->journalStatus->system_code_id}}">
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
                                        <textarea class="form-control"
                                                  name="journal_hd_notes"> {{$journal_hd->journal_hd_notes}} </textarea>
                                    </div>

                                </div>
                            </div>


                            <div class="card">

                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                            <tr>
                                                <td>#</td>
                                                <th class="pl-0">@lang('home.account_name')</th>
                                                <th></th>
                                                <th>{{__('cost center')}}</th>
                                                <th style="width: 300px">@lang('home.notes')</th>
                                                <th>{{__('date')}}</th>
                                                <th class="pr-0">@lang('home.debit')</th>
                                                <th class="pr-0">@lang('home.credit')</th>

                                                <th class="pr-0"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($journal_dts as $k=>$journal_dt)
                                                <input type="hidden" name="journal_details_ids[]"
                                                       value="{{$journal_dt->journal_dt_id}}">
                                                <tr>
                                                    <td>{{$k+1}}</td>
                                                    <td colspan="2">
                                                        <select name="account_id[]" class="selectpicker"
                                                                data-live-search="true">
                                                            @foreach($accounts as $account)
                                                                <option value="{{$account->acc_id}}"
                                                                        @if($journal_dt->account_id == $account->acc_id) selected @endif>
                                                                    {{$account->acc_name_ar}} . ' ==> '
                                                                    . {{$account->acc_code}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    {{--مركز التكلفه--}}
                                                    <td>
                                                        <p style="color:darkred;padding-bottom: 0px !important;margin-bottom: 0px !important;">
                                                            {{$journal_dt->system_code_name_ar}}</p>
                                                        <br>

                                                        <input type="hidden" name="customer_cost_center_id[]" value="0">
                                                        <input type="hidden" name="customer_cc_voucher_id[]" value="0">
                                                        <input type="hidden" name="cc_supplier_id[]" value="0">
                                                        <input type="hidden" name="supplier_cost_center_id[]" value="0">
                                                        <input type="hidden" name="cc_employee_id[]" value="0">
                                                        <input type="hidden" name="employee_cost_center_id[]" value="0">
                                                        <input type="hidden" name="employee_cc_voucher_id[]" value="0">
                                                        <input type="hidden" name="cc_car_id[]" value="0">
                                                        <input type="hidden" name="cc_branch_id[]" value="0">
                                                        <input type="hidden" name="cost_center_type_id[]" value="0">
                                                        <input type="hidden" name="cc_customer_id[]" value="0">

                                                        @if($journal_dt->system_code == 56002)
                                                            {{$journal_dt->customer_name_full_ar}}
                                                        @elseif($journal_dt->system_code == 56001)
                                                            {{$journal_dt->supplier_name_full_ar}}
                                                        @elseif($journal_dt->system_code == 56003)
                                                            {{$journal_dt->emp_name_full_ar}}
                                                        @elseif($journal_dt->system_code == 56004)
                                                            {{$journal_dt->truck_code . '==>'. $journal_dt->truck_name}}
                                                        @elseif($journal_dt->system_code == 56005)
                                                            {{$journal_dt->branch_name_ar }}
                                                        @endif

                                                    </td>
                                                    {{-- --}}


                                                    <td>
                                                    <textarea class="form-control" name="journal_dt_notes[]"
                                                              rows="3">{{$journal_dt->journal_dt_notes}}</textarea>
                                                    </td>

                                                    <td>
                                                        <input type="datetime-local" class="form-control"
                                                               name="journal_dt_date[]"
                                                               value="{{$journal_dt->journal_dt_date}}">
                                                    </td>

                                                    <td>
                                                        <input type="number" step=".001"
                                                               class="form-control journal_dt_debit"
                                                               value="{{$journal_dt->journal_dt_debit}}"
                                                               name="journal_dt_debit[]">
                                                    </td>

                                                    <td>
                                                        <input type="number" step=".001"
                                                               class="form-control journal_dt_credit"
                                                               value="{{$journal_dt->journal_dt_credit}}"
                                                               name="journal_dt_credit[]">
                                                    </td>

                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-primary"
                                                                @click="deleteRow('{{$journal_dt->journal_dt_id}}')">
                                                            <i class="fa fa-trash-o"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <tr v-for="(journal,index) in journals_new">
                                                <input type="hidden" name="journal_details_ids[]" value="0">
                                                <td></td>
                                                <td class="pl-0">
                                                    <v-autocomplete
                                                            v-model="journals_new[index]['account_id']"
                                                            :items="accounts"
                                                            @change="getSelectedAccount(index)"
                                                            item-value="acc_id"
                                                            item-text="acc_name_ar"
                                                            :label="journals_new[index]['account'].length > 0 ?
                                                             journals_new[index]['account'][0].acc_code : '@lang("home.accounts")'"
                                                    ></v-autocomplete>
                                                    <input type="hidden" name="account_id[]"
                                                           v-model="journals_new[index]['account_id']">
                                                </td>

                                                <td>
                                                    <button class="btn btn-link" type="button" style="font-weight: bold"
                                                            data-toggle="modal"
                                                            :data-target="'#exampleModalCenterNew'+index">
                                                        <i class="fa fa-paperclip fa-2x"></i>
                                                    </button>
                                                </td>
                                                {{--الملاحظات--}}
                                                <td colspan="2">
                                                    <label v-if="journals_new[index]['show_customers']">@lang('home.customers')
                                                        @{{journals_new[index]['cc_customer'][0] ?
                                                        journals_new[index]['cc_customer'][0].customer_name_full_ar :
                                                        ''}}</label>
                                                    <label v-if="journals_new[index]['show_employees']">@lang('home.employees')
                                                        @{{journals_new[index]['cc_employee'][0] ?
                                                        journals_new[index]['cc_employee'][0].emp_name_full_ar :
                                                        ''}}</label>
                                                    <label v-if="journals_new[index]['show_suppliers']">@lang('home.suppliers')
                                                        @{{journals_new[index]['cc_supplier'][0] ?
                                                        journals_new[index]['cc_supplier'][0].customer_name_full_ar :
                                                        ''}}</label>
                                                    <label v-if="journals_new[index]['show_cars']">@lang('home.cars')
                                                        @{{journals_new[index]['cc_car'][0] ?
                                                        journals_new[index]['cc_car'][0].truck_name :
                                                        ''}}</label>
                                                    <label v-if="journals_new[index]['show_branches']">@lang('home.branches')
                                                        @{{journals_new[index]['cc_branch'][0] ?
                                                        journals_new[index]['cc_branch'][0].branch_name_ar :
                                                        journals_new[index]['cc_branch'].branch_name_ar}}</label>

                                                    <input type="text" class="form-control" name="journal_dt_notes[]"
                                                           v-model="journals_new[index]['journal_dt_notes']"
                                                           :required="journals_new[index]['journal_dt_notes_required']">


                                                    <small class="text-danger"
                                                           v-if="!journals_new[index]['cost_center_type_id']">
                                                        البيانات غير كامله
                                                    </small>


                                                    <small style="color:#dc3545" v-if="journals_new[index]['show_suppliers'] &&
                                                     !journals_new[index]['cc_supplier_id']">البيانات غير كامله
                                                    </small>

                                                    <small style="color:#dc3545" v-if="journals_new[index]['show_customers'] &&
                                                     !journals_new[index]['cc_customer_id']">البيانات غير كامله
                                                    </small>

                                                    <small style="color:#dc3545" v-if="journals_new[index]['show_employees'] &&
                                                     !journals_new[index]['cc_employee_id']">البيانات غير كامله
                                                    </small>

                                                    <small style="color:#dc3545" v-if="journals_new[index]['show_cars'] &&
                                                     !journals_new[index]['cc_car_id']">البيانات غير كامله
                                                    </small>

                                                    <small style="color:#dc3545" v-if="journals_new[index]['show_branches'] &&
                                                     !journals_new[index]['cc_branch_id']">البيانات غير كامله
                                                    </small>

                                                </td>

                                                <td class="pr-0">
                                                    <input type="datetime-local" class="form-control"
                                                           name="journal_dt_date[]"
                                                           v-model="journals_new[index]['journal_dt_date']"
                                                           :required="journals_new[index]['journal_dt_date_required']">
                                                </td>

                                                {{--دائن--}}
                                                <td class="pr-0">
                                                    <input type="number" class="form-control journal_dt_debit"
                                                           name="journal_dt_debit[]"
                                                           step="0.01"
                                                           v-model="journals_new[index]['journal_dt_debit']"
                                                           value="0.00"
                                                           :required="journals_new[index]['journal_dt_debit_required']">
                                                </td>


                                                {{--مدين--}}
                                                <td class="pr-0">
                                                    <input type="number" class="form-control journal_dt_credit"
                                                           name="journal_dt_credit[]"
                                                           step="0.01"
                                                           v-model="journals_new[index]['journal_dt_credit']"
                                                           value="0.00"
                                                           :required="journals_new[index]['journal_dt_credit_required']">
                                                </td>


                                                <th class="pr-0">
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                            @click="addRow()">
                                                        <i class="fa fa-plus"></i></button>
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                            @click="subRow()" v-if="index>0">
                                                        <i class="fa fa-minus"></i></button>
                                                </th>
                                            </tr>

                                            {{--form new --}}
                                            <template v-for="(journal,index) in journals_new">
                                                <div class="modal fade bd-example-modal-lg"
                                                     :id="'exampleModalCenterNew'+index"
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
                                                                            name="cost_center_type_id[]"
                                                                            @change="putPropRequired(index)"
                                                                            v-model="journals_new[index]['cost_center_type_id']">
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
                                                                     v-show="journals_new[index]['show_customers']">
                                                                    <div class="row">
                                                                        {{--العملاء--}}
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="recipient-name"
                                                                                       class="col-form-label">

                                                                                    @lang('home.customers')</label>

                                                                                <v-autocomplete
                                                                                        @change="getData(index);getSelectedCostCenter(index)"
                                                                                        :required="journals_new[index]['cc_customer_required']"
                                                                                        v-model="journals_new[index]['cc_customer_id']"
                                                                                        :items="customers"
                                                                                        item-value="customer_id"
                                                                                        item-text="customer_name_full_ar"
                                                                                        label="@lang('home.customers')"
                                                                                ></v-autocomplete>

                                                                                <input type="hidden"
                                                                                       name="cc_customer_id[]"
                                                                                       :value="journals_new[index]['cc_customer_id']">
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
                                                                                        {{--:required="journals_new[index]['cost_center_customer_required']"--}}
                                                                                        @change="getData(index)"
                                                                                        v-model="journals_new[index]['customer_cost_center_id']">
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
                                                                                    {{--:required="journals_new[index]['cc_customer_voucher_required']"--}}
                                                                                    v-model="journals_new[index]['customer_cc_voucher_id']">
                                                                                <option value="">@lang('home.choose')</option>
                                                                                <option v-if="Object.keys(journals_new[index]['waybills']).length > 0"
                                                                                        :value="waybill.waybill_id"
                                                                                        v-for="waybill in journals_new[index]['waybills']">
                                                                                    @{{ waybill.waybill_code }} +
                                                                                    @{{waybill.waybill_total_amount}}
                                                                                </option>

                                                                                <option v-if="Object.keys(journals_new[index]['invoices']).length > 0"
                                                                                        :value="invoice.invoice_id"
                                                                                        v-for="invoice in journals_new[index]['invoices']">
                                                                                    @{{ invoice.invoice_code }} +
                                                                                    @{{invoice.invoice_no}}
                                                                                </option>


                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="suppliers  p-4 mt-1"
                                                                     v-show="journals_new[index]['show_suppliers']">
                                                                    <div class="row">
                                                                        {{--الموردين--}}
                                                                        <div class="col-md-4">
                                                                            <v-autocomplete
                                                                                    v-model="journals_new[index]['cc_supplier_id']"
                                                                                    :required="journals_new[index]['cc_supplier_required']"
                                                                                    @change="getData(index); getSelectedCostCenter(index)"
                                                                                    :items="suppliers"
                                                                                    item-value="customer_id"
                                                                                    item-text="customer_name_full_ar"
                                                                                    label="@lang('home.suppliers')"
                                                                            ></v-autocomplete>


                                                                            <input type="hidden"
                                                                                   name="cc_supplier_id[]"
                                                                                   :value="journals_new[index]['cc_supplier_id']">
                                                                        </div>

                                                                        {{--امر شراء--}}
                                                                        <div class="col-md-4">
                                                                            <label>@lang('home.buy_command')</label>
                                                                            <select class="form-control"
                                                                                    name="supplier_cost_center_id[]"
                                                                                    @change="getData(index)"
                                                                                    {{--:required="journals_new[index]['cost_center_required']"--}}
                                                                                    v-model="journals_new[index]['supplier_cost_center_id']">
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
                                                                                    {{--:required="journals_new[index]['cc_voucher_required']"--}}
                                                                                    v-model="journals_new[index]['supplier_cc_voucher_id']">

                                                                                <option>@lang('home.choose')</option>
                                                                                <option v-if="Object.keys(journals_new[index]['waybills']).length > 0"
                                                                                        :value="waybill.waybill_id"
                                                                                        v-for="waybill in journals_new[index]['waybills']">
                                                                                    @{{ waybill.waybill_code }} +
                                                                                    @{{waybill.waybill_total_amount}}
                                                                                </option>

                                                                                <option v-if="Object.keys(journals_new[index]['invoices']).length > 0"
                                                                                        :value="invoice.invoice_id"
                                                                                        v-for="invoice in journals_new[index]['invoices']">
                                                                                    @{{ invoice.invoice_code }} +
                                                                                    @{{invoice.invoice_no}}
                                                                                </option>

                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="employees  p-4 mt-1"
                                                                     v-show="journals_new[index]['show_employees']">
                                                                    <div class="row">
                                                                        {{--الموظفين--}}
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <v-autocomplete
                                                                                        v-model="journals_new[index]['cc_employee_id']"
                                                                                        :required="journals_new[index]['cc_employees_required']"
                                                                                        @change="getData(index);getSelectedCostCenter(index)"
                                                                                        :items="employees"
                                                                                        item-value="emp_id"
                                                                                        :item-text="employees => `${employees.emp_name_full_ar} ${employees.emp_identity}`"
                                                                                        label="@lang('home.employees')"
                                                                                ></v-autocomplete>

                                                                                <input type="hidden"
                                                                                       name="cc_employee_id[]"
                                                                                       :value="journals_new[index]['cc_employee_id']">
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
                                                                                        {{--:required="journals_new[index]['cost_center_employees_required']"--}}
                                                                                        v-model="journals_new[index]['employee_cost_center_id']">
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
                                                                                    {{--:required="journals_new[index]['cc_employees_voucher_required']"--}}
                                                                                    v-model="journals_new[index]['employee_cc_voucher_id']">
                                                                                <option value="">@lang('home.choose')</option>

                                                                                <option v-if="Object.keys(journals_new[index]['waybills']).length > 0"
                                                                                        :value="waybill.waybill_id"
                                                                                        v-for="waybill in journals_new[index]['waybills']">
                                                                                    @{{ waybill.waybill_code }} +
                                                                                    @{{waybill.waybill_total_amount}}
                                                                                </option>

                                                                                <option v-if="Object.keys(journals_new[index]['invoices']).length > 0"
                                                                                        :value="invoice.invoice_id"
                                                                                        v-for="invoice in journals_new[index]['invoices']">
                                                                                    @{{ invoice.invoice_code }} +
                                                                                    @{{invoice.invoice_no}}
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="cars p-4 mt-1"
                                                                     v-show="journals_new[index]['show_cars']">
                                                                    <div class="row">
                                                                        <div class="col-md-6">

                                                                            <v-autocomplete
                                                                                    v-model="journals_new[index]['cc_car_id']"
                                                                                    :items="trucks"
                                                                                    @change="getSelectedCostCenter(index)"
                                                                                    item-value="truck_id"
                                                                                    item-text="truck_name"
                                                                                    label="@lang('home.trucks')"
                                                                            ></v-autocomplete>

                                                                            <input type="hidden"
                                                                                   name="cc_car_id[]"
                                                                                   :value="journals_new[index]['cc_car_id']">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="branches p-4 mt-1"
                                                                     v-show="journals_new[index]['show_branches']">
                                                                    <div class="row">

                                                                        <v-autocomplete
                                                                                v-model="journals_new[index]['cc_branch_id']"
                                                                                :items="branches"
                                                                                @change="getSelectedCostCenter(index)"
                                                                                item-value="branch_id"
                                                                                item-text="branch_name_ar"
                                                                                :label="journals_new[index]['cc_branch'][0] ?
                                                                                journals_new[index]['cc_branch'][0].branch_name_ar
                                                                                :
                                                                                journals_new[index]['cc_branch'].branch_name_ar">

                                                                        </v-autocomplete>

                                                                        <input type="hidden"
                                                                               name="cc_branch_id[]"
                                                                               :value="journals_new[index]['cc_branch_id']">

                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                            {{--end form--}}

                                            {{--<tr>--}}
                                            {{--<td></td>--}}
                                            {{--<td></td>--}}
                                            {{--<td></td>--}}
                                            {{--<td></td>--}}
                                            {{--<td>--}}
                                            {{--<label>@lang('home.difference')</label>--}}
                                            {{--<input type="text" name="total_difference" class="form-control"--}}
                                            {{--readonly id="total_difference">--}}
                                            {{--</td>--}}
                                            {{--<td></td>--}}
                                            {{--<td>--}}
                                            {{--<label>@lang('home.debit')</label>--}}
                                            {{--<input type="text" name="journal_hd_debit" class="form-control"--}}
                                            {{--readonly id="total_debit">--}}
                                            {{--</td>--}}

                                            {{--<td>--}}
                                            {{--<label>@lang('home.credit')</label>--}}
                                            {{--<input type="text" name="journal_hd_credit" class="form-control"--}}
                                            {{--readonly id="total_credit">--}}
                                            {{--</td>--}}

                                            {{--</tr>--}}

                                            </tbody>
                                        </table>
                                        {{$journal_dts->links()}}
                                    </div>
                                    <button class="btn btn-primary btn-lg" type="submit"
                                            :disabled="disable_button_2"
                                            id="submit">{{__('save')}}</button>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script>
        // $(document).ready(function () {
        //     var total_credit = 0;
        //     $(".journal_dt_credit").each(function () {
        //         total_credit += parseFloat($(this).val()) || 0;
        //     });
        //
        //     $('#total_credit').val(total_credit)
        //
        //
        //     var total_debit = 0;
        //     $(".journal_dt_debit").each(function () {
        //         total_debit += parseFloat($(this).val()) || 0;
        //     });
        //
        //     $('#total_debit').val(total_debit)
        //
        //     $('#total_difference').val(total_debit - total_credit)
        //
        // })

        // function calculateTotals() {
        //     console.log('a')
        //     var total_credit = 0;
        //
        //     $(".journal_dt_credit").each(function () {
        //         total_credit += parseFloat($(this).val()) || 0;
        //     });
        //
        //     $('#total_credit').val(total_credit)
        //
        //
        //     var total_debit = 0;
        //
        //     $(".journal_dt_debit").each(function () {
        //         total_debit += parseFloat($(this).val()) || 0;
        //     });
        //
        //     $('#total_debit').val(total_debit)
        //
        //     $('#total_difference').val(total_debit - total_credit)
        //
        //     if ($('#total_difference').val() != 0) {
        //         $('#submit').prop('disabled', true)
        //     } else if ($('#total_difference').val() == 0) {
        //         $('#submit').attr('disabled', false)
        //     }
        // }

    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>

    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data: {
                journals_new: [
                    {
                        'account_id': '',
                        'account': {},
                        'journal_dt_date': '{{$current_date}}',
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
                        'cc_supplier': {},
                        'cc_employee_id': '',
                        'cc_employee': {},
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
                        'cc_branches_required': false,
                        'cc_employees_voucher_required': false,
                        'cost_center_employees_required': false,
                        'cars_required': false,
                        'cost_center_id': '',
                        'cc_car_id': '',
                        'cc_car': {},
                        'cc_branch_id': '{{$branch->branch_id}}',
                        'cc_branch':  {!! $branch!!},
                        'journal_dt_notes_required': false,
                        'journal_dt_date_required': false,
                        'journal_dt_debit_required': false,
                        'journal_dt_credit_required': false,
                    }
                ],
                customers: [],
                suppliers: [],
                employees: [],
                trucks: [],
                accounts: [],
                branches: [],
                company_id: '{{$journal_hd->company_id}}'
            },
            mounted() {
                this.getBranches()
            },
            methods: {
                getBranches() {
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
                        this.trucks = response.trucks
                    })
                },
                putPropRequired(index) {
                    //suppliers
                    if (this.journals_new[index]['cost_center_type_id'] == 56001) {

                        this.journals_new[index]['show_customers'] = false
                        this.journals_new[index]['show_suppliers'] = true
                        this.journals_new[index]['show_employees'] = false
                        this.journals_new[index]['show_cars'] = false
                        this.journals_new[index]['show_branches'] = false

                        this.journals_new[index]['cc_supplier_required'] = true
                        this.journals_new[index]['cc_voucher_required'] = false
                        this.journals_new[index]['cost_center_required'] = false

                        this.journals_new[index]['cc_customer_required'] = false
                        this.journals_new[index]['cc_customer_voucher_required'] = false
                        this.journals_new[index]['cost_center_customer_required'] = false
                        this.journals_new[index]['cc_branches_required'] = false

                        this.journals_new[index]['customer_cc_voucher_id'] = ''
                        this.journals_new[index]['customer_cost_center_id'] = ''
                        this.journals_new[index]['cc_customer_id'] = ''
                        this.journals_new[index]['cc_branch_id'] = ''

                        this.journals_new[index]['cc_employees_required'] = false
                        this.journals_new[index]['cc_employees_voucher_required'] = false
                        this.journals_new[index]['cost_center_employees_required'] = false
                        this.journals_new[index]['employee_cc_voucher_id'] = ''
                        this.journals_new[index]['employee_cost_center_id'] = ''
                        this.journals_new[index]['cc_supplier_id'] = ''

                    }

                    //عميل
                    if (this.journals_new[index]['cost_center_type_id'] == 56002) {
                        this.journals_new[index]['show_customers'] = true
                        this.journals_new[index]['show_suppliers'] = false
                        this.journals_new[index]['show_employees'] = false
                        this.journals_new[index]['show_cars'] = false
                        this.journals_new[index]['show_branches'] = false

                        this.journals_new[index]['cc_customer_required'] = true
                        this.journals_new[index]['cc_customer_voucher_required'] = false
                        this.journals_new[index]['cost_center_customer_required'] = false

                        this.journals_new[index]['cc_supplier_required'] = false
                        this.journals_new[index]['cc_voucher_required'] = false
                        this.journals_new[index]['cost_center_required'] = false
                        this.journals_new[index]['supplier_cc_voucher_id'] = ''
                        this.journals_new[index]['supplier_cost_center_id'] = ''
                        this.journals_new[index]['cc_supplier_id'] = ''

                        this.journals_new[index]['cc_employees_required'] = false
                        this.journals_new[index]['cc_employees_voucher_required'] = false
                        this.journals_new[index]['cost_center_employees_required'] = false
                        this.journals_new[index]['employee_cc_voucher_id'] = ''
                        this.journals_new[index]['employee_cost_center_id'] = ''
                        this.journals_new[index]['cc_employee_id'] = ''

                        this.journals_new[index]['cc_branch_id'] = ''
                    }

                    //موظف
                    if (this.journals_new[index]['cost_center_type_id'] == 56003) {
                        this.journals_new[index]['show_customers'] = false
                        this.journals_new[index]['show_suppliers'] = false
                        this.journals_new[index]['show_employees'] = true
                        this.journals_new[index]['show_cars'] = false
                        this.journals_new[index]['show_branches'] = false

                        this.journals_new[index]['cc_employees_required'] = true
                        this.journals_new[index]['cc_employees_voucher_required'] = false
                        this.journals_new[index]['cost_center_employees_required'] = false

                        this.journals_new[index]['cc_customer_required'] = false
                        this.journals_new[index]['cc_customer_voucher_required'] = false
                        this.journals_new[index]['cost_center_customer_required'] = false
                        this.journals_new[index]['customer_cc_voucher_id'] = ''
                        this.journals_new[index]['customer_cost_center_id'] = ''
                        this.journals_new[index]['cc_customer_id'] = ''

                        this.journals_new[index]['cc_supplier_required'] = false
                        this.journals_new[index]['cc_voucher_required'] = false
                        this.journals_new[index]['cost_center_required'] = false
                        this.journals_new[index]['supplier_cc_voucher_id'] = ''
                        this.journals_new[index]['supplier_cost_center_id'] = ''
                        this.journals_new[index]['cc_supplier_id'] = ''

                        this.journals_new[index]['cc_branch_id'] = ''
                    }

                    //سياره
                    if (this.journals_new[index]['cost_center_type_id'] == 56004) {
                        this.journals_new[index]['show_customers'] = false
                        this.journals_new[index]['show_suppliers'] = false
                        this.journals_new[index]['show_employees'] = false
                        this.journals_new[index]['show_cars'] = true
                        this.journals_new[index]['show_branches'] = false

                        this.journals_new[index]['cars_required'] = true

                        this.journals_new[index]['cc_employees_required'] = false
                        this.journals_new[index]['cc_employees_voucher_required'] = false
                        this.journals_new[index]['cost_center_employees_required'] = false
                        this.journals_new[index]['employee_cc_voucher_id'] = ''
                        this.journals_new[index]['employee_cost_center_id'] = ''
                        this.journals_new[index]['cc_employee_id'] = ''

                        this.journals_new[index]['cc_customer_required'] = false
                        this.journals_new[index]['cc_customer_voucher_required'] = false
                        this.journals_new[index]['cost_center_customer_required'] = false
                        this.journals_new[index]['customer_cc_voucher_id'] = ''
                        this.journals_new[index]['customer_cost_center_id'] = ''
                        this.journals_new[index]['cc_customer_id'] = ''

                        this.journals_new[index]['cc_supplier_required'] = false
                        this.journals_new[index]['cc_voucher_required'] = false
                        this.journals_new[index]['cost_center_required'] = false
                        this.journals_new[index]['supplier_cc_voucher_id'] = ''
                        this.journals_new[index]['supplier_cost_center_id'] = ''
                        this.journals_new[index]['cc_supplier_id'] = ''

                        this.journals_new[index]['cc_branch_id'] = ''
                    }

                    ///فرع
                    if (this.journals_new[index]['cost_center_type_id'] == 56005) {
                        this.journals_new[index]['show_customers'] = false
                        this.journals_new[index]['show_suppliers'] = false
                        this.journals_new[index]['show_employees'] = false
                        this.journals_new[index]['show_cars'] = false
                        this.journals_new[index]['show_branches'] = true

                        this.journals_new[index]['cc_branches_required'] = true


                        this.journals_new[index]['cc_employees_required'] = false
                        this.journals_new[index]['cc_employees_voucher_required'] = false
                        this.journals_new[index]['cost_center_employees_required'] = false
                        this.journals_new[index]['employee_cc_voucher_id'] = ''
                        this.journals_new[index]['employee_cost_center_id'] = ''
                        this.journals_new[index]['cc_employee_id'] = ''

                        this.journals_new[index]['cc_customer_required'] = false
                        this.journals_new[index]['cc_customer_voucher_required'] = false
                        this.journals_new[index]['cost_center_customer_required'] = false
                        this.journals_new[index]['customer_cc_voucher_id'] = ''
                        this.journals_new[index]['customer_cost_center_id'] = ''
                        this.journals_new[index]['cc_customer_id'] = ''

                        this.journals_new[index]['cc_supplier_required'] = false
                        this.journals_new[index]['cc_voucher_required'] = false
                        this.journals_new[index]['cost_center_required'] = false
                        this.journals_new[index]['supplier_cc_voucher_id'] = ''
                        this.journals_new[index]['supplier_cost_center_id'] = ''
                        this.journals_new[index]['cc_supplier_id'] = ''


                    }

                },
                deleteRow(id) {
                    $.ajax({
                        type: 'DELETE',
                        data: {"_token": "{{ csrf_token() }}", journal_dt_id: id},
                        url: '{{ route('api.journal-entries.delete') }}'
                    }).then(response => {
                        window.location.reload()
                    })
                },
                addRow() {
                    this.journals_new.push({
                        'account_id': '',
                        'account': {},
                        'journal_dt_debit': 0.00,
                        'journal_dt_date': '{{$current_date}}',
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
                        ///cars
                        'cars_required': false,
                        ///branches
                        'cc_branches_required': false,
                        'cc_car_id': '',
                        'cc_car': {},
                        'cc_branch_id': '{{$branch->branch_id}}',
                        'cc_branch': {!! $branch !!},
                        'journal_dt_notes_required': false,
                        'journal_dt_date_required': false,
                        'journal_dt_debit_required': false,
                        'journal_dt_credit_required': false,
                    })
                },
                subRow(index) {
                    this.journals_new.splice(index, 1)
                },
                getData(index) {
                    this.journals_new[index]['waybills'] = {}
                    this.journals_new[index]['invoices'] = {}
                    if (this.journals_new[index]['supplier_cost_center_id'] || this.journals_new[index]['customer_cost_center_id']
                        || this.journals_new[index]['employee_cost_center_id']) {
                        if (this.journals_new[index]['cc_supplier_id'] || this.journals_new[index]['cc_customer_id']
                            || this.journals_new[index]['cc_employee_id']) {

                            if (this.journals_new[index]['supplier_cost_center_id']) {
                                this.cost_center_id = this.journals_new[index]['supplier_cost_center_id']
                            }

                            if (this.journals_new[index]['customer_cost_center_id']) {
                                this.cost_center_id = this.journals_new[index]['customer_cost_center_id']
                            }

                            if (this.journals_new[index]['employee_cost_center_id']) {
                                this.cost_center_id = this.journals_new[index]['employee_cost_center_id']
                            }

                            $.ajax({
                                type: 'GET',
                                data: {
                                    cc_customer_id: this.journals_new[index]['cc_customer_id'],
                                    cc_supplier_id: this.journals_new[index]['cc_supplier_id'],
                                    cc_employee_id: this.journals_new[index]['cc_employee_id'],
                                    cost_center_id: this.cost_center_id
                                },
                                url: '{{ route("api.journal-entries.get-data") }}'
                            }).then(response => {
                                if (response.invoices) {
                                    this.journals_new[index]['invoices'] = response.invoices
                                }
                                if (response.waybills) {
                                    this.journals_new[index]['waybills'] = response.waybills
                                }
                            })
                        }
                    }


                },
                getSelectedAccount(index) {
                    this.journals_new[index]['account'] = this.accounts.filter((account) => {
                        return account.acc_id == this.journals_new[index]['account_id']
                    })
                },
                getSelectedCostCenter(index) {
                    if (this.isLoaded = true) {
                        if (this.journals_new[index]['show_customers']) {
                            this.journals_new[index]['cc_customer'] = this.customers.filter((customer) => {
                                return customer.customer_id == this.journals_new[index]['cc_customer_id']
                            })
                        }

                        if (this.journals_new[index]['show_suppliers']) {
                            this.journals_new[index]['cc_supplier'] = this.suppliers.filter((supplier) => {
                                return supplier.customer_id == this.journals_new[index]['cc_supplier_id']
                            })
                        }

                        if (this.journals_new[index]['show_branches']) {
                            this.journals_new[index]['cc_branch'] = this.branches.filter((branch) => {
                                return branch.branch_id == this.journals_new[index]['cc_branch_id']
                            })
                        }
                        if (this.journals_new[index]['show_cars']) {
                            this.journals_new[index]['cc_car'] = this.trucks.filter((truck) => {
                                return truck.truck_id == this.journals_new[index]['cc_car_id']
                            })
                        }

                        if (this.journals_new[index]['show_employees']) {
                            this.journals_new[index]['cc_employee'] = this.employees.filter((employee) => {
                                return employee.emp_id == this.journals_new[index]['cc_employee_id']
                            })
                        }
                    }
                }
            },
            computed: {
                disable_button_2: function () {

                    var x = 0;
                    Object.entries(this.journals_new).forEach(([key, val]) => {

                        if (!(val.cost_center_type_id)) {
                            x += 1;
                        }

                        if (val.cost_center_type_id == 56001) { //supplier
                            if (!val.cc_supplier_id) {
                                x += 1;
                            }
                        }


                        if (val.cost_center_type_id == 56002) { //customer
                            if (!val.cc_customer_id) {
                                x += 1;
                            }
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
        });
    </script>
@endsection
@extends('Layouts.master')

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

@section('content')

    <div class="section-body mt-3" id="app">
        <v-app>

            <div class="container-fluid">
                <div class="row clearfix">
                    <div class="col-lg-12">

                        <form action="{{ route('journal-entries.update',$id) }}" method="post">
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
                                            <input type="hidden" name="company_id"
                                                   value="{{$journal_hd->company->company_id}}">
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
                                                   :value="journal_hd.journal_hd_code">
                                        </div>
                                    </div>

                                    {{--انواع يوميات قيود الحسابات--}}
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.daily_accounts_type')</label>
                                            <select name="journal_type_id" class="form-control" disabled="" required
                                                    v-model="journal_type_id">
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($journal_types as $journal_type)
                                                    <option value="{{ $journal_type->system_code_id }}">
                                                        {{ app()->getLocale()=='ar' ?
                                                    $journal_type->system_code_name_ar :
                                                    $journal_type->system_code_name_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    {{--تاريخ اليوميه--}}

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
                                                   name="journal_file_no" v-model="journal_file_no">
                                        </div>
                                    </div>


                                    {{--حاله القيود اليوميه--}}
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.restriction_account_status')</label>

                                            @if($flag>0)
                                                <select class="custom-select" name="journal_status" required
                                                        v-model="journal_status">
                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($journal_statuses as $journal_status)
                                                        <option value="{{$journal_status->system_code_id}}">
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
                                        <textarea class="form-control" name="journal_hd_notes"
                                                  v-model="journal_hd_notes"> </textarea>
                                    </div>


                                </div>
                            </div>

                            <div class="card">

                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                            <tr>
                                                <th class="pl-0" style="width: 350px">@lang('home.account_name')</th>
                                                <th></th>
                                                <th colspan="2" style="width: 600px">@lang('home.notes')</th>
                                                <th></th>
                                                <th class="pr-0">@lang('home.debit')</th>
                                                <th class="pr-0">@lang('home.credit')</th>


                                                <th class="pr-0"></th>
                                            </tr>
                                            </thead>

                                            <tr v-for="(journal,index) in journals">
                                                <input type="hidden" name="old_journal_details_ids[]"
                                                       :value="journals[index]['journal_dt_id']">
                                                <td class="pl-0">
                                                    <v-autocomplete
                                                            required
                                                            :search-input="journals[index]['account_id']"
                                                            v-model="journals[index]['account_obj']"
                                                            :items="accounts"
                                                            item-value="acc_id"
                                                            item-text="acc_name_ar"
                                                            @change="getSelectedAccountOld(index)"
                                                            :label="Number.isInteger(journals[index]['account_obj']) ?
                                                             journals[index]['account'][0].acc_code
                                                            : journals[index]['account_obj'].acc_code"
                                                    ></v-autocomplete>

                                                    <input type="hidden" name="old_account_id[]"
                                                           v-model="typeof(journals[index]['account_obj']) != 'object'
                                                            ? journals[index]['account_obj'] : journals[index]['account_id']">

                                                </td>

                                                <td>
                                                    <button class="btn btn-link" type="button" style="font-weight: bold"
                                                            data-toggle="modal"
                                                            :data-target="'#exampleModalCenter'+index">
                                                        <i class="fa fa-paperclip fa-2x"></i>
                                                    </button>
                                                </td>
                                                {{--الملاحظات--}}
                                                <td colspan="2">
                                                    <label class="form-label">


                                                    </label>

                                                    <label v-if="journals[index]['cost_center_type_id'] == 56002">@lang('home.customers')
                                                        @{{journals[index]['cc_customer'] ?
                                                        journals[index]['cc_customer'].customer_name_full_ar :
                                                        ''}}</label>
                                                    <label v-if="journals[index]['cost_center_type_id'] == 56003">@lang('home.employees')
                                                        @{{journals[index]['cc_employee'] ?
                                                        journals[index]['cc_employee'].emp_name_full_ar :
                                                        ''}}</label>
                                                    <label v-if="journals[index]['cost_center_type_id'] == 56001">@lang('home.suppliers')
                                                        @{{journals[index]['cc_supplier'] ?
                                                        journals[index]['cc_supplier'].customer_name_full_ar :
                                                        ''}}</label>
                                                    <label v-if="journals[index]['cost_center_type_id'] == 56004">@lang('home.cars')
                                                        @{{journals[index]['cc_car'] ?
                                                        journals[index]['cc_car'].truck_name :
                                                        ''}}</label>
                                                    <label v-if="journals[index]['cost_center_type_id'] == 56005">@lang('home.branches')
                                                        @{{journals[index]['cc_branch'] ?
                                                        journals[index]['cc_branch'].branch_name_ar :''}}</label>

                                                    <input type="text" class="form-control"
                                                           name="old_journal_dt_notes[]"
                                                           v-model="journals[index]['journal_dt_notes']">
                                                </td>


                                                <td class="pr-0">
                                                    <input type="datetime-local" class="form-control"
                                                           name="old_journal_dt_date[]"
                                                           v-model="journals[index]['journal_dt_date']">
                                                </td>
                                                {{--مدين--}}
                                                <td class="pr-0">
                                                    <input type="number" class="form-control"
                                                           name="old_journal_dt_debit[]"
                                                           step="0.01"
                                                           v-model="journals[index]['journal_dt_debit']" value="0.00"
                                                           required>
                                                </td>


                                                {{--دائن--}}
                                                <td class="pr-0">
                                                    <input type="number" class="form-control"
                                                           name="old_journal_dt_credit[]"
                                                           step="0.01"
                                                           v-model="journals[index]['journal_dt_credit']" value="0.00"
                                                           required>
                                                </td>


                                                <th class="pr-0">
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                            @click="deleteRow(index)">
                                                        <i class="fa fa-trash-o"></i></button>
                                                </th>
                                            </tr>

                                            <tr v-for="(journal,index) in journals_new">
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
                                                </td>

                                                <td class="pr-0">
                                                    <input type="datetime-local" class="form-control"
                                                           name="journal_dt_date[]"
                                                           v-model="journals_new[index]['journal_dt_date']"
                                                           :required="journals_new[index]['journal_dt_date_required']">
                                                </td>
                                                {{--دائن--}}
                                                <td class="pr-0">
                                                    <input type="number" class="form-control" name="journal_dt_debit[]"
                                                           step="0.01"
                                                           v-model="journals_new[index]['journal_dt_debit']"
                                                           value="0.00"
                                                           :required="journals_new[index]['journal_dt_debit_required']">
                                                </td>


                                                {{--مدين--}}
                                                <td class="pr-0">
                                                    <input type="number" class="form-control" name="journal_dt_credit[]"
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

                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <label>@lang('home.difference')</label>
                                                    <input type="text" name="total_difference" class="form-control"
                                                           v-model="total_difference" readonly>
                                                </td>
                                                <td></td>
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

                                            {{--form old--}}
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
                                                                            name="old_cost_center_type_id[]" disabled
                                                                            @change="putPropRequired(index)"
                                                                            v-model="journals[index]['cost_center_type_id']">
                                                                        @foreach($account_types as $type)
                                                                            <option value="{{$type->system_code}}">
                                                                                {{app()->getLocale()=='ar' ? $type->system_code_name_ar :
                                                                                $type->system_code_name_en }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="customers p-4 mt-1"
                                                                     v-if="journals[index]['cost_center_type_id'] == 56002">
                                                                    <div class="row">
                                                                        {{--العملاء--}}
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="recipient-name"
                                                                                       class="col-form-label">

                                                                                    @lang('home.customers')</label>
                                                                                <select class="form-control" disabled=""
                                                                                        name="old_cc_customer_id[]"
                                                                                        :required="journals[index]['cc_customer_required']"
                                                                                        @change="getData(index)"
                                                                                        v-model="journals[index]['cc_customer_id']">
                                                                                    <option :value="customer.customer_id"
                                                                                            v-for="customer in customers">
                                                                                        @if(app()->getLocale()=='ar')
                                                                                            @{{
                                                                                            customer.customer_name_full_ar
                                                                                            }}
                                                                                        @else
                                                                                            @{{
                                                                                            customer.customer_name_full_en
                                                                                            }}
                                                                                        @endif
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        {{--امر شراء--}}
                                                                        <div class="col-md-3" v-if="journals[index]['customer_cost_center_id'] == 73 ||
                                                                     journals[index]['customer_cost_center_id'] == 70">
                                                                            <div class="form-group">
                                                                                <label for="recipient-name"
                                                                                       class="col-form-label">
                                                                                    @lang('home.type')</label>
                                                                                <select class="form-control customers_cost_center"
                                                                                        name="old_customer_cost_center_id[]"
                                                                                        disabled
                                                                                        :required="journals[index]['cost_center_customer_required']"
                                                                                        @change="getData(index)"
                                                                                        v-model="journals[index]['customer_cost_center_id']">
                                                                                    <option value="">@lang('home.choose')</option>
                                                                                    <option value="70">@lang('home.waybill')</option>
                                                                                    <option value="73">@lang('home.invoice')</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        {{--البوالص او الفواتير--}}
                                                                        <div class="col-md-3" v-if="journals[index]['customer_cost_center_id'] == 73 ||
                                                                     journals[index]['customer_cost_center_id'] == 70">
                                                                            <label class=""> @lang('home.bond')</label>
                                                                            <select name="old_customer_cc_voucher_id[]"
                                                                                    class="form-control" disabled=""
                                                                                    :required="journals[index]['cc_customer_voucher_required']"
                                                                                    v-model="journals[index]['customer_cc_voucher_id']">

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
                                                                     v-if="journals[index]['cost_center_type_id'] == 56001">
                                                                    <div class="row">
                                                                        {{--الموردين--}}
                                                                        <div class="col-md-4">
                                                                            <label>@lang('home.suppliers')</label>
                                                                            <select class="form-control" disabled=""
                                                                                    :required="journals[index]['cc_supplier_required']"
                                                                                    name="old_cc_supplier_id[]"
                                                                                    @change="getData(index)"
                                                                                    v-model="journals[index]['cc_supplier_id']">
                                                                                <option value="">@lang('home.choose')</option>
                                                                                <option :value="supplier.customer_id"
                                                                                        v-for="supplier in suppliers">
                                                                                    @if(app()->getLocale()=='ar')
                                                                                        @{{
                                                                                        supplier.customer_name_full_ar
                                                                                        }}
                                                                                    @else
                                                                                        @{{
                                                                                        supplier.customer_name_full_en
                                                                                        }}
                                                                                    @endif
                                                                                </option>
                                                                            </select>

                                                                        </div>

                                                                        {{--امر شراء--}}
                                                                        <div class="col-md-4" v-if="journals[index]['supplier_cost_center_id'] == 73 ||
                                                                     journals[index]['supplier_cost_center_id'] == 70">
                                                                            <label>@lang('home.buy_command')</label>
                                                                            <select class="form-control" disabled=""
                                                                                    name="old_supplier_cost_center_id[]"
                                                                                    @change="getData(index)"
                                                                                    :required="journals[index]['cost_center_required']"
                                                                                    v-model="journals[index]['supplier_cost_center_id']">
                                                                                <option value="">@lang('home.choose')</option>
                                                                                <option value="70">@lang('home.waybill')</option>
                                                                                <option value="73">@lang('home.invoice')</option>
                                                                            </select>
                                                                        </div>

                                                                        {{--الفواتير او البوالص--}}
                                                                        <div class="col-md-4"
                                                                             v-if="journals[index]['supplier_cost_center_id'] == 73 ||
                                                                     journals[index]['supplier_cost_center_id'] == 70">
                                                                            <label>@lang('home.number')</label>
                                                                            <select class="form-control" disabled=""
                                                                                    name="old_supplier_cc_voucher_id[]"
                                                                                    :required="journals[index]['cc_voucher_required']"
                                                                                    v-model="journals[index]['supplier_cc_voucher_id']"
                                                                            >

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
                                                                     v-if="journals[index]['cost_center_type_id'] == 56003">
                                                                    <div class="row">
                                                                        {{--الموظفين--}}
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="recipient-name"
                                                                                       class="col-form-label">
                                                                                    @lang('home.employees')</label>
                                                                                <select class="form-control" disabled=""
                                                                                        name="old_cc_employee_id[]"
                                                                                        @change="getData(index)"
                                                                                        :required="journals[index]['cc_employees_required']"
                                                                                        v-model="journals[index]['cc_employee_id']">
                                                                                    <option value="">@lang('home.choose')</option>
                                                                                    <option :value="employee.emp_id"
                                                                                            v-for="employee in employees">
                                                                                        @if(app()->getLocale()=='ar')
                                                                                            @{{employee.emp_name_full_ar}}
                                                                                        @else
                                                                                            @{{employee.emp_name_full_en}}
                                                                                        @endif
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        {{--امر شراء--}}
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="recipient-name"
                                                                                       class="col-form-label">
                                                                                    @lang('home.type')</label>
                                                                                <select class="form-control" disabled=""
                                                                                        name="old_employee_cost_center_id[]"
                                                                                        @change="getData(index)"
                                                                                        :required="journals[index]['cost_center_employees_required']"
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
                                                                            <select class="form-control" disabled=""
                                                                                    name="old_employee_cc_voucher_id[]"
                                                                                    :required="journals[index]['cc_employees_voucher_required']"
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
                                                                     v-if="journals[index]['cost_center_type_id'] == 56004">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <select class="form-control" disabled=""
                                                                                    name="old_cc_car_id[]"
                                                                                    v-model="journals[index]['cc_car_id']">
                                                                                <option value="">@lang('home.choose')</option>
                                                                                <option :value="truck.truck_id"
                                                                                        v-for="truck in trucks">
                                                                                    @{{truck.truck_name }} +
                                                                                    @{{truck.truck_code}}
                                                                                </option>

                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="branches p-4 mt-1"
                                                                     v-if="journals[index]['cost_center_type_id'] == 56005">
                                                                    <div class="row">
                                                                        <select class="form-control"
                                                                                name="old_cc_branch_id"
                                                                                disabled=""
                                                                                v-model="journals[index]['cc_branch_id']">
                                                                            <option value="">@lang('home.choose')</option>
                                                                            <option :value="branch.branch_id"
                                                                                    v-for="branch in branches">
                                                                                @if(app()->getLocale()=='ar')
                                                                                    @{{ branch.branch_name_ar }}
                                                                                @else
                                                                                    @{{ branch.branch_name_en }}
                                                                                @endif
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                            {{--end form--}}

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


                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary"
                                            :disabled="disable_button || disable_button_2">@lang('home.add')</button>
                                    <a href="{{config('app.telerik_server')}}?rpt={{$journal_hd->report_url_journal->report_url}}&id={{$id}}&lang=ar&skinName=bootstrap"
                                       title="{trans('Print')}" class="btn btn-primary" id="showReport"
                                       target="_blank">@lang('home.print')
                                    </a>
                                    <a href="{{ route('journal-entries') }}" class="btn btn-primary"
                                       style="display: inline-block; !important;"
                                       id="back">
                                        @lang('home.back')</a>


                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                @if($journal_hd->journalStatus->system_code == 902)
                    @if(auth()->user()->user_type_id != 1)
                        @foreach(session('job')->permissions as $job_permission)
                            @if($job_permission->app_menu_id == 33 && $job_permission->permission_approve)
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="{{route('journal-entries.approveJournal')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="journal_hd_id"
                                                   value="{{$journal_hd->journal_hd_id}}">
                                            <button type="submit" class="btn btn-primary">{{__('approve')}}</button>
                                        </form>
                                    </div>
                                </div>
                            @endif

                        @endforeach
                    @else
                        <div class="row">
                            <div class="col-md-12">
                                <form action="{{route('journal-entries.approveJournal')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="journal_hd_id"
                                           value="{{$journal_hd->journal_hd_id}}">
                                    <button type="submit" class="btn btn-primary">{{__('approve')}}</button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

        </v-app>
    </div>

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>


    <script>
        $(document).ready(function () {
            $('#cc_customer_id').selectpicker();

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

        function getForm(el) {

            if (el.val() == '56001') {
                //مورد
                el.parent().next().css({'display': 'none'})
                el.parent().next().next().css({'display': 'block'}) ////////////////
                el.parent().next().next().next().css({'display': 'none'})
                el.parent().next().next().next().next().css({'display': 'none'})
                el.parent().next().next().next().next().next().css({'display': 'none'})

            }

            if (el.val() == '56002') {
                //عميل
                el.parent().next().css({'display': 'block'}) /////////////////////
                el.parent().next().next().css({'display': 'none'})
                el.parent().next().next().next().css({'display': 'none'})
                el.parent().next().next().next().next().css({'display': 'none'})
                el.parent().next().next().next().next().next().css({'display': 'none'})
            }

            if (el.val() == '56003') {
                //موظف
                el.parent().next().css({'display': 'none'})
                el.parent().next().next().css({'display': 'none'})
                el.parent().next().next().next().css({'display': 'block'}) //////////////
                el.parent().next().next().next().next().css({'display': 'none'})
                el.parent().next().next().next().next().next().css({'display': 'none'})

            }

            if (el.val() == '56004') {
                //سياره
                el.parent().next().css({'display': 'none'})
                el.parent().next().next().css({'display': 'none'})
                el.parent().next().next().next().css({'display': 'none'})
                el.parent().next().next().next().next().css({'display': 'block'}) ///////////////
                el.parent().next().next().next().next().next().css({'display': 'none'})
            }

            if (el.val() == '56005') {
                ////فرع
                el.parent().next().css({'display': 'none'})
                el.parent().next().next().css({'display': 'none'})
                el.parent().next().next().next().css({'display': 'none'})
                el.parent().next().next().next().next().css({'display': 'none'})
                el.parent().next().next().next().next().next().css({'display': 'block'})
            }

        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>

    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data: {
                journal_hd_id: '{{$id}}',
                company_id: '',
                branches: [],
                branch_id: '',
                journal_status: '',
                journal_hd_notes: '',
                journal_hd_date: '',
                journal_file_no: '',
                journal_type_id: '',

                customers: [],
                suppliers: [],
                employees: [],
                trucks: [],
                accounts: [],
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
                journals: [],
                journal_hd: {},
                journal_dts: {},
                disable_button: false
            },
            mounted() {
                this.getJournal()
            },
            methods: {
                validateData(index) {
                    if (this.journals_new[index]['account_id']) {
                        this.journals_new[index]['journal_dt_notes_required'] = true
                        this.journals_new[index]['journal_dt_debit_required'] = true
                        this.journals_new[index]['journal_dt_credit_required'] = true
                        this.journals_new[index]['journal_dt_date_required'] = false
                    } else {
                        this.journals_new[index]['journal_dt_notes_required'] = false
                        this.journals_new[index]['journal_dt_debit_required'] = false
                        this.journals_new[index]['journal_dt_credit_required'] = false
                        this.journals_new[index]['journal_dt_date_required'] = false
                    }
                },
                deleteRow(index) {
                    $.ajax({
                        type: 'DELETE',
                        data: {"_token": "{{ csrf_token() }}", journal_dt_id: this.journals[index]['journal_dt_id']},
                        url: '{{ route('api.journal-entries.delete') }}'
                    }).then(response => {
                        // console.log(response)
                        this.journals.splice(index, 1);
                    })
                },
                getJournal() {
                    $.ajax({
                        type: 'GET',
                        data: {journal_hd_id: this.journal_hd_id},
                        url: ''
                    }).then(response => {
                        this.journal_hd = response.data
                        // this.journal_dts = response.journal_dts

                        this.company_id = this.journal_hd.company_id
                        this.getBranches();
                        this.branch_id = this.journal_hd.branch_id
                        this.journal_status = this.journal_hd.journal_status
                        this.journal_hd_notes = this.journal_hd.journal_hd_notes
                        this.journal_hd_date = this.journal_hd.journal_hd_date
                        this.journal_file_no = this.journal_hd.journal_file_no
                        this.journal_type_id = this.journal_hd.journal_type_id
                        this.journals = response.journal_dts
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

                        this.journals_new[index]['cc_branch_id'] = ''
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
                    // this.journals[index]['account'] = this.accounts
                    this.journals_new[index]['account'] = this.accounts.filter((account) => {
                        return account.acc_id == this.journals_new[index]['account_id']
                    })
                },
                getSelectedAccountOld(index) {
                    this.journals[index]['account'] = this.accounts.filter((account) => {
                        return account.acc_id == this.journals[index]['account_obj']
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
                total_credit: function () {
                    let total = 0.00;
                    let total_new = 0.00;
                    var tdc = 0.00;
                    Object.entries(this.journals).forEach(([key, val]) => {
                        total += (parseFloat(val.journal_dt_credit))
                    });
                    Object.entries(this.journals_new).forEach(([key, val]) => {
                        total_new += (parseFloat(val.journal_dt_credit))
                    });

                    tdc = total + total_new
                    return tdc.toFixed(2);
                },
                total_debit: function () {
                    let total = 0.00;
                    let total_new = 0.00;
                    var tdd = 0.00;
                    Object.entries(this.journals).forEach(([key, val]) => {
                        total += (parseFloat(val.journal_dt_debit))
                    });
                    Object.entries(this.journals_new).forEach(([key, val]) => {
                        total_new += (parseFloat(val.journal_dt_debit))
                    });

                    tdd = total + total_new

                    return tdd.toFixed(2);
                },
                total_difference: function () {
                    var td = this.total_credit - this.total_debit

                    if (td != 0.00) {
                        this.disable_button = true
                    } else {
                        this.disable_button = false
                    }

                    return td.toFixed(2);
                },
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

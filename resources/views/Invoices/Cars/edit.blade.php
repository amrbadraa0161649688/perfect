@extends('Layouts.master')
@section('content')
    <div class="section-body mt-3" id="app">

        <div class="section-body">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item">
                            <a href="#data-grid" data-toggle="tab"
                               class="nav-link active">@lang('home.data')</a>
                        </li>

                        <li class="nav-item"><a class="nav-link" href="#files-grid"
                        style="font-size: 18px ;font-weight: bold"
                                                data-toggle="tab">@lang('home.files')</a></li>
                        <li class="nav-item"><a class="nav-link" href="#notes-grid"
                        style="font-size: 18px ;font-weight: bold"
                                                data-toggle="tab">@lang('home.notes')</a></li>

                        <li class="nav-item"><a class="nav-link" href="#bonds-cash-grid"
                                                                style="font-size: 18px ;font-weight: bold"
                                                                data-toggle="tab">@lang('home.bonds_cash')</a></li>

                        <li class="nav-item"><a class="nav-link" href="#bonds-capture-grid"
                                                                style="font-size: 18px ;font-weight: bold"
                                                                data-toggle="tab">@lang('home.bonds_capture')</a></li>                        

                    </ul>
                    <div class="header-action"></div>
                </div>
            </div>
        </div>


        <div class="container-fluid">

            <div class="tab-content mt-3">
                {{-- dATA --}}
                <div class="tab-pane fade  active show"
                     id="data-grid" role="tabpanel">

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
                                                @lang('invoice.add_invoice_car')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('Invoices.cars.update',$invoice->invoice_id) }}"
                                          method="post">
                                        @csrf
                                        @method('put')
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <div class="row">

                                                        <div class="col-md-3">
                                                            <label class="form-label">@lang('invoice.invoice_no')</label>
                                                            <input type="text" disabled=""
                                                                   value="{{ $invoice->invoice_no}}"
                                                                   class="form-control">
                                                        </div>

                                                        <div hidden class="col-md-3">
                                                            <label class="form-label">@lang('home.companies')</label>
                                                            <input type="text" disabled="" value="{{ app()->getLocale()=='ar' ? $invoice->company->company_name_ar :
                                                     $invoice->company->company_name_en }}"
                                                                   class="form-control">
                                                        </div>

                                                        <div class="col-md-3">
                                                            <label class="form-label">@lang('home.account_periods')</label>
                                                            <select class="form-control"
                                                                    name="acc_period_id" required>
                                                                <option value="{{ $invoice->acc_period_id}}">@lang('home.choose')</option>
                                                                @foreach($account_periods as $account_period)
                                                                    <option value="{{ $account_period->acc_period_id }}"
                                                                            @if($invoice->acc_period_id == $account_period->acc_period_id)
                                                                            selected @endif>{{app()->getLocale() == 'ar' ?
                                                            $account_period->acc_period_name_ar : $account_period->acc_period_name_en}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <label class="form-label">@lang('home.created_date')</label>
                                                            <input type="text" class="form-control" name="invoice_date"
                                                                   id="invoice_date"
                                                                   value="{{ $invoice->invoice_date }}"
                                                                   placeholder="@lang('invoice.invoice_date')" disabled>
                                                        </div>
                                                        <div class="col-md-3">
                                                                        <label class="form-label">@lang('home.user')</label>
                                                                        <input type="text" calss="form-control" disabled
                                                                               class="form-control"
                                                                               value="@if(app()->getLocale()=='ar') {{ auth()->user()->user_name_ar }}
                                                                               @else {{ auth()->user()->user_name_en }} @endif">
                                                                    </div>

                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <div class="row">

                                                                   

                                                                    <div class="col-md-3">
                                                                        <label for="recipient-name"
                                                                               class="form-label"> @lang('invoice.customer_name') </label>
                                                                        <input type="text" class="form-control"
                                                                               name="customer_id"
                                                                               id="customer_id"
                                                                               disabled=""
                                                                               value="{{app()->getLocale() == 'ar' ?
                                                                 $invoice->customer->customer_name_full_ar : $invoice->customer->customer_name_full_en }}">

                                                                    </div>


                                                                    <div class="col-md-3">
                                                                        <label class="form-label">@lang('invoice.invoice_due_date')</label>
                                                                        <input type="date" class="form-control"
                                                                               name="invoice_due_date"
                                                                               id="invoice_due_date"
                                                                               value="{{ $invoice->invoice_due_date }}"
                                                                               required>
                                                                    </div>

                                                                    <div class="col-md-3">
                                                                        <label>@lang('home.invoice_notes')</label>
                                                                        <textarea class="form-control"
                                                                                  name="invoice_notes">{{$invoice->invoice_notes}}</textarea>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label class="form-label">@lang('invoice.userUpdated')</label>
                                                                        <input type="text" calss="form-control" disabled
                                                                               class="form-control"
                                                                               value="{{$invoice->userCreated->user_name_ar ? $invoice->userCreated->user_name_ar : ''}}"
                                                                             >
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <label for="recipient-name"
                                                                               class="col-form-label"> @lang('invoice.customer_name') </label>
                                                                        <input type="text"
                                                                               class="form-control is-invalid"
                                                                               name="customer_name"
                                                                               id="customer_name"
                                                                               value="{{$invoice->customer_name ? $invoice->customer_name : ''}}"
                                                                               placeholder="@lang('invoice.customer_name')">

                                                                    </div>


                                                                    <div class="col-md-2">
                                                                        <label for="recipient-name"
                                                                               class="col-form-label"> {{__('account')}} </label>
                                                                        <input type="text"
                                                                               class="form-control" name="" id=""
                                                                               readonly
                                                                               value="{{$invoice->customer->account->acc_code}}"
                                                                               required>

                                                                    </div>

                                                                    <div class="col-md-3">
                                                                        <label for="recipient-name"
                                                                               class="col-form-label"> @lang('invoice.customer_address') </label>
                                                                        <input type="text"
                                                                               class="form-control is-invalid"
                                                                               name="customer_address"
                                                                               id="customer_address"
                                                                               value="{{$invoice->customer_address ? $invoice->customer_address  : ''}}"
                                                                               placeholder="@lang('invoice.customer_address')"
                                                                        >

                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <label for="recipient-name"
                                                                               class="col-form-label"> @lang('invoice.customer_tax_no') </label>
                                                                        <input type="text"
                                                                               class="form-control is-invalid"
                                                                               name="customer_tax_no"
                                                                               id="customer_tax_no"
                                                                               value="{{$invoice->customer_tax_no ? $invoice->customer_tax_no : ''}}"
                                                                               placeholder="@lang('invoice.customer_tax_no')"
                                                                        >

                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <label for="recipient-name"
                                                                               class="col-form-label"> @lang('invoice.customer_phone') </label>
                                                                        <input type="text"
                                                                               class="form-control is-invalid"
                                                                               name="customer_phone"
                                                                               id="customer_phone"
                                                                               value="{{$invoice->customer_phone ? $invoice->customer_phone : ''}}"
                                                                               placeholder="@lang('invoice.customer_phone')"
                                                                        >

                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <label for="recipient-name"
                                                                               class="col-form-label"> @lang('invoice.po_number') </label>
                                                                        <input type="text" class="form-control "
                                                                               name="po_number"
                                                                               value="{{$invoice->po_number ? $invoice->po_number : ''}}"
                                                                               id="po_number" required
                                                                               placeholder="@lang('invoice.po_number')">

                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label for="recipient-name"
                                                                               class="col-form-label"> @lang('invoice.payment_tems') </label>
                                                                        <input type="text" class="form-control "
                                                                               name="payment_tems"
                                                                               value="{{$invoice->payment_tems ? $invoice->payment_tems : ''}}"
                                                                               id="payment_tems" required
                                                                               placeholder="@lang('invoice.payment_tems')">

                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label for="recipient-name"
                                                                               class="col-form-label"> @lang('invoice.gr_number') </label>
                                                                        <input type="text" class="form-control "
                                                                               name="gr_number"
                                                                               id="gr_number" required
                                                                               value="{{$invoice->gr_number ? $invoice->gr_number : ''}}"
                                                                               placeholder="@lang('invoice.gr_number')">

                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label class="form-label">@lang('invoice.supply_date')</label>
                                                                        <input type="date" class="form-control"
                                                                               name="supply_date"
                                                                               id="supply_date" required
                                                                               value="{{$invoice->supply_date ? $invoice->supply_date : ''}}"
                                                                               placeholder="@lang('invoice.supply_date')">
                                                                    </div>

                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row mb-3">

                                                        <div class="col-md-4">
                                                            <label class="form-label">@lang('home.invoice_status')</label>
                                                            <input type="text" disabled="" value="{{app()->getLocale() == 'ar' ?
                                                            \App\Models\SystemCode::where('system_code',$invoice->invoice_status)->first() ->system_code_name_ar :
                                                            \App\Models\SystemCode::where('system_code',$invoice->invoice_status)->first()->system_code_name_en}}"
                                                                   class="form-control">
                                                        </div>


                                                        @if($invoice->invoice_status == 121001 || $invoice->invoice_status == 121002)
                                                            <div class="col-md-4">
                                                                <label class="form-label">@lang('home.invoice_status')</label>
                                                                <select class="form-control"
                                                                        name="invoice_status">
                                                                    @foreach($invoice_status_1 as $invoice_status_11)

                                                                        @if($invoice->invoice_status == 121001)

                                                                            <option value="{{ $invoice_status_11->system_code }}">{{app()->getLocale() == 'ar' ?
                                                                            $invoice_status_11->system_code_name_ar :
                                                                            $invoice_status_11->system_code_name_en}}</option>

                                                                        @endif
                                                                    @endforeach
                                                                    @if($invoice->invoice_status == 121002)
                                                                        @foreach($invoice_status_2 as $invoice_status_22)
                                                                            <option value="{{ $invoice_status_22->system_code }}">{{app()->getLocale() == 'ar' ?
                                                                            $invoice_status_22->system_code_name_ar :
                                                                            $invoice_status_22->system_code_name_en}}</option>
                                                                        @endforeach
                                                                    @endif


                                                                </select>
                                                            </div>
                                                        @endif

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

                                                        {{--<div class="col-md-4">--}}
                                                        {{--<button type="button" @click="getCarWaybills()"--}}
                                                        {{--:disabled="disable_button"--}}
                                                        {{--class="btn btn-info">@lang('home.search')</button>--}}
                                                        {{--</div>--}}

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
                                                                    <th style="width:220px"
                                                                        class="text-center">@lang('home.waybill_no')</th>
                                                                    <th style="width:150px"
                                                                        class="text-center">@lang('invoice.load_date')</th>
                                                                    <th style="width:160px"
                                                                        class="text-center">@lang('invoice.contract_no')</th>
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
                                                                    <th style="width:150px"
                                                                        class="text-center">@lang('home.waybill_car_plate')</th>
                                                                    <th style="width:200px"
                                                                        class="text-center">@lang('home.waybill_car_desc')</th>
                                                                    <th hidden style="width:140px"
                                                                        class="text-center">@lang('home.waybill_item_amount')</th>
                                                                    <th hidden style="width:140px"
                                                                        class="text-center">@lang('home.waybill_vat_amount')</th>

                                                                    <th style="width:140px"
                                                                        class="text-center">@lang('invoice.total')</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>

                                                                    <td></td>
                                                                    <td>
                                                                        <input type="text" class="form-control"
                                                                               name="waybill_code"
                                                                               v-model="waybill_code"
                                                                               placeholder="@lang('home.waybill_no')">

                                                                    </td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td><input type="text"
                                                                               class="form-control"
                                                                               name="waybill_loc_from"
                                                                               v-model="waybill_loc_from"
                                                                               placeholder="@lang('home.from')">
                                                                    </td>
                                                                    <td><input type="text"
                                                                               class="form-control"
                                                                               name="waybill_loc_to"
                                                                               v-model="waybill_loc_to"
                                                                               placeholder="@lang('home.to')">
                                                                    </td>
                                                                    <td></td>
                                                                    <td><input type="text"
                                                                               class="form-control"
                                                                               v-model="waybill_car_plate"
                                                                               name="waybill_car_plate"
                                                                               placeholder="@lang('home.waybill_car_plate')">
                                                                    </td>
                                                                    <td></td>
                                                                    <td></td>


                                                                </tr>

                                                                @foreach($invoice->waybillCars as $waybill_car)
                                                                    <tr class="clone">

                                                                        <td>
                                                                            <input type="checkbox"
                                                                                   @if($invoice->invoice_status == 121003 || $invoice->invoice_status == 121004)
                                                                                   disabled @endif
                                                                                   value="{{ $waybill_car->waybill_id}}"
                                                                                   name="waybill_id[]" checked
                                                                                   class="checkboxSelection">
                                                                        </td>

                                                                        <td>
                                                                            <a href="{{ route('Waybill.edit_car',$waybill_car->waybill_id) }}"
                                                                               class="btn btn-link btn-sm"
                                                                               target="_blank">
                                                                                {{ $waybill_car->waybill_code }}
                                                                            </a>
                                                                        </td>
                                                                        <td style="font-size: 14px ;font-weight: bold"> {{date('d-m-y', strtotime($waybill_car->waybill_load_date)) }}</td>
                                                                        <td>{{ $waybill_car->waybill_ticket_no }}</td>
                                                                        <td style="font-size: 12px ;font-weight: bold;color: blue ;width:50px">{{ $waybill_car->waybill_trip_id  ? 'مرحله' : ''}}</td>
                                                                        <td>
                                                                            {{ app()->getLocale()=='ar' ? $waybill_car->detailsCar->item->system_code_name_ar :
                                                                             $waybill_car->detailsCar->item->system_code_name_en}}
                                                                        </td>
                                                                        <td>
                                                                            {{ app()->getLocale()=='ar' ? $waybill_car->locFrom->system_code_name_ar :
                                                                            $waybill_car->locFrom->system_code_name_en}}
                                                                        </td>
                                                                        <td>
                                                                            {{ app()->getLocale()=='ar' ? $waybill_car->locTo->system_code_name_ar :
                                                                       $waybill_car->locTo->system_code_name_en}}
                                                                        </td>
                                                                        <td> {{ $waybill_car->detailsCar->waybill_car_chase }}</td>
                                                                        <td style="font-size: 16px ;font-weight: bold"> {{ $waybill_car->detailsCar->waybill_car_plate }}</td>
                                                                        <td> {{ $waybill_car->detailsCar->waybill_car_desc }}</td>
                                                                        <td hidden><input hidden type="number"
                                                                                          class="form-control"
                                                                                          name="waybill_item_amount[]"
                                                                                          readonly
                                                                                          value="{{($waybill_car->detailsCar->waybill_item_amount *
                                                                           $waybill_car->detailsCar->waybill_item_quantity) + $waybill_car->detailsCar->waybill_add_amount - $waybill_car->detailsCar->waybill_discount_total}}">
                                                                        </td>
                                                                        <td hidden><input hidden type="number"
                                                                                          class="form-control"
                                                                                          name="waybill_vat_amount[]"
                                                                                          readonly
                                                                                          value="{{ $waybill_car->waybill_vat_amount}}">
                                                                        </td>

                                                                        <td><input type="number" class="form-control"
                                                                                   style="font-size: 14px ;font-weight: bold"
                                                                                   name="waybill_total_amount[]"
                                                                                   value="{{ number_format($waybill_car->waybill_total_amount,2)}}"
                                                                                   readonly></td>

                                                                    </tr>
                                                                @endforeach

                                                                @if($invoice->invoice_status == 121001 || $invoice->invoice_status == 121002)

                                                                    <tr class="clone"
                                                                        v-for="waybill in filteredWaybills4">
                                                                        <td>
                                                                            <input type="checkbox"
                                                                                   :value="waybill.waybill_id"
                                                                                   name="waybill_id[]"
                                                                                   class="checkboxSelection">
                                                                        </td>
                                                                        <td>
                                                                            <a :href="'{{env("APP_URL")}}'+'/Waybillcar-add/' + waybill.waybill_id +'/edit' "
                                                                               class="btn btn-link btn-sm"
                                                                               target="_blank">
                                                                                @{{ waybill.waybill_code }}
                                                                            </a>


                                                                        </td>
                                                                        <td style="font-size: 14px ;font-weight: bold">
                                                                            @{{waybill.waybill_load_date }}
                                                                        </td>
                                                                        <td>@{{ waybill.waybill_ticket_no }}</td>
                                                                        <td style="font-size: 12px ;font-weight: bold;color: blue ;width:50px">
                                                                            @{{ waybill.waybill_trip_id }}
                                                                        </td>
                                                                        <td>
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
                                                                        <td style="font-size: 16px ;font-weight: bold">
                                                                            @{{
                                                                            waybill.waybill_car_plate }}
                                                                        </td>
                                                                        <td> @{{ waybill.waybill_car_desc }}</td>
                                                                        <td hidden><input hidden type="text"
                                                                                          class="form-control"
                                                                                          name="waybill_item_amount[]"
                                                                                          readonly
                                                                                          :value="(waybill.waybill_item_amount * waybill.waybill_item_quantity)">
                                                                        </td>
                                                                        <td hidden><input hidden type="text"
                                                                                          class="form-control"
                                                                                          name="waybill_vat_amount[]"
                                                                                          readonly
                                                                                          :value="waybill.waybill_vat_amount">
                                                                        </td>


                                                                        <td><input type="text" class="form-control"
                                                                                   name="waybill_total_amount[]"
                                                                                   style="font-size: 14px ;font-weight: bold"
                                                                                   :value="waybill.waybill_total_amount"
                                                                                   readonly></td>

                                                                    </tr>

                                                                @endif

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <button class="btn btn-primary mt-2" type="submit">
                                                                @lang('home.save')</button>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <a
                                                                    href="{{config('app.telerik_server')}}?rpt={{$invoice->report_url_car->report_url}}&id={{$invoice->invoice_id}}&lang=ar&skinName=bootstrap"
                                                                    title="{trans('Print')}"
                                                                    class="btn btn-primary btn-sm"
                                                                    id="showReport"
                                                                    target="_blank">  @lang('home.print')
                                                                <i class="btn btn-primary mt-2"></i>
                                                            </a>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <a href="{{ route('invoicesCars') }}"
                                                               class="btn btn-primary"
                                                               style="display: inline-block; !important;"
                                                               id="back">
                                                                @lang('home.back')</a>
                                                        </div>

                                                        <div class="row">
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3">


                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- files part --}}
                <div class="tab-pane fade" id="files-grid" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-12">

                            <x-files.form>
                                <input type="hidden" name="transaction_id" value="{{ $invoice->invoice_id }}">
                                <input type="hidden" name="app_menu_id" value="119">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('home.attachment_type')</label>
                                        <select class="form-control" name="attachment_type" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($attachment_types as $attachment_type)
                                                <option value="{{ $attachment_type->system_code_id }}">
                                                    {{app()->getLocale()=='ar' ? $attachment_type->system_code_name_ar
                                                     : $attachment_type->system_code_name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </x-files.form>

                            <x-files.attachment>

                                @foreach($attachments as $attachment)
                                    <tr>
                                        <td>{{ app()->getLocale()=='ar' ?
                                         $attachment->attachmentType_2->system_code_name_ar :
                                          $attachment->attachmentType_2->system_code_name_en}}</td>
                                        <td>{{ date('d-m-Y', strtotime($attachment->issue_date )) }}</td>
                                        <td>{{ date('d-m-Y', strtotime($attachment->expire_date )) }}</td>
                                        <td>{{ $attachment->issue_date_hijri }}</td>
                                        <td>{{ $attachment->expire_date_hijri }}</td>
                                        <td>{{ $attachment->copy_no }}</td>
                                        <td>
                                            <a href="{{ url('/attachments/download-pdf?name=' .
                                             $attachment->attachment_file_url) }}">
                                                <i class="fa fa-download text-blue fa-2x"></i></a>
                                            <a href="{{ asset('Files/'.$attachment->attachment_file_url) }}"
                                               target="_blank" class="mr-1 ml-1"><i class="fa fa-eye text-blue"
                                                                                    style="font-size:20px"></i></a>
                                        </td>
                                        <td>
                                            <div class="badge text-gray text-wrap" style="width: 400px;">
                                                {{ $attachment->attachment_data }}</div>
                                        </td>
                                        <td>{{ $attachment->userCreated->user_name_ar }}</td>
                                        <td>{{ $attachment->created_at }}</td>
                                    </tr>
                                @endforeach

                            </x-files.attachment>

                        </div>
                    </div>
                </div>


                {{-- notes part --}}
                <div class="tab-pane fade" id="notes-grid" role="tabpanel">

                    <div class="row">
                        <div class="col-lg-12">
                            <x-files.form-notes>
                                <input type="hidden" name="transaction_id" value="{{ $invoice->invoice_id }}">
                                <input type="hidden" name="app_menu_id" value="119">
                            </x-files.form-notes>

                            <x-files.notes>
                                @foreach($notes as $note)
                                    <tr>
                                        <td>
                                            <div class="badge text-gray text-wrap" style="width: 400px;">
                                                {{ $note->notes_data }}</div>
                                        </td>
                                        <td>{{ date('d-m-Y', strtotime($note->notes_date )) }}</td>
                                        <td>{{ $note->user->user_name_ar }}</td>
                                        <td>{{ $note->notes_serial }}</td>
                                    </tr>
                                @endforeach
                            </x-files.notes>
                        </div>
                    </div>


                </div>


                {{--start  bond  part--}}
                        <div class="tab-pane fade" id="bonds-cash-grid" role="tabpanel">
                            <div class="card-body">
                                <div class="row card">
                                    <div class="table-responsive table_e2">
                                        <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                            <thead>
                                            <tr>
                                                <th>@lang('home.bonds_number')</th>
                                                <th>@lang('home.bonds_date')</th>

                                                <th>@lang('home.branch')</th>

                                                <th>@lang('home.payment_method')</th>
                                                <th>@lang('home.value')</th>
                                                <th>@lang('home.user')</th>
                                                <th>@lang('home.journal')</th>
                                                <th></th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($bonds_cash as $bond)
                                                <tr>
                                                    <td>{{ $bond->bond_code }}</td>
                                                    <td>{{ $bond->created_date }}</td>


                                                    <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->branch->branch_name_ar :
                                            $bond->branch->branch_name_en }}</td>

                                                    <td>{{ app()->getLocale() == 'ar' ? $bond->paymentMethod->system_code_name_ar :
                                              $bond->paymentMethod->system_code_name_en }}</td>
                                                    <td style="font-size: 16px ;font-weight: bold">{{ $bond->bond_amount_credit }}</td>
                                                    <td>{{ app()->getLocale()=='ar' ? $bond->userCreated->user_name_ar :
                                            $bond->userCreated->user_name_en }}</td>
                                                    <td>
                                                        @if($bond->journalCash)
                                                            <a href="{{ route('journal-entries.show',$bond->journalCash->journal_hd_id) }}"
                                                               class="btn btn-primary btn-sm">
                                                                @lang('home.journal_details')
                                                                {{$bond->journalCash->journal_hd_code}}
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{config('app.telerik_server')}}?rpt={{$bond->report_url_payment->report_url}}&id={{$bond->bond_id}}&lang=ar&skinName=bootstrap"
                                                           class="btn btn-primary btn-sm"
                                                           title="@lang('home.show')"><i
                                                                    class="fa fa-print"></i></a>
                                                        <a href="{{ route('Bonds-cash.show',$bond->bond_id) }}"
                                                           class="btn btn-primary btn-sm"
                                                           title="@lang('home.show')"><i
                                                                    class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="tab-pane fade" id="bonds-capture-grid" role="tabpanel">
                            <div class="card-body">
                                <div class="row card">
                                    <div class="table-responsive table_e2">
                                        <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                            <thead>
                                            <tr>
                                                <th>@lang('home.bonds_number')</th>
                                                <th>@lang('home.bonds_date')</th>

                                                <th>@lang('home.branch')</th>

                                                <th>@lang('home.payment_method')</th>
                                                <th>@lang('home.value')</th>
                                                <th></th>
                                                <th>@lang('home.user')</th>
                                                <th>@lang('home.journal')</th>


                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($bonds_capture as $bond)
                                                <tr>
                                                    <td>{{ $bond->bond_code }}</td>
                                                    <td>{{ $bond->created_date }}</td>


                                                    <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->branch->branch_name_ar :
                                            $bond->branch->branch_name_en }}</td>

                                                    <td>
                                                        @if($bond->bond_method_type)
                                                            {{ app()->getLocale() == 'ar' ? \App\Models\SystemCode::where('system_code',$bond->bond_method_type)
                                                            ->first()->system_code_name_ar :
                                                          \App\Models\SystemCode::where('system_code',$bond->bond_method_type)
                                                            ->first()->system_code_name_en }}
                                                        @endif
                                                    </td>
                                                    <td style="font-size: 16px ;font-weight: bold">{{ $bond->bond_amount_debit }}</td>
                                                    <td>
                                                        <a href="{{config('app.telerik_server')}}?rpt={{$bond->report_url_receipt->report_url}}&id={{$bond->bond_id}}&lang=ar&skinName=bootstrap"
                                                           class="btn btn-primary btn-sm"
                                                           title="@lang('home.print')"><i
                                                                    class="fa fa-print"></i></a>

                                                        <a href="{{ route('Bonds-capture.show',$bond->bond_id) }}"
                                                           class="btn btn-primary btn-sm"
                                                           title="@lang('home.show')"><i
                                                                    class="fa fa-eye"></i></a>
                                                    </td>
                                                    <td>{{ app()->getLocale()=='ar' ? $bond->userCreated->user_name_ar :
                                            $bond->userCreated->user_name_en }}</td>
                                                    <td>

                                                        @if($bond->journalBondInvoiceSales)
                                                            <a href="{{ route('journal-entries.show',$bond->journalBondInvoiceSales
                                                        ->journal_hd_id) }}"
                                                               class="btn btn-primary btn-sm">
                                                                @lang('home.journal_details')
                                                                {{$bond->journalBondInvoiceSales->journal_hd_code}}
                                                            </a>
                                                        @endif

                                                    </td>

                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--end bond part--}}


            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">

        $(function () {
            $("#issue_date_hijri").hijriDatePicker();
            $("#expire_date_hijri").hijriDatePicker();
        });

    </script>
    <script>
        $(document).ready(function () {

            $('#add_files').click(function () {
                var display = $("#add_files_form").css("display");
                if (display == 'none') {
                    $('#add_files_form').css('display', 'block')
                } else {
                    $('#add_files_form').css('display', 'none')
                }

            });

            $('#add_note').click(function () {
                var display = $("#add_note_form").css("display");
                if (display == 'none') {
                    $('#add_note_form').css('display', 'block')
                } else {
                    $('#add_note_form').css('display', 'none')
                }
            });

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

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                expire_date: '',
                issue_date: '',
                issue_date_hijri: '',
                expire_date_hijri: '',
                date: '',
                invoice_id: '',
                invoice: {},
                company_id: '',
                customer_id: '',
                waybills: [],
                isLoaded: false,
                waybill_code: '',
                waybill_loc_from: '',
                waybill_loc_to: '',
                waybill_car_plate: '',
                from_date: '',
                to_date: '',
                date_error_message: '',
                disable_button: false
            },
            mounted() {

                this.invoice_id = '{{$id}}'

                this.getInvoice()

                $('#issue_date_hijri').on("dp.change", (e) => {
                    this.issue_date_hijri = $('#issue_date_hijri').val()
                    this.getGeorgianDate()
                });

                $('#expire_date_hijri').on("dp.change", (e) => {
                    this.expire_date_hijri = $('#expire_date_hijri').val()
                    this.getGeorgianDate2()
                });

            },
            methods: {
                getGeorgianDate() {
                    if (this.issue_date_hijri) {
                        $.ajax({
                            type: 'GET',
                            data: {date: this.issue_date_hijri},
                            url: '{{ route("test-date") }}'
                        }).then(response => {
                            this.issue_date = response.data
                        })
                    }
                },
                getGeorgianDate2() {
                    if (this.expire_date_hijri) {
                        $.ajax({
                            type: 'GET',
                            data: {date: this.expire_date_hijri},
                            url: '{{ route("test-date") }}'
                        }).then(response => {
                            this.expire_date = response.data
                        })
                    }
                },
                getIssueDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.issue_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.issue_date_hijri = response.data
                    })
                },
                getExpireDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.expire_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.expire_date_hijri = response.data
                    })
                },
                getIssueDateHijri() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.issue_date_hijri},
                        url: '{{ route("api.getDate2") }}'
                    }).then(response => {
                        this.issue_date = response.data
                    })
                },
                getInvoice() {
                    $.ajax({
                        type: 'GET',
                        data: {id: this.invoice_id},
                        url: ''
                    }).then(response => {
                        this.invoice = response.data
                        this.customer_id = this.invoice.customer_id
                        this.company_id = this.invoice.company_id

                        // this.getCarWaybills()
                    })
                },
                getCarWaybills() {
                    this.date_error_message = ''

                    this.waybills = []
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
                                url: '{{route('waybills-car.getWaybills')}}'
                            }).then(response => {
                                this.isLoaded = true
                                this.waybills = response.data
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
            }
        });
    </script>

@endsection
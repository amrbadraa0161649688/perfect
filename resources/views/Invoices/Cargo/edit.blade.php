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
                                                data-toggle="tab">@lang('home.files')</a></li>
                        <li class="nav-item"><a class="nav-link" href="#notes-grid"
                                                data-toggle="tab">@lang('home.notes')</a></li>

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
                                                @lang('invoice.add_invoice_cargo')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">

                                    @if(request()->message)
                                        <div class="alert alert-danger">
                                            {{request()->message}}
                                        </div>
                                    @endif
                                    <form action="{{ route('Invoices.cargo.update',$invoice->invoice_id) }}"
                                          method="post">
                                        @csrf
                                        @method('put')
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <div class="row">

                                                        <div class="col-md-2">
                                                            <label class="form-label">@lang('invoice.invoice_no')</label>
                                                            <input type="text" disabled=""
                                                                   value="{{ $invoice->invoice_no}}"
                                                                   class="form-control">
                                                        </div>

                                                        <div class="col-md-3">
                                                            <label class="form-label">@lang('home.companies')</label>
                                                            <input type="text" disabled="" value="{{ app()->getLocale()=='ar' ? $invoice->company->company_name_ar :
                                                     $invoice->company->company_name_en }}"
                                                                   class="form-control">
                                                        </div>

                                                        <div class="col-md-2">
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

                                                        <div class="col-md-2">
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

                                                                    <input type="hidden" name="customer_vat_rate"
                                                                           id="customer_vat_rate"
                                                                           value="{{$invoice->customer->customer_vat_rate >0 ? $invoice->customer->customer_vat_rate : 0}}">

                                                                    <div class="col-md-3">
                                                                        <label class="form-label">@lang('invoice.invoice_due_date')</label>
                                                                        <input type="date" class="form-control"
                                                                               name="invoice_due_date"
                                                                               id="invoice_due_date"
                                                                               value="{{ $invoice->invoice_due_date }}"
                                                                               required>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label class="form-label">@lang('invoice.supply_date')</label>
                                                                        <input type="date" class="form-control"
                                                                               name="supply_date"
                                                                               id="supply_date"
                                                                               value="{{$invoice->supply_date ? $invoice->supply_date : ''}}"
                                                                               placeholder="@lang('invoice.supply_date')">
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label>@lang('home.invoice_notes')</label>
                                                                        <textarea class="form-control"
                                                                                  name="invoice_notes">{{$invoice->invoice_notes}}</textarea>
                                                                    </div>

                                                                </div>

                                                                <div class="row">
                                                                    <div hidden class="col-md-3">
                                                                        <label for="recipient-name"
                                                                               class="col-form-label"> @lang('invoice.customer_name') </label>
                                                                        <input type="text"
                                                                               class="form-control is-invalid"
                                                                               name="customer_name"
                                                                               id="customer_name"
                                                                               value="{{$invoice->customer_name ? $invoice->customer_name : ''}}"
                                                                               placeholder="@lang('invoice.customer_name')"
                                                                        >

                                                                    </div>
                                                                    <div hidden class="col-md-3">
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
                                                                    <div hidden class="col-md-3">
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
                                                                    <div hidden class="col-md-3">
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
                                                                    <div hidden class="col-md-3">
                                                                        <label for="recipient-name"
                                                                               class="col-form-label"> @lang('invoice.po_number') </label>
                                                                        <input type="text" class="form-control "
                                                                               name="po_number"
                                                                               value="{{$invoice->po_number ? $invoice->po_number : ''}}"
                                                                               id="po_number"
                                                                               placeholder="@lang('invoice.po_number')">

                                                                    </div>
                                                                    <div hidden class="col-md-3">
                                                                        <label for="recipient-name"
                                                                               class="col-form-label"> @lang('invoice.payment_tems') </label>
                                                                        <input type="text" class="form-control "
                                                                               name="payment_tems"
                                                                               value="{{$invoice->payment_tems ? $invoice->payment_tems : ''}}"
                                                                               id="payment_tems"
                                                                               placeholder="@lang('invoice.payment_tems')">

                                                                    </div>
                                                                    <div hidden class="col-md-3">
                                                                        <label for="recipient-name"
                                                                               class="col-form-label"> @lang('invoice.gr_number') </label>
                                                                        <input type="text" class="form-control "
                                                                               name="gr_number"
                                                                               id="gr_number"
                                                                               value="{{$invoice->gr_number ? $invoice->gr_number : ''}}"
                                                                               placeholder="@lang('invoice.gr_number')">

                                                                    </div>


                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row">

                                                        <div class="col-md-3">
                                                            <label class="form-label">@lang('home.invoice_status')</label>
                                                            <input type="text" disabled="" value="{{app()->getLocale() == 'ar' ?
                                                            \App\Models\SystemCode::where('system_code',$invoice->invoice_status)->first() ->system_code_name_ar :
                                                            \App\Models\SystemCode::where('system_code',$invoice->invoice_status)->first()->system_code_name_en}}"
                                                                   class="form-control">
                                                        </div>


                                                        @if($invoice->invoice_status == 121001 || $invoice->invoice_status == 121002)
                                                            <div class="col-md-3">
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

                                                            <div class="col-md-1">
                                                                <label class="form-label">@lang('home.send_email')</label>
                                                                <input type="checkbox" name="send_email">
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="recipient-name"
                                                                       class="form-label"> @lang('customer.email_work') </label>
                                                                <input type="text" class="form-control"
                                                                       name="customer_id"
                                                                       id="customer_id"
                                                                       disabled=""
                                                                       value="{{app()->getLocale() == 'ar' ?
                                                                 $invoice->customer->customer_email : $invoice->customer->customer_email }}">

                                                            </div>

                                                        @endif

                                                        <div class="col-md-2">
                                                            <label>{{__('Discount Value')}}</label>
                                                            <input type="number" class="form-control"
                                                                   name="discount_value"
                                                                   @if($invoice->invoice_discount_total > 0 || $invoice->invoice_status == 121003) readonly
                                                                   value="{{$invoice->invoice_discount_total}}"
                                                                   @else  value="0" @endif step=".001"
                                                                   id="discount_value" placeholder="قيمه الخصم">
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
                                                                    <th style="width:160px"
                                                                        class="text-center">@lang('home.ref_no')</th>
                                                                    <th style="width:150px"
                                                                        class="text-center">@lang('home.waybill_item')</th>

                                                                    <th style="width:150px"
                                                                        class="text-center">@lang('home.waybill_car_chase')</th>
                                                                    <th style="width:150px"
                                                                        class="text-center">@lang('home.waybill_car_plate')</th>
                                                                    <th style="width:200px"
                                                                        class="text-center">@lang('home.waybill_car_desc')</th>
                                                                    <th style="width:140px"
                                                                        class="text-center">@lang('home.waybill_item_amount')</th>
                                                                    <th style="width:140px"
                                                                        class="text-center">@lang('home.waybill_vat_amount')</th>

                                                                    <th style="width:140px"
                                                                        class="text-center">@lang('invoice.total')</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($invoice->waybillCars as $waybill_car)
                                                                    <tr class="clone">

                                                                        <td>
                                                                            <input type="checkbox"
                                                                                   @if($invoice->invoice_status == 121003 || $invoice->invoice_status == 121004
                                                                                   || $invoice->invoice_discount_total > 0)
                                                                                   disabled @endif
                                                                                   value="{{ $waybill_car->waybill_id}}"
                                                                                   name="waybill_id[]" checked
                                                                                   onclick="calculateTotal('{{$waybill_car->waybill_id}}')"
                                                                                   class="checkboxSelection">
                                                                        </td>

                                                                        <td>
                                                                            <a href="{{ route('Waybillcargo2.edit',$waybill_car->waybill_id) }}"
                                                                               class="btn btn-link btn-sm"
                                                                               target="_blank">
                                                                                {{ $waybill_car->waybill_code }}
                                                                            </a>
                                                                        </td>
                                                                        <td>{{ $waybill_car->waybill_ticket_no }}</td>
                                                                        <td>
                                                                            {{ app()->getLocale()=='ar' ? $waybill_car->detailsCar->item->system_code_name_ar :
                                                                             $waybill_car->detailsCar->item->system_code_name_en}}
                                                                        </td>

                                                                        <td> {{ $waybill_car->detailsCar->waybill_car_chase }}</td>
                                                                        <td> {{ $waybill_car->detailsCar->waybill_car_plate }}</td>
                                                                        <td> {{ $waybill_car->detailsCar->waybill_car_desc }}</td>
                                                                        {{--<td><input type="number" class="form-control"--}}
                                                                        {{--id="waybill_item_amount{{$waybill_car->waybill_id}}"--}}
                                                                        {{--name="waybill_item_amount[]" readonly--}}
                                                                        {{--value="{{($waybill_car->detailsCar->waybill_item_amount *--}}
                                                                        {{--$waybill_car->detailsCar->waybill_item_quantity) + $waybill_car->detailsCar->waybill_add_amount - $waybill_car->detailsCar->waybill_discount_total}}">--}}
                                                                        {{--</td>     --}}
                                                                        <td><input type="text" class="form-control"
                                                                                   id="waybill_item_amount{{$waybill_car->waybill_id}}"
                                                                                   name="waybill_item_amount[]" readonly
                                                                                   value="{{$waybill_car->waybill_total_amount - $waybill_car->waybill_vat_amount}}">
                                                                        </td>
                                                                        <td><input type="text" class="form-control"
                                                                                   name="waybill_vat_amount[]" readonly
                                                                                   id="waybill_vat_amount{{$waybill_car->waybill_id}}"
                                                                                   value="{{ $waybill_car->waybill_vat_amount}}">
                                                                        </td>

                                                                        <td><input type="text" class="form-control"
                                                                                   name="waybill_total_amount[]"
                                                                                   id="waybill_total_amount{{$waybill_car->waybill_id}}"
                                                                                   value="{{ $waybill_car->waybill_total_amount}}"
                                                                                   readonly></td>

                                                                    </tr>
                                                                @endforeach

                                                                @if($invoice->invoice_discount_total == 0)
                                                                    @if($invoice->invoice_status == 121001 || $invoice->invoice_status == 121002)
                                                                        @foreach($waybills as $waybill)
                                                                            <tr class="clone">

                                                                                <td>
                                                                                    <input type="checkbox"
                                                                                           value="{{ $waybill->waybill_id}}"
                                                                                           name="waybill_id[]"
                                                                                           id="waybill_id{{$waybill->waybill_id}}"
                                                                                           onclick="calculateTotal('{{$waybill->waybill_id}}')"
                                                                                           class="checkboxSelection">
                                                                                </td>

                                                                                <td>
                                                                                    <a href="{{ route('Waybillcargo2.edit',$waybill->waybill_id) }}"
                                                                                       class="btn btn-link btn-sm"
                                                                                       target="_blank">
                                                                                        {{ $waybill->waybill_code }}

                                                                                    </a>


                                                                                </td>
                                                                                <td> {{ $waybill->waybill_ticket_no }} </td>
                                                                                <td>
                                                                                    {{ app()->getLocale()=='ar' ? $waybill->detailsCar->item->system_code_name_ar :
                                                                                     $waybill->detailsCar->item->system_code_name_en}}
                                                                                </td>

                                                                                <td> {{ $waybill->detailsCar->waybill_car_chase }}</td>
                                                                                <td> {{ $waybill->detailsCar->waybill_car_plate }}</td>
                                                                                <td> {{ $waybill->detailsCar->waybill_car_desc }}</td>
                                                                                {{--<td>--}}
                                                                                {{--<input type="number"--}}
                                                                                {{--class="form-control"--}}
                                                                                {{--name="waybill_item_amount[]"--}}
                                                                                {{--id="waybill_item_amount{{$waybill->waybill_id}}"--}}
                                                                                {{--readonly--}}
                                                                                {{--value="{{ ($waybill->detailsCar->waybill_item_amount *--}}
                                                                                {{--$waybill->detailsCar->waybill_item_quantity) + $waybill->detailsCar->waybill_add_amount - $waybill->detailsCar->waybill_discount_total}}">--}}
                                                                                {{--</td>   --}}
                                                                                <td>
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="waybill_item_amount[]"
                                                                                           id="waybill_item_amount{{$waybill->waybill_id}}"
                                                                                           readonly
                                                                                           value="{{ $waybill->waybill_total_amount - $waybill->waybill_vat_amount }}">
                                                                                </td>
                                                                                <td><input type="text"
                                                                                           class="form-control"
                                                                                           name="waybill_vat_amount[]"
                                                                                           readonly
                                                                                           id="waybill_vat_amount{{$waybill->waybill_id}}"
                                                                                           value="{{ $waybill->waybill_vat_amount}}">
                                                                                </td>

                                                                                <td><input type="text"
                                                                                           class="form-control"
                                                                                           name="waybill_total_amount[]"
                                                                                           id="waybill_total_amount{{$waybill->waybill_id}}"
                                                                                           value="{{ $waybill->waybill_total_amount}}"
                                                                                           readonly></td>

                                                                            </tr>
                                                                @endforeach

                                                                @endif
                                                                @endif

                                                                <tfoot>

                                                                <tr style="background-color: red">

                                                                    <td colspan="2">{{ __('total with out vat') }}</td>
                                                                    <td colspan="3">{{ __('total with discount') }}</td>

                                                                    <td colspan="2">{{__('total vat')}}</td>

                                                                    <td colspan="3">{{__('total net')}}</td>

                                                                </tr>


                                                                <tr>
                                                                    @if($invoice->invoice_discount_total == 0)
                                                                        <td colspan="2"><input type="text"
                                                                                               class="form-control"
                                                                                               readonly
                                                                                               id="total_without_vat"
                                                                                               value="{{$invoice->invoice_amount - $invoice->invoice_vat_amount}}">
                                                                        </td>

                                                                        <td colspan="3"><input type="text"
                                                                                               class="form-control"
                                                                                               readonly
                                                                                               value="{{$invoice->invoice_amount - $invoice->invoice_vat_amount }}"
                                                                                               id="total_with_discount">
                                                                        </td>
                                                                    @else

                                                                        <td colspan="2"><input type="text"
                                                                                               class="form-control"
                                                                                               readonly
                                                                                               id="total_without_vat"
                                                                                               value="{{$invoice->invoice_amount - $invoice->invoice_vat_amount + $invoice->invoice_discount_total}}">
                                                                        </td>

                                                                        <td colspan="3"><input type="text"
                                                                                               class="form-control"
                                                                                               readonly
                                                                                               value="{{$invoice->invoice_amount - $invoice->invoice_vat_amount }}"
                                                                                               id="total_with_discount">
                                                                        </td>
                                                                    @endif

                                                                    <td colspan="2"><input type="text"
                                                                                           class="form-control" readonly
                                                                                           id="total_vat"
                                                                                           name="total_vat"
                                                                                           value="{{$invoice->invoice_vat_amount}}">
                                                                    </td>

                                                                    <td colspan="3">
                                                                        <input type="text"
                                                                               class="form-control" readonly
                                                                               id="total_net" name="total_net"
                                                                               value="{{$invoice->invoice_amount}}">
                                                                    </td>

                                                                </tr>
                                                                </tfoot>

                                                            </table>
                                                        </div>
                                                    </div>


                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <button class="btn btn-primary mt-2" type="submit">
                                                                @lang('home.save')</button>
                                                        </div>
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

            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script>
        $(function () {
            $("#issue_date_hijri").hijriDatePicker();
            $("#expire_date_hijri").hijriDatePicker();
        });

        function calculateTotal(id) {
            console.log(id)
            var a = $('#total_without_vat').val()
            var b = $('#total_vat').val()
            var c = $('#total_net').val()

            if ($('#waybill_id' + id).is(":checked")) {
                $('#total_without_vat').val(parseFloat(a) + parseFloat($('#waybill_item_amount' + id).val()))

                $('#total_vat').val(parseFloat(b) + parseFloat($('#waybill_vat_amount' + id).val()))

                $('#total_net').val((parseFloat(c) + parseFloat($('#waybill_total_amount' + id).val())).toFixed(2))

            } else {
                $('#total_without_vat').val(parseFloat(a) - parseFloat($('#waybill_item_amount' + id).val()))
                $('#total_vat').val(parseFloat(b) - parseFloat($('#waybill_vat_amount' + id).val()))

                $('#total_net').val((parseFloat(c) - parseFloat($('#waybill_total_amount' + id).val())).toFixed(2))
            }

            $('#total_with_discount').val(parseFloat($('#total_without_vat').val()) - parseFloat($('#discount_value').val()))
        }


        $(document).ready(function () {
            $('#discount_value').keyup(function () {
                $('#total_with_discount').val(parseFloat($('#total_without_vat').val()) - parseFloat($('#discount_value').val()))
                $('#total_vat').val(parseFloat($('#total_with_discount').val()) * parseFloat($('#customer_vat_rate').val()))
                $('#total_net').val((parseFloat($('#total_with_discount').val()) + parseFloat($('#total_vat').val())).toFixed(2))
            })


//             $("input[name='waybill_id[]']").click(function () {
//                 //////////total without vat
//                 alert('f')
//                 var amount_without_vat = $("input[name='waybill_item_amount[]']")
//                     .map(function () {
//                         return $(this).val();
//                     }).get();
//
//                 var sum_without_vat = 0;
//                 $.each(amount_without_vat, function () {
//                     sum_without_vat += parseFloat(this) || 0;
//                 });
//                 $('#total_without_vat').val(sum_without_vat)
//                 console.log(sum_without_vat)
// /////////////////////
//
//                 //////////////////total with vat
//
//                 var total_vat = $("input[name='waybill_vat_amount[]']")
//                     .map(function () {
//                         return $(this).val();
//                     }).get();
//
//                 var sum_vat = 0;
//                 $.each(total_vat, function () {
//                     sum_vat += parseFloat(this) || 0;
//                 });
//                 $('#total_vat').val(sum_vat)
//
//                 //////////////////////
//
//
//                 //////////net value
//                 var amount_net = $("input[name='waybill_total_amount[]']")
//                     .map(function () {
//                         return $(this).val();
//                     }).get();
//
//                 var sum_net = 0;
//                 $.each(amount_net, function () {
//                     sum_net += parseFloat(this) || 0;
//                 });
//                 $('#total_net').val(sum_net)
//                 ///////////
//
//             })


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
                date: ''
            },
            mounted() {
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
                }

            }
        });
    </script>

@endsection
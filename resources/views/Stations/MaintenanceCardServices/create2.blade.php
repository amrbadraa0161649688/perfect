@extends('Layouts.master')

@section('style')
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
                </div>
            </div>
        </div>


        <div class="container-fluid">
            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="data-grid" role="tabpanel">
                    {{--main data--}}
                    <form action="{{route('maintenanceCardServices.update',$maintenance_hd->mntns_cards_id)}}" method="post">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            <div class="row card">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label "> المحطه</label>
                                                <input type="text" class="form-control" readonly value="{{app()->getLocale()=='ar' ?
                                                $maintenance_hd->branch->branch_name_ar : $maintenance_hd->branch->branch_name_en}}">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label "> التاريخ</label>
                                                <input type="text" class="form-control" readonly
                                                       value="{{$maintenance_hd->created_date}}">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label "> نوع كرت
                                                    الصيانة </label>
                                                <input type="text" class="form-select form-control" readonly
                                                       value="{{ app()->getLocale() == 'ar' ?
                                                   $maintenance_hd->cardType->system_code_name_ar : $maintenance_hd->cardType->system_code_name_en}}">
                                            </div>


                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label"> نوع العمل </label>

                                                <input type="text" class="form-select form-control" readonly
                                                       value="{{ app()->getLocale() == 'ar' ?
                                                   $maintenance_hd->details->first()->itemMaintenanceType->mntns_type_name_ar :
                                                   $maintenance_hd->details->first()->itemMaintenanceType->mntns_type_name_en}}">
                                            </div>


                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label"> الحاله </label>
                                                {{--<input type="text" readonly class="form-control" name="mntns_cards_status"--}}
                                                {{--value="{{app()->getLocale() == 'ar' ? $maintenance_hd->status->system_code_name_ar :--}}
                                                {{--$maintenance_hd->status->system_code_name_en}}">--}}

                                                <select class="form-control" name="mntns_cards_status">
                                                    @foreach($statuses as $status)
                                                        <option value="{{$status->system_code_id}}"
                                                                @if($maintenance_hd->status->system_code_id == $status->system_code_id)
                                                                selected @endif>
                                                            {{app()->getLocale() == 'ar' ? $status->system_code_name_ar :
                                                            $status->system_code_name_en}}
                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label"> المضخه </label>
                                                <input type="text" readonly class="form-control" name="mntns_cars_id"
                                                       value="{{app()->getLocale() == 'ar' ? $maintenance_hd->asset->asset_name_ar :
                                                   $maintenance_hd->asset->asset_name_en}}">

                                            </div>

                                            <div class="col-md-6">
                                                <label for="recipient-name" class="col-form-label"> الملاحظات </label>
                                                <textarea class="form-control" name="mntns_cards_notes"
                                                          required>{{$maintenance_hd->mntns_cards_notes}}</textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <button type="submit" class="btn btn-primary">@lang('home.edit')</button>
                            </div>
                        </div>
                    </form>

                    {{--صيانه داخليه--}}
                    <table class="table table-bordered card_table" id="internal_maintenance_table">
                        <tbody>
                        <tr>
                            <th colspan="10" style="text-align: center;background-color: #113f50;color: white;">
                                @lang('maintenanceType.mntns_internal')
                            </th>
                        </tr>

                        <tr>
                            <th colspan="10">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <form action="{{route('maintenanceCardServices.storeDetails')}}" method="post"
                                              id="internal">
                                            @csrf
                                            <div class="row">
                                                <input type="hidden" name="mntns_cards_id"
                                                       value="{{$maintenance_hd->mntns_cards_id}}">
                                                <input type="hidden" name="mntns_cards_item_type"
                                                       value="535">
                                                <div class="col-md-2">
                                                    <label for="recipient-name"
                                                           class="col-form-label">   @lang('maintenanceType.mntns_type')  </label>
                                                    <select class="selectpicker show-tick form-control"
                                                            data-live-search="true" name="mntns_cards_item_id"
                                                            id="mntns_cards_item_id" v-model="mntns_cards_item_id"
                                                            @change="getMaintenanceTypeDts()">
                                                        <option value="" selected> choose</option>
                                                        @foreach($maintenance_types as $mt)
                                                            <option value="{{$mt->mntns_type_id}}">
                                                                {{$mt->typeCat->getSysCodeName()}}
                                                                - {{$mt->getMaintenanceTypeName()}} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="recipient-name"
                                                           class="col-form-label">  @lang('maintenanceType.value')  </label>
                                                    <input type="text" class="form-control" name="mntns_type_value"
                                                           id="mntns_type_value" v-model="mntns_type_value"
                                                           readonly>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('maintenanceType.disc_type')  </label>
                                                    <select class="form-select form-control"
                                                            name="mntns_cards_item_disc_type"
                                                            v-model="mntns_cards_item_disc_type"
                                                            id="mntns_cards_item_disc_type">
                                                        @foreach($mntns_cards_item_disc_type as $mctdt)
                                                            <option value="{{ $mctdt->system_code }}"
                                                                    @if($mctdt->system_code = 535) selected @endif> {{ $mctdt->getSysCodeName() }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('maintenanceType.disc')   </label>
                                                    <input type="number" class="form-control"
                                                           name="mntns_cards_item_disc_amount"
                                                           v-model="mntns_cards_item_disc_amount"
                                                           id="mntns_cards_item_disc_amount">

                                                    <input type="hidden" class="form-control"
                                                           name="mntns_cards_item_disc_value"
                                                           v-model="mntns_cards_item_disc_value"
                                                           id="mntns_cards_item_disc_value" value="0">
                                                </div>

                                                <div class="col-md-4 row">
                                                    <div class="col-md-5">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('maintenanceType.vat')  </label>
                                                        <input type="number" class="form-control" name="vat_value"
                                                               v-model="vat_value"
                                                               id="vat_value" readonly>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('maintenanceType.total')  </label>
                                                        <input type="number" class="form-control" name="total_after_vat"
                                                               id="total_after_vat" v-model="total_after_vat" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="recipient-name"
                                                               class="col-form-label">  @lang('maintenanceType.mntns_b_add')  </label>

                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fe fe-plus mr-2"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> عدد الساعات </label>
                                                    <input type="text"
                                                           v-model="mntns_cards_item_hours" readonly
                                                           class="form-control" name="mntns_cards_item_hours">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </th>
                        </tr>

                        <tr>
                            <th class="ctd table-active">No</th>
                            <th class="ctd table-active" style="width:25%"> @lang('maintenanceType.mntns_type') </th>
                            <th class="ctd table-active"> @lang('maintenanceType.mntns_hours')</th>
                            <th class="ctd table-active"> @lang('maintenanceType.value') </th>
                            <th class="ctd table-active"> @lang('maintenanceType.disc_type') </th>
                            <th class="ctd table-active"> @lang('maintenanceType.disc')  </th>
                            <th class="ctd table-active">@lang('maintenanceType.vat')</th>
                            <th class="ctd table-active">@lang('maintenanceType.total') </th>
                            <th class="ctd table-active">Action</th>

                        </tr>
                        @foreach($maintenance_hd->internalDetails as $internalDetail)
                            <tr>
                                <td class="ctd table-active">No</td>
                                <td class="ctd table-active"
                                    style="width:25%"> {{$internalDetail->itemMaintenanceType->mntns_type_name_ar}} </td>
                                <td class="ctd table-active"> {{$internalDetail->mntns_cards_item_hours}}</td>
                                <td class="ctd table-active"> {{$internalDetail->mntns_cards_item_amount}}</td>
                                <td class="ctd table-active"> {{$internalDetail->discType ? $internalDetail->discType->system_code_name_ar : 'لا يوجد خصم'}}</td>
                                <td class="ctd table-active"> {{$internalDetail->mntns_cards_disc_amount}}</td>
                                <td class="ctd table-active"> {{$internalDetail->mntns_cards_vat_amount}}</td>
                                <td class="ctd table-active"> {{$internalDetail->mntns_cards_amount}}</td>
                                <td class="ctd table-active">
                                    <a href="{{route('maintenanceCardServices.deleteItem',$internalDetail->mntns_cards_dt_id)}}"
                                       class="btn btn-danger"><i
                                                class="fa fa-trash"></i></a>
                                </td>

                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2" class="ctd table-active"> @lang('maintenanceType.disc')</td>
                            <td colspan="1" class="ctd table-active">
                                <div id="internal_total_disc_amount_div">{{$maintenance_hd->internalSumDisc()}}</div>
                            </td>
                            <td colspan="2" class="ctd table-active">@lang('maintenanceType.vat')</td>
                            <td colspan="1" class="ctd table-active">
                                <div id="internal_total_vat_amount_div">{{$maintenance_hd->internalSumVat()}}</div>
                            </td>
                            <td colspan="2" class="ctd table-active">@lang('maintenanceType.total')</td>
                            <td colspan="1" class="ctd table-active">
                                <div id="internal_total_amount_div">{{$maintenance_hd->internalSumTotal()}}</div>
                            </td>
                        </tr>
                        <tr>

                        </tr>
                        <tr>
                        </tr>
                        </tfoot>
                    </table>
                    {{--///////////////////////////////////////////////////////////////--}}

                    {{--صيانه خارجيه--}}
                    <table class="table table-bordered card_table" id="external_maintenance_table">
                        <tbody>
                        <tr colspan="11">
                            <th colspan="11" style="text-align: center;background-color: #113f50;color: white;">
                                @lang('maintenanceType.mntns_external')
                            </th>
                        <tr>

                        <tr>
                            <th colspan="11">
                                <div class="col-md-12">
                                    <div class="mb-3" id="external_input">
                                        <form action="{{route('maintenanceCardServices.storeDetails')}}" method="post"
                                              id="external">
                                            @csrf
                                            <input type="hidden" name="mntns_cards_item_type"
                                                   value="536">
                                            <input type="hidden" name="mntns_cards_id"
                                                   value="{{$maintenance_hd->mntns_cards_id}}">
                                            <div class="row">
                                                <div class="col-md-12 row">

                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-form-label">@lang('home.accounts')</label>
                                                            <select class="form-control"
                                                                    name="account_id"
                                                                    id="account_id"
                                                                    v-model="account_id"
                                                                    required>
                                                                <option value="">@lang('home.choose')</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->acc_id }}">
                                                                        @if(app()->getLocale()=='ar')
                                                                            {{$account->acc_name_ar}}
                                                                        @else
                                                                            {{$account->acc_name_en}}
                                                                        @endif
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6 col-md-3">
                                                        <label for="recipient-name"
                                                               class="col-form-label"
                                                               style="text-decoration: underline;"> {{__('Supplier Name')}}</label>
                                                        <div class="form-group multiselect_div">
                                                            <div class="form-group multiselect_div">
                                                                <select class="selectpicker" data-live-search="true"
                                                                        name="supplier_id" id="supplier_id"
                                                                        @change="getSupplierType()"
                                                                        v-model="supplier_id">
                                                                    <option value=""
                                                                            selected>@lang('home.choose')</option>
                                                                    @foreach($suppliers as $supplier)
                                                                        <option value="{{$supplier->customer_id }}">
                                                                            @if(app()->getLocale() == 'ar')
                                                                                {{ $supplier->customer_name_full_ar }}
                                                                            @else
                                                                                {{ $supplier->customer_name_full_en }}
                                                                            @endif
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-3">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('maintenanceType.mntns_supp_inv')   </label>
                                                        <input type="number" class="form-control"
                                                               name="invoice_no_external"
                                                               id="invoice_no_external">
                                                    </div>

                                                    <div class="col-sm-6 col-md-3">
                                                        <label for="recipient-name"
                                                               class="col-form-label">  @lang('maintenanceType.mntns_supp_inv_date')   </label>
                                                        <input type="date" class="form-control"
                                                               name="invoice_date_external"
                                                               id="invoice_date_external">
                                                    </div>

                                                    <div class="col-sm-6 col-md-3">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('invoice.customer_tax_no') </label>
                                                        <input type="text" class="form-control"
                                                               name="customer_tax_no"
                                                               id="customer_tax_no" :value="customer_tax_no"
                                                               placeholder="@lang('invoice.customer_tax_no')" required>

                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('invoice.customer_name') </label>
                                                        <input type="text" class="form-control"
                                                               name="customer_name"
                                                               id="customer_name" :value="customer_name"
                                                               placeholder="@lang('invoice.customer_name')" required>

                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('invoice.customer_address') </label>
                                                        <input type="text" class="form-control"
                                                               name="customer_address"
                                                               id="customer_address" :value="customer_address"
                                                               placeholder="@lang('invoice.customer_address')" required>

                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('invoice.customer_phone') </label>
                                                        <input type="text" class="form-control"
                                                               name="customer_phone"
                                                               id="customer_phone" :value="customer_phone"
                                                               placeholder="@lang('invoice.customer_phone')" required>
                                                    </div>
                                                    <div class="col-md-3 d-none">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('invoice.customer_vat_rate') </label>
                                                        <input type="text" class="form-control"
                                                               name="external_vat_value"
                                                               id="customer_vat_rate" :value="customer_vat_rate"
                                                               placeholder="@lang('invoice.customer_vat_rate')"
                                                               required>
                                                    </div>

                                                    <div class="col-sm-6 col-md-3 d-none">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('maintenanceType.mntns_hours')  </label>
                                                        <input type="number" class="form-control"
                                                               name="mntns_cards_item_hours_external"
                                                               id="mntns_cards_item_hours_external" value="1">
                                                    </div>
                                                    <div class="col-sm-6 col-md-3">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('maintenanceType.value')  </label>
                                                        <input type="number" class="form-control"
                                                               name="mntns_type_value_external"
                                                               v-model="mntns_type_value_external"
                                                               id="mntns_type_value_external">
                                                    </div>
                                                    <div class="col-sm-6 col-md-3">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('maintenanceType.vat')  </label>
                                                        <input type="number" class="form-control"
                                                               name="external_vat_amount" v-model="customer_vat_rate"
                                                               id="vat_value_external" readonly>
                                                    </div>
                                                    <div class="col-sm-6 col-md-3">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('maintenanceType.total')  </label>
                                                        <input type="number" class="form-control"
                                                               name="total_after_vat_external"
                                                               v-model="total_after_vat_external"
                                                               id="total_after_vat_external" readonly>
                                                    </div>
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-form-label">@lang('home.payment_method')</label>
                                                            <select class="form-control" id="payment_tems"
                                                                    name="payment_tems" required>
                                                                <ooption value="">@lang('home.choose')</ooption>
                                                                @foreach($payment_methods as $payment_method)
                                                                    <option value="{{ $payment_method->system_code }}">{{ app()->getLocale()=='ar' ?
                                                 $payment_method->system_code_name_ar : $payment_method->system_code_name_en }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-10">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('maintenanceType.mntns_notes') </label>
                                                        <textarea class="form-control" name="mntns_cards_item_notes"
                                                                  id="mntns_cards_item_notes"
                                                                  maxlength="500"></textarea>
                                                        {{--                                    <input type="text" class="form-control" name="mntns_cards_item_notes" id="mntns_cards_item_notes" value="" >--}}
                                                    </div>
                                                    <div class="col-sm-6 col-md-2">
                                                        <label for="recipient-name"
                                                               class="col-form-label">  @lang('maintenanceType.mntns_b_add')  </label>
                                                        <button type="submit"
                                                                class="btn btn-primary w-100">
                                                            <i class="fe fe-plus mr-2"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </th>
                        </tr>

                        <tr>
                            <th>No</th>
                            <th>  {{__('Supplier Name')}}</th>
                            <th> @lang('maintenanceType.mntns_notes')</th>
                            <th> @lang('maintenanceType.value')</th>
                            <th>@lang('maintenanceType.vat')</th>
                            <th>@lang('maintenanceType.total')</th>
                            <th>  @lang('maintenanceType.mntns_supp_inv') </th>
                            <th> @lang('maintenanceType.mntns_supp_inv_date') </th>

                            <th>Action</th>
                        </tr>


                        @foreach($maintenance_hd->externalDetails as $key => $external)

                            <tr id="{--><!--{ $external->uuid }}">
                                <td class="ctd"> {{ $key + 1 }} </td>
                                <td class="ctd"> {{ $external->workshop?$external->workshop->name:''}}</td>
                                <td class="ctd">{{ $external->mntns_cards_item_notes }} </td>
                                <td class="ctd"> {{ $external->mntns_cards_item_price }} </td>
                                <td class="ctd"> {{ $external->mntns_cards_vat_amount }}  </td>
                                <td class="ctd"> {{ $external->mntns_cards_amount }} </td>
                                <td class="ctd"> {{ $external->invoice_no_external }}</td>
                                <td class="ctd">{{ $external->invoice_date_external }} </td>

                                <td class="ctd">
                                    <a href="{{route('maintenanceCardServices.deleteItem',$external->mntns_cards_dt_id)}}"
                                       class="btn btn-danger"><i
                                                class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4" class="ctd table-active">@lang('maintenanceType.vat')</td>
                            <td colspan="2" class="ctd table-active">
                                <div id="external_total_vat_amount_div">{{$maintenance_hd->externalSumVat()}}</div>
                            </td>
                            <td colspan="4" class="ctd table-active">@lang('maintenanceType.total')</td>
                            <td colspan="2" class="ctd table-active">
                                <div id="external_total_amount_div">{{$maintenance_hd->externalSumTotal()}}</div>
                            </td>
                        </tr>

                        </tfoot>
                    </table>
                    {{--/////////////////////////////////////////////////////////////////--}}

                    {{--قطع غيار--}}
                    <table class="table table-bordered card_table" id="part_maintenance_table">
                        <tbody>
                        <tr>
                            <th colspan="11" style="text-align: center;background-color: #113f50;color: white;">
                                @lang('maintenanceType.mntns_part')
                            </th>
                        <tr>

                        <tr>
                            <th colspan="11">
                                <form action="{{route('maintenanceCardServices.storeDetails')}}" method="post"
                                      id="part">
                                    @csrf
                                    <div class="row">
                                        <input type="hidden" name="mntns_cards_id"
                                               value="{{$maintenance_hd->mntns_cards_id}}">
                                        <input type="hidden" name="mntns_cards_item_type"
                                               value="537">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <div class="row">
                                                    <div class="col-md-5 row">

                                                        <div class="col-md-4">
                                                            <label for="recipient-name"
                                                                   class="col-form-label">  @lang('maintenanceType.item_name')  </label>
                                                            <select class="selectpicker show-tick form-control"
                                                                    data-live-search="true"
                                                                    v-model="part_mntns_cards_item_id"
                                                                    name="part_mntns_cards_item_id"
                                                                    id="part_mntns_cards_item_id"
                                                                    @change="getStoreItem()">
                                                                <option value="" selected> choose</option>
                                                                @foreach(App\Models\StoreItem::where('company_id','=',auth()->user()->company->company_id)->where('branch_id',session('branch')['branch_id'])
                                                                    ->get() as $it)
                                                                    <option value="{{$it->item_id}}"
                                                                            data-unit-price="{{$it->item_price_cost}}"
                                                                            data-balance="{{$it->item_balance}}"
                                                                            data-itemname="{{$it->item_name_a}}"
                                                                            data-storename="{{$it->itemCategory->system_code_name_ar}}"
                                                                            data-storeid="{{$it->itemCategory->system_code_id}}">
                                                                        - {{ $it->item_code }} - {{$it->item_desc}}
                                                                        - {{ $it->item_name_a }} - المتوفر
                                                                        عدد {{ $it->item_balance }}  </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label for="recipient-name"
                                                                   class="col-form-label"> @lang('maintenanceType.unit_price')  </label>
                                                            <input type="number" class="form-control"
                                                                   name="part_unit_price"
                                                                   id="part_unit_price" v-model="item_price_mntns"
                                                                   readonly>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label for="recipient-name"
                                                                   class="col-form-label">  @lang('maintenanceType.qty')  </label>
                                                            <input type="number" class="form-control" name="part_qty"
                                                                   id="part_qty" v-model="part_qty">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 row">
                                                        <div class="col-md-4">
                                                            <label for="recipient-name"
                                                                   class="col-form-label"> @lang('maintenanceType.value')  </label>
                                                            <input type="text" class="form-control"
                                                                   name="part_mntns_type_value"
                                                                   id="part_mntns_type_value" value="" readonly
                                                                   v-model="part_mntns_type_value">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="recipient-name"
                                                                   class="col-form-label">  @lang('maintenanceType.disc_type')  </label>
                                                            <select class="form-select form-control"
                                                                    name="part_mntns_cards_item_disc_type"
                                                                    id="part_mntns_cards_item_disc_type"
                                                                    data-live-search="true">
                                                                @foreach($mntns_cards_item_disc_type as $mctdt)
                                                                    <option value="{{ $mctdt->system_code_id }}"
                                                                            data-mntnsdisctype="{{ $mctdt->getSysCodeName() }}"> {{ $mctdt->getSysCodeName()}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label for="recipient-name"
                                                                   class="col-form-label">  @lang('maintenanceType.disc')  </label>
                                                            <input type="number" class="form-control"
                                                                   name="part_mntns_cards_item_disc_amount"
                                                                   v-model="part_mntns_cards_item_disc_amount"
                                                                   id="part_mntns_cards_item_disc_amount" value="0">

                                                            <input type="hidden" class="form-control"
                                                                   name="part_mntns_cards_item_disc_value"
                                                                   v-model="part_mntns_cards_item_disc_value"
                                                                   id="part_mntns_cards_item_disc_value" value="0">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 row">
                                                        <div class="col-md-5">
                                                            <label for="recipient-name"
                                                                   class="col-form-label"> @lang('maintenanceType.vat')  </label>
                                                            <input type="number" class="form-control"
                                                                   name="part_vat_value"
                                                                   id="part_vat_value"
                                                                   v-model="part_vat_value"
                                                                   value="" readonly>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="recipient-name"
                                                                   class="col-form-label"> @lang('maintenanceType.total')  </label>
                                                            <input type="number" class="form-control"
                                                                   name="part_total_after_vat"
                                                                   v-model="part_total"
                                                                   id="part_total_after_vat" value="" readonly>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label for="recipient-name"
                                                                   class="col-form-label"> @lang('maintenanceType.mntns_b_add')  </label>
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fe fe-plus mr-2"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </th>
                        </tr>

                        <tr>
                            <th>No</th>
                            <th>@lang('maintenanceType.mntns_store')</th>
                            <th> @lang('maintenanceType.item_name')</th>
                            <th> @lang('maintenanceType.qty')</th>
                            <th> @lang('maintenanceType.unit_price')</th>
                            <th>@lang('maintenanceType.value')</th>
                            <th>@lang('maintenanceType.vat')</th>
                            <th> @lang('maintenanceType.disc_type')  </th>
                            <th>  @lang('maintenanceType.disc')  </th>
                            <th> @lang('maintenanceType.total') </th>
                            <th>Action</th>
                        </tr>

                        @foreach($maintenance_hd->partDetails as $key => $part)

                            <tr id="{{ $part->uuid }}">
                                <td class="ctd"> {{ $key+1}} </td>
                                <td class="ctd"> {{ optional($part->partItem->itemCategory)->getSysCodeName() }} </td>
                                <td class="ctd"> {{ optional($part->partItem)->getItemName() }} </td>
                                <td class="ctd"> {{ $part->mntns_cards_item_qty }} </td>
                                <td class="ctd"> {{ $part->mntns_cards_item_price }} </td>
                                <td class="ctd"> {{ $part->mntns_cards_item_amount }} </td>
                                <td class="ctd"> {{ $part->mntns_cards_vat_amount }} </td>
                                <td class="ctd"> {{ optional($part->discType)->getSysCodeName()}}  </td>
                                <td class="ctd"> {{ $part->mntns_cards_disc_amount }} </td>
                                <td class="ctd"> {{ $part->mntns_cards_amount }} </td>
                                <td class="ctd">
                                    <a href="{{route('maintenanceCardServices.deleteItem',$part->mntns_cards_dt_id)}}"
                                       class="btn btn-danger"><i
                                                class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>

                        <tfoot>
                        <tr>
                            <td colspan="2" class="ctd table-active">@lang('maintenanceType.disc')</td>
                            <td colspan="2" class="ctd table-active">
                                <div id="part_total_disc_amount_div">{{$maintenance_hd->partSumDisc()}}</div>
                            </td>
                            <td colspan="2" class="ctd table-active">@lang('maintenanceType.vat')</td>
                            <td colspan="2" class="ctd table-active">
                                <div id="part_total_vat_amount_div">{{$maintenance_hd->partSumVat()}}</div>
                            </td>
                            <td colspan="2" class="ctd table-active"> @lang('maintenanceType.total') </td>
                            <td colspan="2" class="ctd table-active">
                                <div id="part_total_amount_div">{{$maintenance_hd->partSumTotal()}}</div>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                    {{--/////////////////////////////////////--}}

                    {{--الاجماليات--}}
                    <table class="table table-bordered card_table">
                        <tbody>
                        <tr>
                            <th colspan="12" style="text-align: center;background-color: #113f50;color: white;">
                                @lang('maintenanceType.total')
                            </th>
                        <tr>
                        <tr>
                            <th colspan="3" style="text-align: center;background-color: #113f50;color: white;">
                                @lang('maintenanceType.disc')
                            </th>
                            <th colspan="2" style="text-align: center;background-color: #113f50;color: white;">
                                @lang('maintenanceType.vat')
                            </th>
                            <th colspan="2" style="text-align: center;background-color: #113f50;color: white;">
                                @lang('maintenanceType.total')
                            </th>

                            <th colspan="1" style="text-align: center;background-color: #113f50;color: white;">
                                @lang('maintenanceType.mntns_card_paid')
                            </th>

                            <th colspan="1" style="text-align: center;background-color: #113f50;color: white;">
                                @lang('maintenanceType.mntns_card_amount')
                            </th>


                        </tr>
                        <tr>
                            <td class="ctd" colspan="3">
                                <div id="g_total_disc_amount_div"> {{ $maintenance_hd->internalSumDisc()  + $maintenance_hd->partSumDisc() }} </div>
                            </td>
                            <td class="ctd" colspan="2">
                                <div id="g_total_vat_amount_div"> {{ $maintenance_hd->internalSumVat() + $maintenance_hd->externalSumVat() + $maintenance_hd->partSumVat() +
             $maintenance_hd->card_total_vat_from_inv}} </div>
                            </td>
                            <td class="ctd" colspan="2">
                                <div id="g_total_amount_div"> {{ $maintenance_hd->internalSumTotal() + $maintenance_hd->externalSumTotal() + $maintenance_hd->partSumTotal()
             +  $maintenance_hd->card_total_val_from_inv}} </div>
                            </td>

                            <td class="ctd" colspan="1">
                                <div id="g_total_payment_div">  {{ $maintenance_hd->mntns_cards_payment_amount }} </div>
                            </td>


                            <td class="ctd" colspan="1">
                                <div id="g_total_due_div">  {{ $maintenance_hd->mntns_cards_due_amount }} </div>
                            </td>

                        </tr>
                        </tbody>
                    </table>
                    {{--/////////////////////////////////////--}}
                </div>

                {{-- files part --}}
                <div class="tab-pane fade" id="files-grid" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-12">

                            <x-files.form>
                                <input type="hidden" name="transaction_id" value="{{$maintenance_hd->mntns_cards_id }}">
                                <input type="hidden" name="app_menu_id" value="153">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('home.attachment_type')</label>
                                        <select class="form-control" name="attachment_type" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($attachment_types as $attachment_type)
                                                <option value="{{ $attachment_type->system_code_id }}">{{app()->getLocale()=='ar' ? $attachment_type->system_code_name_ar
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
                                         $attachment->attachmentType->system_code_name_ar :
                                          $attachment->attachmentType->system_code_name_en}}</td>
                                        <td>{{ date('d-m-Y', strtotime($attachment->issue_date )) }}</td>
                                        <td>{{ date('d-m-Y', strtotime($attachment->expire_date )) }}</td>
                                        <td>{{ $attachment->issue_date_hijri }}</td>
                                        <td>{{ $attachment->expire_date_hijri }}</td>
                                        <td>{{ $attachment->copy_no }}</td>
                                        <td>
                                            <a href="{{ url('/attachments/download-pdf?name=' . $attachment->attachment_file_url) }}">
                                                <i class="fa fa-download fa-2x"></i></a>
                                            <a href="{{ asset('Files/'.$attachment->attachment_file_url) }}"
                                               target="_blank" class="mr-1 ml-1"><i class="fa fa-eye text-info"
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
                                <input type="hidden" name="transaction_id" value="{{ $maintenance_hd->mntns_cards_id}}">
                                <input type="hidden" name="app_menu_id" value="153">
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
        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                mntns_cards_item_id: '',
                maintenance_type: {},
                mntns_cards_item_disc_amount: 0,
                mntns_type_value: 0,
                mntns_cards_item_disc_type: '',
                mntns_cards_item_hours: 0,
                customer_tax_no: 0,
                customer_address: '',
                customer_name: '',
                customer_phone: '',
                customer_vat_rate: 0,
                supplier_id: '',
                account_id: '',
                mntns_type_value_external: 0,
                part_mntns_cards_item_id: '',
                store_item_type_part: '',
                item_price_mntns: 0,
                part_qty: 0,
                part_mntns_cards_item_disc_amount: 0,

            },
            methods: {
                getMaintenanceTypeDts() {
                    $.ajax({
                        type: 'GET',
                        data: {mntns_cards_item_id: this.mntns_cards_item_id},
                        url: '{{ route("maintenanceCardServices.getMaintenanceTypeDts") }}'
                    }).then(response => {
                        this.maintenance_type = response.data
                        this.mntns_type_value = response.data.mntns_type_value
                        this.mntns_cards_item_hours = response.data.mntns_type_hours
                    })
                },
                getSupplierType() {
                    $.ajax({
                        type: 'GET',
                        data: {customer_id: this.supplier_id},
                        url: '{{ route("api.waybill.customer.type") }}'
                    }).then(response => {
                        this.customer_tax_no = response.customer_tax_no
                        this.customer_address = response.customer_address
                        this.customer_name = response.customer_name
                        this.customer_phone = response.customer_mobile
                        this.customer_vat_rate = response.customer_vat_rate ? response.customer_vat_rate : 15
                    })
                },
                getStoreItem() {
                    $.ajax({
                        type: 'GET',
                        data: {part_mntns_cards_item_id: this.part_mntns_cards_item_id},
                        url: '{{ route("maintenanceCardServices.getStoreItemDt") }}'
                    }).then(response => {
                        this.store_item_type_part = response.data
                        this.item_price_mntns = response.data.item_price_sales
                        this.part_qty = 1
                    })
                }
            },
            computed: {
                mntns_cards_item_disc_value: function () {
                    if (this.mntns_cards_item_disc_type == 533) {
                        return parseFloat(this.mntns_type_value) * (parseFloat(this.mntns_cards_item_disc_amount) / 100)
                    } else {
                        return parseFloat(this.mntns_cards_item_disc_amount) > 0 ? parseFloat(this.mntns_cards_item_disc_amount) : 0
                    }
                },
                vat_value: function () {
                    var vat_m = (parseFloat(this.mntns_type_value) - this.mntns_cards_item_disc_value) * .15
                    return vat_m
                },
                total_after_vat: function () {
                    var total = ((this.mntns_type_value - this.mntns_cards_item_disc_value) * .15) + parseFloat(this.mntns_type_value)
                        - this.mntns_cards_item_disc_value
                    return total
                },
                total_after_vat_external: function () {
                    var vat_amount = (parseFloat(this.customer_vat_rate) / 100) * parseFloat(this.mntns_type_value_external);
                    return vat_amount + parseFloat(this.mntns_type_value_external);
                },
                part_mntns_cards_item_disc_value: function () {
                    var t = this.part_qty * this.item_price_mntns;
                    if (this.part_mntns_cards_item_disc_type == 533) {
                        return this.t * (parseFloat(this.part_mntns_cards_item_disc_amount) / 100)
                    } else {
                        return parseFloat(this.part_mntns_cards_item_disc_amount);
                    }
                },
                part_mntns_type_value: function () {
                    return this.part_qty * this.item_price_mntns
                },
                part_vat_value: function () {
                    var t_with_discount = (this.part_qty * this.item_price_mntns) - this.part_mntns_cards_item_disc_value
                    return .15 * t_with_discount;
                },
                part_total: function () {
                    return ((this.part_qty * this.item_price_mntns) - this.part_mntns_cards_item_disc_value) * 1.15
                },


            }
        })
    </script>
@endsection
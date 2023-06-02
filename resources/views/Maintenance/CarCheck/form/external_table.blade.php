<table class="table table-bordered card_table" id="external_maintenance_table">
    <tbody>
    <tr colspan="11">
        <th colspan="11" style="text-align: center;background-color: #113f50;color: white;">
            @lang('maintenanceType.mntns_external')
        </th>
    <tr>
    @if(in_array($card->mntns_cards_status,$can_edit) || $card->mntns_cards_status == $can_close)
        <tr>
            <th colspan="11">
                <div class="col-md-12">
                    <div class="mb-3" id="external_input">
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
                                                <option value="" selected>@lang('home.choose')</option>
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
                                    <input type="number" class="form-control" name="invoice_no_external"
                                           id="invoice_no_external">
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label">  @lang('maintenanceType.mntns_supp_inv_date')   </label>
                                    <input type="date" class="form-control" name="invoice_date_external"
                                           id="invoice_date_external" value="">
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
                                           name="customer_vat_rate"
                                           id="customer_vat_rate" :value="customer_vat_rate"
                                           placeholder="@lang('invoice.customer_vat_rate')" required>
                                </div>

                                <div class="col-sm-6 col-md-3 d-none">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('maintenanceType.mntns_hours')  </label>
                                    <input type="number" class="form-control" name="mntns_cards_item_hours_external"
                                           id="mntns_cards_item_hours_external" value="1">
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('maintenanceType.value')  </label>
                                    <input type="number" class="form-control" name="mntns_type_value_external"
                                           id="mntns_type_value_external" value="">
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('maintenanceType.vat')  </label>
                                    <input type="number" class="form-control" name="vat_value_external"
                                           id="vat_value_external" value="" readonly>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('maintenanceType.total')  </label>
                                    <input type="number" class="form-control" name="total_after_vat_external"
                                           id="total_after_vat_external" value="" readonly>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label class="col-form-label">@lang('home.payment_method')</label>
                                        <select class="form-control" id="payment_tems" name="payment_tems" required>
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
                                              id="mntns_cards_item_notes" maxlength="500"></textarea>
                                    {{--                                    <input type="text" class="form-control" name="mntns_cards_item_notes" id="mntns_cards_item_notes" value="" >--}}
                                </div>
                                <div class="col-sm-6 col-md-2">
                                    <label for="recipient-name"
                                           class="col-form-label">  @lang('maintenanceType.mntns_b_add')  </label>
                                    <button onclick="saveExternalRow()" class="btn btn-primary w-100">
                                        <i class="fe fe-plus mr-2"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-4 row">


                            </div>

                            {{--                            <div class="col-md-9 row">--}}
                            {{--                            <div class="col-md-3">--}}
                            {{--                                    <label for="recipient-name" class="col-form-label">  @lang('maintenanceType.mntns_cost_inv_value') </label>--}}
                            {{--                                        <input type="number" class="form-control" name="mntns_customer_cost_external" id="mntns_customer_cost_external" value="">--}}
                            {{--                                </div>--}}
                            {{--                                <input type="hidden" class="form-control" name="external_total_vat_amount" id="external_total_vat_amount" value="" >--}}
                            {{--                                <input type="hidden" class="form-control" name="external_total_amount" id="external_total_amount" value="" >--}}

                            {{--                                <div class="col-md-3">--}}
                            {{--                                    <label for="recipient-name" class="col-form-label"> @lang('maintenanceType.mntns_supp_inv')   </label>--}}
                            {{--                                    <input type="number" class="form-control" name="invoice_no_external" id="invoice_no_external">--}}
                            {{--                                </div>--}}


                            {{--                                <!-- <div class="col-md-3">--}}
                            {{--                                    <label for="recipient-name" class="col-form-label"> الملف </label>--}}
                            {{--                                    <input type="text" class="form-control" name="invoice_file_external" id="invoice_file_external" value="">--}}
                            {{--                                </div> -->--}}

                            {{--                            </div>--}}

                        </div>
                    </div>
                </div>
            </th>
        </tr>
    @endif
    <tr>
        <th>No</th>
        <th>  {{__('Supplier Name')}}</th>
        <th> @lang('maintenanceType.mntns_notes')</th>
        {{--        <th> @lang('maintenanceType.mntns_hours')</th>--}}
        <th> @lang('maintenanceType.value')</th>
        <th>@lang('maintenanceType.vat')</th>
        <th>@lang('maintenanceType.total')</th>
        {{--            <th>@lang('maintenanceType.mntns_cost_inv_value')  </th>--}}
        <th>  @lang('maintenanceType.mntns_supp_inv') </th>
        <th> @lang('maintenanceType.mntns_supp_inv_date') </th>

        <th>Action</th>
    </tr>

    <?php
    $external_discount_total = 0;
    $external_vat_total = 0;
    $external_total = 0;
    $external_row_count = 0;
    ?>
    @foreach($card->externalDetails as $key => $external)
        <?php
        $external_discount_total = $external_discount_total + $external->mntns_cards_disc_amount;
        $external_vat_total = $external_vat_total + $external->mntns_cards_vat_amount;
        $external_total = $external_total + floatval($external->mntns_cards_amount);
        $external_row_count = floatval($external_row_count) + 1;
        //            ?>
        <tr id="{--><!--{ $external->uuid }}">
            <td class="ctd"> {{ $key + 1 }} </td>
            <td class="ctd"> {{ $external->workshop?$external->workshop->name:''}}</td>
            <td class="ctd">{{ $external->mntns_cards_item_notes }} </td>
            {{--            <td class="ctd"> {{ $external->mntns_cards_item_hours }} </td>--}}
            <td class="ctd"> {{ $external->mntns_cards_item_price }} </td>
            <td class="ctd"> {{ $external->mntns_cards_vat_amount }}  </td>
            <td class="ctd"> {{ $external->mntns_cards_amount }} </td>
            {{--                <td class="ctd"> {{ $external->mntns_customer_cost_external }}</td>--}}
            <td class="ctd"> {{ $external->invoice_no_external }}</td>
            <td class="ctd">{{ $external->invoice_date_external }} </td>

            <td class="ctd">
                @if(in_array($card->mntns_cards_status,$can_edit))
                    <button type="button" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only"
                            onclick="deleteItem('{{ $external->uuid }}')"><i class="fa fa-trash"></i></button>
                @endif
            </td>
        </tr>
    @endforeach
    <input type="hidden" class="form-control" name="external_row_count" id="external_row_count"
           value="{{ $external_row_count }}">
    <input type="hidden" class="form-control" name="external_total_disc_amount" id="external_total_disc_amount"
           value="{{ $external_discount_total }}">
    <input type="hidden" class="form-control" name="external_total_vat_amount" id="external_total_vat_amount"
           value="{{$external_vat_total}} ">
    <input type="hidden" class="form-control" name="external_total_amount" id="external_total_amount"
           value="{{ $external_total }}">

    </tbody>
    <tfoot>
    <tr>
        <td colspan="4" class="ctd table-active">@lang('maintenanceType.vat')</td>
        <td colspan="2" class="ctd table-active">
            <div id="external_total_vat_amount_div"> {{ $external_vat_total }} </div>
        </td>
        <td colspan="4" class="ctd table-active">@lang('maintenanceType.total')</td>
        <td colspan="2" class="ctd table-active">
            <div id="external_total_amount_div"> {{ $external_total }} </div>
        </td>
    </tr>

    </tfoot>
</table>











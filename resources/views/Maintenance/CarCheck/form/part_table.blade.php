<table class="table table-bordered card_table" id="part_maintenance_table">
    <tbody>
    <tr>
        <th colspan="11" style="text-align: center;background-color: #113f50;color: white;">
            @lang('maintenanceType.mntns_part')
        </th>
    <tr>
    @if(in_array($card->mntns_cards_status,$can_edit) ||$card->mntns_cards_status ==  $can_close)
        <tr>
            <th colspan="11">
                <div class="col-md-12">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-5 row">
                            <?php $store_list = App\Models\SystemCode::where('system_code_filter', '=', $card->cardType->system_code); ?>
                            <!-- <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label"> المستودع  </label>
                                    <select class="selectpicker show-tick form-control"  data-live-search="true" name="part_mntns_cards_item_id" id="part_mntns_cards_item_id">
                                        <option value="" selected> choose</option> 
                                        @foreach($store_list->get() as $s)
                                <option value="{{$s->system_code}}"> {{ $s->getSysCodeName()}}  </option>
                                        @endforeach
                                    </select>
                                </div> -->

                                <div class="col-md-4">
                                    <label for="recipient-name"
                                           class="col-form-label">  @lang('maintenanceType.item_name')  </label>
                                    <select class="selectpicker show-tick form-control" data-live-search="true"
                                            name="part_mntns_cards_item_id" id="part_mntns_cards_item_id">
                                        <option value="" selected> choose</option>
                                        @foreach(App\Models\StoreItem::where('company_id','=',auth()->user()->company->company_id)->whereIn('item_category',$store_list->pluck('system_code_id'))->get() as $it)
                                            <option value="{{$it->item_id}}" data-unit-price="{{$it->item_price_cost}}"
                                                    data-balance="{{$it->item_balance}}"
                                                    data-itemname="{{$it->item_name_a}}"
                                                    data-storename="{{$it->itemCategory->system_code_name_ar}}"
                                                    data-storeid="{{$it->itemCategory->system_code_id}}"> 
                                                - {{ $it->item_code }} - {{$it->item_desc}} - {{ $it->item_name_a }} - المتوفر
                                                عدد {{ $it->item_balance }}  </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('maintenanceType.unit_price')  </label>
                                    <input type="number" class="form-control" name="part_unit_price"
                                           id="part_unit_price" value="0" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name"
                                           class="col-form-label">  @lang('maintenanceType.qty')  </label>
                                    <input type="number" class="form-control" name="part_qty" id="part_qty" value="">
                                </div>
                            </div>
                        <!-- <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label"> المستودع  </label>
                                <select class="selectpicker show-tick form-control"  data-live-search="true" name="warehouses_type_id" id="warehouses_type_id"  >
                                    @foreach(App\Models\SystemCode::where('company_id','=',auth()->user()->company->company_id)->where('system_code_id','=',$card->mntns_cards_type)->get() as $wt)
                            <option value="{{$wt->system_code_id}}"> {{$wt->getSysCodeName()}} </option>
                                    @endforeach
                                </select>
                            </div> -->
                            <div class="col-md-4 row">
                                <div class="col-md-4">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('maintenanceType.value')  </label>
                                    <input type="text" class="form-control" name="part_mntns_type_value"
                                           id="part_mntns_type_value" value="" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="recipient-name"
                                           class="col-form-label">  @lang('maintenanceType.disc_type')  </label>
                                    <select class="form-select form-control" name="part_mntns_cards_item_disc_type"
                                            id="part_mntns_cards_item_disc_type" data-live-search="true">
                                        <!-- <option value="" selected> choose</option>  -->
                                        @foreach($mntns_cards_item_disc_type as $mctdt)
                                            <option value="{{ $mctdt->system_code_id }}"
                                                    data-mntnsdisctype="{{ $mctdt->getSysCodeName() }}"> {{ $mctdt->getSysCodeName()}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="recipient-name"
                                           class="col-form-label">  @lang('maintenanceType.disc')  </label>
                                    <input type="number" class="form-control" name="part_mntns_cards_item_disc_amount"
                                           id="part_mntns_cards_item_disc_amount" value="0">
                                    <input type="hidden" class="form-control" name="part_mntns_cards_item_disc_value"
                                           id="part_mntns_cards_item_disc_value" value="0">
                                </div>
                            </div>
                            <div class="col-md-3 row">
                                <div class="col-md-5">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('maintenanceType.vat')  </label>
                                    <input type="number" class="form-control" name="part_vat_value" id="part_vat_value"
                                           value="" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('maintenanceType.total')  </label>
                                    <input type="number" class="form-control" name="part_total_after_vat"
                                           id="part_total_after_vat" value="" readonly>
                                </div>
                                <div class="col-md-1">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('maintenanceType.mntns_b_add')  </label>
                                    <button onclick="savePartRow()" class="btn btn-primary">
                                        <i class="fe fe-plus mr-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </th>
        </tr>
    @endif
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
    <?php
    $part_discount_total = 0;
    $part_vat_total = 0;
    $part_total = 0;
    $part_row_count = 0;
    ?>

    @if(count($card->salesInvoice) > 0)
        @foreach($card->salesInvoice as $key=>$sales_invoice)
            @foreach($sales_invoice->details as $sales_invoice_dts)

                <?php
                $part_discount_total = $part_discount_total + $sales_invoice_dts->store_vou_disc_amount;
                $part_vat_total = $part_vat_total + $sales_invoice_dts->store_vou_vat_amount;
                $part_total = $part_total + floatval($sales_invoice_dts->store_vou_price_net);
                $part_row_count = floatval($part_row_count) + 1;
                ?>

                <tr>
                    <td class="ctd"> {{ $part_row_count }} </td>
                    <td class="ctd"> {{$sales_invoice_dts->item->item_code}}  </td>
                    <td class="ctd">  {{$sales_invoice_dts->item->item_name_e}}  </td>
                    <td class="ctd"> {{ $sales_invoice_dts->store_vou_qnt_o }} </td>
                    <td class="ctd"> {{ $sales_invoice_dts->store_vou_item_price_unit }} </td>
                    <td class="ctd"> {{ $sales_invoice_dts->store_vou_item_total_price}}  </td>
                    <td class="ctd"> {{ $sales_invoice_dts->store_vou_vat_amount }} </td>

                    <td class="ctd"> {{ optional($sales_invoice_dts->discType)->getSysCodeName()  }}  </td>
                    {{--<td class="ctd"> {{ $sales_invoice_dts->store_voue_disc_value}}  </td>--}}
                    <td class="ctd"> {{ $sales_invoice_dts->store_vou_disc_amount}}  </td>

                    <td class="ctd"> {{ $sales_invoice_dts->store_vou_price_net }} </td>
                    <td></td>
                </tr>
            @endforeach
        @endforeach

    @endif

    @foreach($card->partDetails as $key => $part)
        <?php
        $part_discount_total = $part_discount_total + $part->mntns_cards_disc_amount;
        $part_vat_total = $part_vat_total + $part->mntns_cards_vat_amount;
        $part_total = $part_total + floatval($part->mntns_cards_amount);
        $part_row_count = floatval($part_row_count) + 1;
        ?>
        <tr id="{{ $part->uuid }}">
            <td class="ctd"> {{ $part_row_count}} </td>
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
                @if(in_array($card->mntns_cards_status,$can_edit))
                    <button type="button" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only"
                            onclick="deleteItem('{{ $part->uuid }}')"><i class="fa fa-trash"></i></button>
                @endif
            </td>
        </tr>
    @endforeach

    <input type="hidden" class="form-control" name="part_row_count" id="part_row_count" value="{{ $part_row_count }}">
    <input type="hidden" class="form-control" name="part_total_disc_amount" id="part_total_disc_amount"
           value="{{ $part_discount_total }}">
    <input type="hidden" class="form-control" name="part_total_vat_amount" id="part_total_vat_amount"
           value="{{$part_vat_total}} ">
    <input type="hidden" class="form-control" name="part_total_amount" id="part_total_amount" value="{{ $part_total }}">
    </tbody>

    <tfoot>
    <tr>
        <td colspan="2" class="ctd table-active">@lang('maintenanceType.disc')</td>
        <td colspan="2" class="ctd table-active">
            <div id="part_total_disc_amount_div"> {{ $part_discount_total }} </div>
        </td>
        <td colspan="2" class="ctd table-active">@lang('maintenanceType.vat')</td>
        <td colspan="2" class="ctd table-active">
            <div id="part_total_vat_amount_div"> {{$part_vat_total}} </div>
        </td>
        <td colspan="2" class="ctd table-active"> @lang('maintenanceType.total') </td>
        <td colspan="2" class="ctd table-active">
            <div id="part_total_amount_div"> {{ $part_total }} </div>
        </td>
    </tr>
    </tfoot>
</table>
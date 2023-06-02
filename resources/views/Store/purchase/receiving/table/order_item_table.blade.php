<style type="text/css">
    .ctd {
        text-align: center;
    }
</style>
<div class="table-responsive" style="height:300px;">
    <table class="table table-bordered card_table" id="item_table">
        <tbody>
        <tr>
            <th class="ctd table-active" style="width:5%">
                <div class="form-group">
                    <label class="custom-control custom-checkbox custom-control-inline">
                        <input type="checkbox" class="custom-control-input" id="selected_all" name="selected_all"
                               onchange="checkAll(this)">
                        <span class="custom-control-label"></span>
                    </label>
                </div>
            </th>
            <!-- <th class="ctd table-active">No</th> -->
            <th class="ctd table-active">  @lang('purchase.item_code') </th>
            <th class="ctd table-active" style="width:10%"> @lang('purchase.item_name_en') </th>
            <th class="ctd table-active" style="width:5%"> @lang('purchase.qty') </th>
            <th class="ctd table-active" style="width:7%">الكمية المستلمة</th>
            <th class="ctd table-active" style="width:5%"> المتبقي</th>
            <th class="ctd table-active" style="width:10%"> @lang('purchase.unit_price') </th>
            <th class="ctd table-active" style="width:10%"> @lang('purchase.total') </th>
            <th class="ctd table-active" style="width:10%"> @lang('purchase.disc_type') </th>
            <th class="ctd table-active" style="width:10%"> @lang('purchase.disc') </th>
            <th class="ctd table-active" style="width:10%"> @lang('purchase.total_disc') </th>
            <th class="ctd table-active" style="width:10%"> @lang('purchase.vat') </th>
            <th class="ctd table-active" style="width:10%">  @lang('purchase.net_amount') </th>
            <!-- <th class="ctd table-active">الرصيد</th> -->
            <!-- <th class="ctd table-active">متوسط سعر التكلفة</th> -->
            <!-- <th class="ctd table-active">التكلفة لسابقة</th> -->

        </tr>
        <?php
        $total_sum = 0;
        $total_disc = 0;
        $total_sum_vat = 0;
        $total_sum_net = 0;
        $item_row_count = 0;
        ?>

        @foreach($purchase_request->details as $key => $d)
            <?php
            $total_sum = $total_sum + floatval($d->store_vou_item_total_price);
            $total_disc = $total_disc + floatval($d->store_vou_disc_amount);
            $total_sum_vat = $total_sum_vat + $d->store_vou_vat_amount;
            $total_sum_net = $total_sum_net + floatval($d->store_vou_price_net);
            $item_row_count = floatval($item_row_count) + 1;

            $qty = $d->store_vou_qnt_p - $d->store_vou_qnt_t_i_r;
            $store_vou_item_total_price = $qty * $d->store_vou_item_price_unit;
            $store_vou_vat_amount = $store_vou_item_total_price * trans('vat.current_vat_value');
            $store_vou_price_net = $store_vou_item_total_price + $store_vou_vat_amount;
            ?>
            <tr id="{{ $d->uuid }}">
                <td class="ctd">
                    @if($d->store_vou_qnt_p - $d->store_vou_qnt_t_i_r > 0)
                        <div class="form-group">
                            <label class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="selected_item_{{$d->uuid}}"
                                       name="{{$d->uuid}}" value="{{$d->uuid}}" onchange="selectItem(this,true)">
                                <span class="custom-control-label"></span>
                            </label>
                        </div>
                    @endif
                </td>
            <!-- <td class="ctd"> {{ $key + 1 }} </td> -->
                <td class="ctd"> {{$d->item->item_code}}  </td>
                <td class="ctd">  {{$d->item->item_name_e}}  </td>
                <td class="ctd"> {{ $d->store_vou_qnt_p - $d->store_vou_qnt_t_i_r }} </td>
                <td class="ctd">
                    <input type="text" class="form-control item-qty" name="{{$d->uuid}}"
                           id="store_vou_qnt_i_{{$d->uuid}}" value="{{ $d->store_vou_qnt_p - $d->store_vou_qnt_t_i_r}}"
                           onchange="calculateItem(this)" readonly>
                    <input type="hidden" class="form-control" name="{{$d->uuid}}" id="store_vou_qnt_p_{{$d->uuid}}"
                           value="{{ $d->store_vou_qnt_p - $d->store_vou_qnt_t_i_r}}"
                           readonly>
                </td>
                <td class="ctd">
                    <input type="text" class="form-control" name="store_vou_qnt_t_i_r"
                           id="store_vou_qnt_t_i_r_{{$d->uuid}}"
                           value="{{ $d->store_vou_qnt_p - $d->store_vou_qnt_t_i_r}}"
                           readonly>
                </td>
                <td class="ctd">
                    <input type="text" class="form-control" name="{{$d->uuid}}"
                           id="store_vou_item_price_unit_{{$d->uuid}}" value="{{ $d->store_vou_item_price_unit }}"
                           onchange="calculateTotal(this)" readonly>
                </td>
                <td class="ctd">
                    <input type="text" class="form-control total_sum" name="{{$d->uuid}}"
                           id="store_vou_item_total_price_{{$d->uuid}}" value="{{ $store_vou_item_total_price }}"
                           readonly>
                </td>

                <td class="ctd">
                    <select class="form-select form-control" name="{{$d->uuid}}" id="store_vou_disc_type_{{$d->uuid}}"
                            data-live-search="true" onchange="calculateTotal(this)" readonly>
                        @foreach($item_disc_type as $dt)
                            <option value="{{ $dt->system_code_id }}" data-mntnsdisctype="{{ $dt->getSysCodeName() }}"
                                    readonly> {{ $dt->getSysCodeName() }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="ctd">
                    <input type="text" class="form-control" name="{{$d->uuid}}" id="store_voue_disc_value_{{$d->uuid}}"
                           value="0" onchange="calculateTotal(this)" readonly>
                </td>
                <td class="ctd">
                    <input type="text" class="form-control total_disc" name="{{$d->uuid}}"
                           id="store_vou_disc_amount_{{$d->uuid}}" value="0" readonly>
                </td>
                <td class="ctd">
                    <input type="text" class="form-control total_sum_vat" name="{{$d->uuid}}"
                           id="store_vou_vat_amount_{{$d->uuid}}" value="{{ $store_vou_vat_amount }}" readonly>
                </td>
                <td class="ctd">
                    <input type="text" class="form-control total_sum_net" name="{{$d->uuid}}"
                           id="store_vou_price_net_{{$d->uuid}}" value="{{ $store_vou_price_net }}" readonly>
                </td>
            </tr>
            <input type="hidden" class="form-control" name="store_vou_ref_before" id="store_vou_ref_before"
                   value="{{ $d->purchase->uuid }}">
        @endforeach
        <input type="hidden" class="form-control" name="item_row_count" id="item_row_count"
               value="{{ $item_row_count }}">

        </tbody>
        <tfoot>
        <tr>
            <td colspan="1" class="ctd table-active"> @lang('purchase.total_amount') </td>
            <td colspan="2" class="ctd table-active">
                <div id="total_sum_div"> 0</div>
            </td>

            <td colspan="1" class="ctd table-active"> @lang('purchase.total_disc_amount') </td>
            <td colspan="2" class="ctd table-active">
                <div id="total_disc_div"> 0</div>
            </td>

            <td colspan="1" class="ctd table-active"> @lang('purchase.total_vat_amount') </td>
            <td colspan="2" class="ctd table-active">
                <div id="total_sum_vat_div"> 0</div>
            </td>

            <td colspan="1" class="ctd table-active"> @lang('purchase.total_net_amount') </td>
            <td colspan="3" class="ctd table-active">
                <div id="total_sum_net_div"> 0</div>
            </td>
        </tr>
        </tfoot>
    </table>
</div>










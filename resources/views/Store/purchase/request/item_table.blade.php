<table class="table table-bordered card_table" id="item_table">
    <tbody >
        <tr >
            <th colspan="10" style="text-align: center;background-color: #113f50;color: white;">
                @lang('purchase.items') 
            </th>
        </tr>
        <tr>
            <th colspan="10">
                <div class="col-md-12">
                    <div class="mb-3" id="add_item_div"> 
                        <div class="row">
                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label"> @lang('purchase.item_code') </label>
                                <select class="selectpicker show-tick form-control"  data-live-search="true" name="item_id" id="item_id"  >
                                    <option value="" selected> choose</option> 
                                    @foreach($itemes as $item)    
                                    <option value="{{$item->item_id}}"  data-item="{{$item}}"  data-itemname="{{$item->item_name_e}}" data-balance="{{$item->item_balance}}"
                                    data-alteritem1="{{(null!==($item->alterItem1($purchase->branch_id)) ? $item->alterItem1($purchase->branch_id) : '0')}}" data-alteritem2="{{(null!==($item->alterItem2($purchase->branch_id)) ? $item->alterItem2($purchase->branch_id) : '0')}}"> {{$item->item_code}} - {{$item->item_name_e}}  </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label">  @lang('purchase.item_name')  </label>
                                <input type="text" class="form-control" name="store_vou_item_code" id="store_vou_item_code" value="" readonly>
                            </div>
                            
                            <div class="col-md-1">
                                <label for="recipient-name" class="col-form-label">   @lang('purchase.qty') </label>
                                <input type="number" class="form-control" name="store_vou_qnt_r" id="store_vou_qnt_r" value="">
                            </div>

                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label"> @lang('purchase.unit_price') </label> 
                                <input type="number" class="form-control" name="store_vou_item_price_unit" id="store_vou_item_price_unit" value="">
                            </div>

                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label"> @lang('purchase.total') </label>
                                <input type="number" class="form-control" name="store_vou_item_total_price" id="store_vou_item_total_price" step="0.01" value="0.00" readonly>
                            </div>

                            <div class="col-sm-1 col-md-1">
                                      

                                        <div class="form-check text-center mt-40">
                                            <input class="form-check-input" type="checkbox"
                                                   style=" margin-right: -1.25rem;"
                                                 
                                                   name="store_vou_item_check" id="store_vou_item_check" value= "1"  >
                                            <label class="form-check-label"
                                                   for="store_vou_item_check"> check</label>
                                        </div>
                             </div>

                            <div class="col-md-1">
                                <label for="recipient-name" class="col-form-label"> @lang('purchase.action') </label>
                                <br>
                                <button onclick="saveItemRow()" class="btn btn-primary">
                                    <i class="fe fe-plus mr-2"></i> 
                                </button>
                            </div>
                            <div class="col-md-1">
                                <label for="recipient-name" class="col-form-label"> @lang('purchase.action') </label>
                                <br>    
                                <button type="button" class="btn btn-primary btn-block" onclick="getItemDetails()" >
                                    <i class="fe fe-search mr-2"></i> 
                                </button>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label"> @lang('purchase.item_balance')   </label>
                                <input type="number" class="form-control" name="item_balance" id="item_balance" value="" readonly>
                            </div>
                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label"> @lang('purchase.item_price_cost')   </label>
                                <input type="number" class="form-control" name="item_price_cost" id="item_price_cost" value="" readonly>
                            </div>
                            <div class="col-md-2 hidden">
                                <label for="recipient-name" class="col-form-label">@lang('purchase.last_price_cost')  </label>
                                <input type="number" class="form-control" name="last_price_cost" id="last_price_cost" value="" readonly>
                            </div>

                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label"><div id="alter_item_1_div"> الصنف البديل 1 </div> </label>
                                <input type="number" class="form-control" name="alter_item_1" id="alter_item_1" value="{{optional($item->alterItem1($purchase->branch_id))->item_balance}}" readonly>
                            </div>

                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label"> <div id="alter_item_2_div">2 الصنف البديل </div> </label>
                                <input type="number" class="form-control" name="alter_item_2" id="alter_item_2" value="{{optional($item->alterItem2($purchase->branch_id))->item_balance}}" readonly>
                            </div>

                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label"> @lang('purchase.store_vou_vat_amount') </label>
                                <input type="number" class="form-control" name="store_vou_vat_amount" id="store_vou_vat_amount" value="" readonly>
                            </div>

                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label"> @lang('purchase.store_vou_price_net') </label>
                                <input type="number" class="form-control" name="store_vou_price_net" id="store_vou_price_net" value="" readonly>
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            </th>
        </tr>
        <tr>
            <th class="ctd table-active"> No</th>
            <th class="ctd table-active" style="width:25%"> @lang('purchase.item_code') </th>
            <th class="ctd table-active"> @lang('purchase.item_name_en') </th>
            <th class="ctd table-active"> @lang('purchase.qty') </th>
            <th class="ctd table-active"> @lang('purchase.unit_price') </th>
            <th class="ctd table-active"> @lang('purchase.total') </th>
            <th class="ctd table-active"> @lang('purchase.vat') </th>
            <th class="ctd table-active"> @lang('purchase.net_amount') </th>
            <th class="ctd table-active"> @lang('purchase.action') </th>
        </tr>
        <?php 
            $total_sum = 0;
            $total_sum_vat = 0;
            $total_sum_net = 0;
            $item_row_count = 0;
        ?>
        
        @foreach($purchase->details as $key => $d)
            <?php 
                $total_sum = $total_sum + floatval($d->store_vou_item_total_price);
                $total_sum_vat = $total_sum_vat + $d->store_vou_vat_amount;
                $total_sum_net = $total_sum_net + floatval($d->store_vou_price_net);
                $item_row_count =  floatval($item_row_count) + 1;
            ?>
            <tr id="{{ $d->uuid }}">
                <td class="ctd"> {{ $key + 1 }} </td>
                <td class="ctd"> {{$d->item->item_code}}  </td>
                <td class="ctd">  {{$d->item->item_name_e}}  </td>
                <td class="ctd"> {{ $d->store_vou_qnt_r }} </td>
                <td class="ctd"> {{ $d->store_vou_item_price_unit }} </td>
                <td class="ctd"> {{ number_format($d->store_vou_item_total_price,2)}}  </td>
                <td class="ctd"> {{ $d->store_vou_vat_amount }} </td>
                <td class="ctd"> {{ number_format($d->store_vou_price_net,2) }} </td>
                <!-- <td class="ctd"> {{ $d->mntns_cards_vat_amount }} </td> -->
                <!-- <td class="ctd"> {{ $d->mntns_cards_amount }} </td> -->
                <!-- <td class="ctd"> {{ $d->mntns_cards_amount }} </td> -->
                <td class="ctd"> 
                    <button type="button"  class="btn btn-danger m-btn m-btn--icon m-btn--icon-only" onclick="deleteItem('{{ $d->uuid }}')"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        @endforeach
        <input type="hidden" class="form-control" name="item_row_count" id="item_row_count" value="{{ $item_row_count }}" >
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" class="ctd table-active"> @lang('purchase.total_amount') </td>
            <td colspan="1" class="ctd table-active"> <div id="total_sum_div"> {{number_format($total_sum,2)}} </div> </td>
            <td colspan="1" class="ctd table-active"> @lang('purchase.total_vat_amount') </td>
            <td colspan="2" class="ctd table-active"> <div id="total_sum_vat_div"> {{number_format($total_sum_vat,2)}} </div> </td>
            <td colspan="1" class="ctd table-active"> @lang('purchase.total_net_amount') </td>
            <td colspan="2" class="ctd table-active"> <div id="total_sum_net_div"> {{ number_format( $total_sum_net ,2) }} </div> </td>
        </tr>
    </tfoot>
</table>










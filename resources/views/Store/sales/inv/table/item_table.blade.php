<table class="table table-bordered card_table" id="item_table">
    <tbody>
    <tr>
        <th colspan="13" style="text-align: center;background-color: #113f50;color: white;">
            @lang('purchase.items')
        </th>
    </tr>
    <!-- <tr>
            <th colspan="10">
                <div class="col-md-12">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label">  كود الصنف </label>
                                <select class="selectpicker show-tick form-control"  data-live-search="true" name="item_id" id="item_id"  >
                                    <option value="" selected> choose</option> 
                                    @foreach($itemes as $item)
        <option value="{{$item->item_id}}"  data-item="{{$item}}"  data-itemname="{{$item->item_name_e}}" data-balance="{{$item->item_balance}}"> {{$item->item_code}}  </option>
                                    @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="recipient-name" class="col-form-label">  اسم الصنف  </label>
            <input type="text" class="form-control" name="store_vou_item_code" id="store_vou_item_code" value="" readonly>
        </div>

        <div class="col-md-2">
            <label for="recipient-name" class="col-form-label">  الكمية </label>
            <input type="number" class="form-control" name="store_vou_qnt_p" id="store_vou_qnt_p" value="">
        </div>

        <div class="col-md-2">
            <label for="recipient-name" class="col-form-label">  سعر الوحدة </label>
            <input type="number" class="form-control" name="store_vou_item_price_unit" id="store_vou_item_price_unit" value="">
        </div>

        <div class="col-md-2">
            <label for="recipient-name" class="col-form-label">  الاجمالي </label>
            <input type="number" class="form-control" name="store_vou_item_total_price" id="store_vou_item_total_price" value="" readonly>
        </div>

        <div class="col-md-1">
            <label for="recipient-name" class="col-form-label"> العمليات  </label>
            <br>
            <button onclick="saveItemRow()" class="btn btn-primary">
                <i class="fe fe-plus mr-2"></i>
            </button>
        </div>

        <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"> الرصيد  </label>
            <input type="number" class="form-control" name="item_balance" id="item_balance" value="" readonly>
        </div>
        <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"> متوسط سعر التكلفة  </label>
            <input type="number" class="form-control" name="item_price_cost" id="item_price_cost" value="" readonly>
        </div>
        <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"> التكلفة لسابقة  </label>
            <input type="number" class="form-control" name="last_price_cost" id="last_price_cost" value="" readonly>
        </div>

        <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"> قيمة الضريبة </label>
            <input type="number" class="form-control" name="store_vou_vat_amount" id="store_vou_vat_amount" value="" readonly>
        </div>

        <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"> الاجمالي الصافي </label>
            <input type="number" class="form-control" name="store_vou_price_net" id="store_vou_price_net" value="" readonly>
        </div>
    </div>
</div>
</div>
</th>
</tr> -->
    <tr>
        <th class="ctd table-warning">No</th>
        <th class="ctd table-warning" style="width:8%"> @lang('purchase.item_code')</th>
        <th class="ctd table-warning" style="width:15%">  @lang('purchase.item_name_en')</th>
        <th class="ctd table-warning"> @lang('purchase.qty') </th>
        <th class="ctd table-warning"> @lang('purchase.unit_price') </th>
        <td class="ctd table-warning">
            {{__('item serial')}}
        </td>
        <th class="ctd table-warning">@lang('purchase.net_amount')</th>

        <th class="ctd table-warning" style="width:10%"> @lang('purchase.disc_type') </th>
        <th class="ctd table-warning" style="width:10%"> @lang('purchase.disc') </th>
        <th class="ctd table-warning" style="width:10%"> @lang('purchase.total_disc') </th>

        <th class="ctd table-warning">@lang('purchase.vat')</th>
        <th class="ctd table-warning"> @lang('purchase.net_amount')</th>
        @if($purchase->status->system_code != 125002)
            <th class="ctd table-warning">Action</th>
        @endif
    </tr>
    <?php
    $total_sum = 0;
    $total_disc_sum = 0;
    $total_sum_vat = 0;
    $total_sum_net = 0;
    $item_row_count = 0;
    ?>

    @foreach($purchase->details as $key => $d)
        <?php
        $total_sum = $total_sum + floatval($d->store_vou_item_total_price);
        $total_disc_sum = $total_disc_sum + floatval($d->store_vou_disc_amount);
        $total_sum_vat = $total_sum_vat + $d->store_vou_vat_amount;
        $total_sum_net = $total_sum_net + floatval($d->store_vou_price_net);
        $item_row_count = floatval($item_row_count) + 1;
        ?>
        <tr id="{{ $d->uuid }}">
            <td class="ctd"> {{ $key + 1 }} </td>
            <td class="ctd"> {{$d->item->item_code}}  </td>
            <td class="ctd">  {{$d->item->item_name_e}}  </td>
            <td class="ctd"> {{ $d->store_vou_qnt_o }} </td>
            <td class="ctd"> {{ $d->store_vou_item_price_unit }} </td>
            <td class="ctd">
                <i class="fa fa-plus btn btn-danger btn-block" data-toggle="modal"
                   data-target=".bd-example-modal-lg{{$d->store_dt_id}}"></i>
            </td>
            <td class="ctd"> {{ $d->store_vou_item_total_price}}  </td>

            <td class="ctd"> {{ optional($d->discType)->getSysCodeName()  }}  </td>
            <td class="ctd"> {{ $d->store_voue_disc_value}}  </td>
            <td class="ctd"> {{ $d->store_vou_disc_amount}}  </td>

            <td class="ctd"> {{ $d->store_vou_vat_amount }} </td>
            <td class="ctd"> {{ $d->store_vou_price_net }} </td>
        <!-- <td class="ctd"> {{ $d->mntns_cards_vat_amount }} </td> -->
        <!-- <td class="ctd"> {{ $d->mntns_cards_amount }} </td> -->
        <!-- <td class="ctd"> {{ $d->mntns_cards_amount }} </td> -->
            @if($purchase->status->system_code != 125002)
                <td class="ctd">
                    <button type="button" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only"
                            onclick="deleteItem('{{ $d->uuid }}')"><i class="fa fa-trash"></i></button>
                </td>
            @endif
        </tr>
    @endforeach
    <input type="hidden" class="form-control" name="item_row_count" id="item_row_count" value="{{ $item_row_count }}">
    </tbody>
    <tfoot>
    <tr>
        <td colspan="1" class="ctd table-active"> @lang('purchase.total_amount') </td>
        <td colspan="1" class="ctd table-active">
            <div id="total_sum_div"> {{$purchase->store_vou_total + $purchase->store_vou_desc - $purchase->store_vou_vat_amount}} </div>
        </td>
        <td colspan="1" class="ctd table-active"> @lang('purchase.total_disc_amount') </td>
        <td colspan="2" class="ctd table-active">

            <input type="number" id="total_discount_div" class="form-control" name="total_discount_div"
                   value="{{ $purchase->store_vou_desc ?  $purchase->store_vou_desc : 0}}" readonly>

        </td>
        <td colspan="1" class="ctd table-active"> @lang('purchase.total_vat_amount') </td>
        <td colspan="2" class="ctd table-active">
            <div id="total_sum_vat_div"> {{ $purchase->store_vou_vat_amount}} </div>
        </td>
        <td colspan="2" class="ctd table-active"> @lang('purchase.total_net_amount') </td>
        <td colspan="2" class="ctd table-active">
            <div id="total_sum_net_div"> {{ $purchase->store_vou_total  }} </div>
        </td>
        @if($purchase->status->system_code != 125002)
            <td class="ctd table-active"></td>
        @endif
    </tr>
    </tfoot>
</table>



@foreach($purchase->details as $key => $d)
    <div class="modal fade bd-example-modal-lg{{$d->store_dt_id}}" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{__('item serial')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    @if(count($purchase->items) > 0)
                        @if(count($purchase->items->where('item_id_dt',$d->store_vou_item_id)) > 0)
                            @foreach($purchase->items->where('item_id_dt',$d->store_vou_item_id)  as $k2=>$item)
                                <label>{{__('item')}} -> {{ $key+1 }}</label>
                                <input type="text" class="form-control"
                                       value="{{ $item->item_stor_dt_serial }}" readonly>
                            @endforeach
                        @else
                            <p class="font-20">لم يتم اضافه ارقام تسلسليه</p>
                        @endif

                    @else
                        <p class="font-20">لم يتم اضافه ارقام تسلسليه</p>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('close')}}</button>
                </div>

            </div>
        </div>
    </div>
@endforeach







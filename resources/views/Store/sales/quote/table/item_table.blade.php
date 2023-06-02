<table class="table table-bordered card_table" id="item_table">
    <tbody >
        <tr >
            <th colspan="11" style="text-align: center;background-color: #113f50;color: white;">
                @lang('purchase.items') 
            </th>
        </tr>
        <tr>
            <th colspan="11">
                <div class="col-md-12">
                    <div class="mb-3" id="add_item_div">
                        <div class="row"> 
                            <div class="col-md-3">
                                <label for="recipient-name" class="col-form-label">  كود الصنف </label>
                                <select class="selectpicker show-tick form-control"  data-live-search="true" name="item_id" id="item_id"  >
                                    <option value="" selected> choose</option> 
                                    @foreach($itemes as $item)    
                                    <option value="{{$item->item_id}}"  data-item="{{$item}}"  data-itemname="{{$item->item_name_e}}" data-balance="{{$item->item_balance}}"> {{$item->item_code}} - {{$item->item_name_e}}  </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="recipient-name" class="col-form-label">  اسم الصنف  </label>
                                <input type="text" class="form-control" name="store_vou_item_code" id="store_vou_item_code" value="" readonly>
                            </div>
                            
                            <div class="col-md-1">
                                <label for="recipient-name" class="col-form-label">  الكمية </label>
                                <input type="number" class="form-control" name="store_vou_qnt_o" id="store_vou_qnt_o" value="">
                            </div>

                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label">  سعر الوحدة </label> 
                                <input type="number" class="form-control" name="store_vou_item_price_unit" id="store_vou_item_price_unit" value="" >
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
                                <label for="recipient-name" class="col-form-label">  السعر   </label>
                                <input type="number" class="form-control" name="item_price_cost" id="item_price_cost" value="" readonly>
                            </div>

                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label"> نسبة خصم العميل  </label>
                                <input type="number" class="form-control" name="discount_rate" id="discount_rate" value="" readonly>
                            </div>
                            
                            <div hidden class="col-md-2">
                                <label for="recipient-name" class="col-form-label"> سعر البيع السابق  </label>
                                <input type="number" class="form-control" name="last_price_cost" id="last_price_cost" value="" readonly>
                            </div>

                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label"> قيمة الخصم </label>
                                <input type="number" class="form-control" name="store_vou_disc_amount" id="store_vou_disc_amount" value="" readonly>
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
        </tr>
        <tr>
            <th class="ctd table-active">No</th>
            <th class="ctd table-active" style="width:25%">كود الصنف</th>
            <th class="ctd table-active">اسم لاصنف انجليزي</th>
            <th class="ctd table-active">الكمية </th>
            <th class="ctd table-active">سعر الوحدة</th>
            <th class="ctd table-active">الخصم</th>
            <th class="ctd table-active">الاجمالي</th>
            <th class="ctd table-active">الضريبة</th>
            <th class="ctd table-active"> الاجمالي الصافي</th>
            <!-- <th class="ctd table-active">الرصيد</th> -->
            <!-- <th class="ctd table-active">متوسط سعر التكلفة</th> -->
            <!-- <th class="ctd table-active">التكلفة لسابقة</th> -->
            <th class="ctd table-active">Action</th>
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
                <td class="ctd"> {{($d->store_vou_item_id == 0 ? $d->store_vou_item_code :$d->item->item_code)}}  </td>
                <td class="ctd"> {{($d->store_vou_item_id == 0 ? 'NA' :$d->item->item_name_e)}}   </td>
                <td class="ctd"> {{ $d->store_vou_qnt_q }} </td>
                <td class="ctd"> {{ $d->store_vou_item_price_unit }} </td>
                <td class="ctd"> {{ $d->store_vou_disc_amount }} </td>
                <td class="ctd"> {{ $d->store_vou_item_total_price}}  </td>
                <td class="ctd"> {{ $d->store_vou_vat_amount }} </td>
                <td class="ctd"> {{ $d->store_vou_price_net }} </td>
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
            <td colspan="1" class="ctd table-active">الاجمالي</td>
            <td colspan="1" class="ctd table-active"> <div id="total_sum_div"> {{$total_sum}} </div> </td>
           <td colspan="2" class="ctd table-active"> @lang('purchase.total_vat_amount') </td>
            <td colspan="2" class="ctd table-active"> <div id="total_sum_vat_div"> {{$total_sum_vat}} </div> </td>
            <td colspan="2" class="ctd table-active" > @lang('purchase.total_net_amount') </td>
            <td colspan="2" class="ctd table-active"> <div id="total_sum_net_div"> {{ $total_sum_net  }} </div> </td>
        </tr>
    </tfoot>
</table>










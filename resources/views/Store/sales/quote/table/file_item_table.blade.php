


<div class="table-responsive" style="height:300px;">
    <table class="table table-bordered card_table" id="item_table">
        <tbody >
            <tr>
                <th class="ctd table-active" style="width:5%">
                    <div class="form-group">
                        <label class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" class="custom-control-input" id="selected_all" name="selected_all" onchange="checkAll(this)" disabled>
                            <span class="custom-control-label"></span>
                        </label> 
                    </div>
                </th>
                <!-- <th class="ctd table-active">No</th> -->
                <th class="ctd table-active" style="width:15%">كود الصنف</th>
                <th class="ctd table-active" style="width:15%">اسم لاصنف انجليزي</th>
                <th class="ctd table-active" style="width:5%"> الكمية الحالية </th>
                <th class="ctd table-active" style="width:7%">كمية عرض السعر</th>
                <th class="ctd table-active" style="width:10%"> @lang('purchase.unit_price') </th>
                <th class="ctd table-active" style="width:10%"> @lang('purchase.total') </th>
                <th class="ctd table-active" style="width:10%"> @lang('purchase.vat') </th>
                <th class="ctd table-active" style="width:10%">  @lang('purchase.net_amount') </th>
            </tr>
            <?php 
                $total_sum = 0;
                $total_disc = 0;
                $total_sum_vat = 0;
                $total_sum_net = 0;
                $item_row_count = 0;
            ?>
            
            @foreach($data as $key => $d)
                <?php 
                    $total_sum = $total_sum + floatval($d['store_vou_item_total_price']);
                    $total_sum_vat = $total_sum_vat + $d['store_vou_vat_amount'];
                    $total_sum_net = $total_sum_net + floatval($d['store_vou_price_net']);
                    $item_row_count =  floatval($item_row_count) + 1;
                ?>
                <tr id="{{ $d['uuid'] }}">
                    <td class="ctd">
                        <div class="form-group">
                            <label class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="selected_item_{{ $d['uuid'] }}" name="{{ $d['uuid'] }}" value="{{ $d['uuid'] }}" onchange="selectItem(this)" checked disabled>
                                <span class="custom-control-label"></span>
                            </label> 
                        </div>
                    </td>
                    <!-- <td class="ctd"> {{ $key + 1 }} </td> -->
                   
                    <td class="ctd"> {{ $d['store_vou_item_code'] }}  </td>
                    <td class="ctd"> {{ $d['store_vou_item_name'] }}  </td>
                    <td class="ctd"> {{ number_format( $d['item_balance'] ) }} </td>
                    <td class="ctd"> 
                        <input type="text" class="form-control item-qty" name="{{ $d['uuid'] }}" id="store_vou_qnt_i_{{ $d['uuid'] }}" value="{{ number_format($d['qty']) }}" onchange="calculateItem(this)" readonly>
                        <input type="hidden" class="form-control" name="{{ $d['uuid'] }}" id="store_vou_qnt_p_{{ $d['uuid'] }}" value="{{ number_format( $d['item_balance'] ) }}" onchange="calculateItem(this)" readonly>
                        <input type="hidden" class="form-control" name="{{ $d['uuid'] }}" id="store_vou_item_id_{{ $d['uuid'] }}" value="{{ number_format( $d['store_vou_item_id'] ) }}" onchange="calculateItem(this)" readonly>
                        <input type="hidden" class="form-control" name="{{ $d['uuid'] }}" id="store_vou_item_code_{{ $d['uuid'] }}" value="{{  $d['store_vou_item_code']  }}" onchange="calculateItem(this)" readonly>
                    </td>
                    <td class="ctd"> 
                        <input type="text" class="form-control" name="{{ $d['uuid'] }}" id="store_vou_item_price_unit_{{ $d['uuid'] }}" value="{{ $d['store_vou_item_price_unit'] }}"  onchange="calculateTotal(this)" readonly>
                    </td>
                    <td class="ctd"> 
                        <input type="text" class="form-control total_sum" name="{{ $d['uuid'] }}" id="store_vou_item_total_price_{{ $d['uuid'] }}" value="{{ $d['store_vou_item_total_price'] }}"  readonly>  
                    </td>
                    <td class="ctd">
                        <input type="text" class="form-control total_sum_vat" name="{{ $d['uuid'] }}" id="store_vou_vat_amount_{{ $d['uuid'] }}" value="{{ $d['store_vou_vat_amount'] }}" readonly>  
                    </td>
                    <td class="ctd">
                        <input type="text" class="form-control total_sum_net" name="{{ $d['uuid'] }}" id="store_vou_price_net_{{ $d['uuid'] }}" value="{{ $d['store_vou_price_net'] }}" readonly>
                    </td>
                </tr>
            @endforeach
            <input type="hidden" class="form-control" name="item_row_count" id="item_row_count" value="{{ $item_row_count }}" >
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="ctd table-active">الاجمالي </td>
                <td colspan="1" class="ctd table-active"> <div id="total_sum_div">  {{ $total_sum }} </div> </td>

                <td colspan="2" class="ctd table-active">اجمالي الضريبة</td>
                <td colspan="1" class="ctd table-active"> <div id="total_sum_vat_div"> {{ $total_sum_vat }}</div> </td>

                <td colspan="2" class="ctd table-active" >الاجمالي الصافي</td>
                <td colspan="1" class="ctd table-active"> <div id="total_sum_net_div"> {{ $total_sum_net }} </div> </td>
            </tr>
        </tfoot>
    </table>
</div>









<style type="text/css">
    .ctd{
        text-align: center;
    }
</style>
<div class="table-responsive" style="height:300px;">
    <table class="table table-bordered card_table" id="item_table" style="width:210%">
        <tbody >
            <tr>
                <th class="ctd table-active" style="width:5%">
                    <div class="form-group">
                        <label class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" class="custom-control-input" id="selected_all" name="selected_all" onchange="checkAll(this)">
                            <span class="custom-control-label"></span>
                        </label> 
                    </div>
                </th>
                <!-- <th class="ctd table-active">No</th> -->
                <th class="ctd table-active" width="7%">رقم الشاسي</th>
                <th class="ctd table-active" width="5%"> رقم اللوحة</th>
                <th class="ctd table-active" width="5%"> @lang('sales_car.brand') </th>
                <th class="ctd table-active" width="5%"> @lang('sales_car.brand_dt') </th>
                <th class="ctd table-active" width="5%"> اللون </th>
                <th class="ctd table-active" width="5%">  الموديل </th>
                <th class="ctd table-active" width="5%"> سعر البيع </th>
                <th class="ctd table-active" width="5%"> الوصف </th>
                <th class="ctd table-active" width="5%"> @lang('sales_car.qty') </th>
                <th class="ctd table-active" width="5%">الكمية المستلمة</th>
                <th class="ctd table-active" width="5%"> المتبقي  </th>
                <th class="ctd table-active" width="5%"> @lang('sales_car.unit_price') </th>
                <th class="ctd table-active" width="5%"> رسوم اضافية</th>
                <th class="ctd table-active" width="5%"> @lang('sales_car.total') </th>
                <th class="ctd table-active" width="4%"> @lang('sales_car.disc_type') </th>
                <th class="ctd table-active" width="5%"> @lang('sales_car.disc') </th>
                <th class="ctd table-active" width="5%"> @lang('sales_car.total_disc') </th>
                <th class="ctd table-active" width="5%"> @lang('sales_car.vat') </th>
                <th class="ctd table-active" width="5%">  @lang('sales_car.net_amount') </th>

            </tr>
            <?php 
                $total_sum = 0;
                $total_disc = 0;
                $total_sum_vat = 0;
                $total_sum_net = 0;
                $item_row_count = 0;
            ?>
            
            @foreach($sales_request->details as $key => $d)
           
            <?php 
                    $total_sum = $total_sum + floatval($d->store_vou_item_total_price);
                    $total_disc = $total_disc + floatval($d->store_vou_disc_amount);
                    $total_sum_vat = $total_sum_vat + $d->store_vou_vat_amount;
                    $total_sum_net = $total_sum_net + floatval($d->store_vou_price_net);
                    $item_row_count =  floatval($item_row_count) + 1;

                    $qty = number_format($d->store_vou_qnt_p) - number_format($d->store_vou_qnt_t_i_r);
                    $store_vou_item_total_price = $qty * $d->store_vou_item_price_unit;
                    $store_vou_vat_amount = $store_vou_item_total_price * trans('vat.current_vat_value');
                    $store_vou_price_net =  $store_vou_item_total_price + $store_vou_vat_amount ;
                    $all_qty = $d->store_vou_qnt_p - $d->store_vou_qnt_t_i_r;
                ?>

            @if($d->store_vou_qnt_p - $d->store_vou_qnt_t_i_r > 0)
                @for($i=0; $i < ($d->store_vou_qnt_p - $d->store_vou_qnt_t_i_r) ; $i++)
                
                <tr id="{{ $i }}">
                    <td class="ctd">
                        @if($d->store_vou_qnt_p - $d->store_vou_qnt_t_i_r > 0)
                        <div class="form-group">
                            <label class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="selected_item_{{$i}}" name="{{$i}}" value="{{$i}}" onchange="selectItem(this,'{{$page}}')">
                                <span class="custom-control-label"></span>
                            </label> 
                        </div>
                        @endif
                    </td>
                    <td class="ctd">
                        <input type="text" class="form-control" name="{{$i}}" id="sales_cars_chasie_no_{{$i}}" value="" readonly>
                    </td>
                    <td class="ctd">
                        <input type="text" class="form-control" name="{{$i}}" id="sales_cars_plate_no_{{$i}}" value="" readonly>
                    </td>
                    <!-- <td class="ctd"> {{ $key + 1 }} </td> -->
                    <td class="ctd"> {{$d->brand->getName()}}  </td>
                    <td class="ctd">  {{$d->brandDT->getBrandName()}}  </td>
                    <td class="ctd">
                        <input type="text" class="form-control" name="{{$i}}" id="sales_cars_color_{{$i}}" value="{{ $car_data['sales_cars_color'] }}" readonly>
                    </td>
                    <td class="ctd">
                        <input type="text" class="form-control" name="{{$i}}" id="sales_cars_model_{{$i}}" value="{{ $car_data['sales_cars_model'] }}" readonly>
                    </td>
                    <td class="ctd">
                        <input type="text" class="form-control" name="{{$i}}" id="sales_cars_sales_amount_{{$i}}" value="{{ $car_data['sales_cars_sales_amount'] }}" readonly>
                    </td>
                    
                    <td class="ctd">
                        <input type="text" class="form-control" name="{{$i}}" id="sales_cars_desc_{{$i}}" value="{{ $car_data['sales_cars_desc'] }}" readonly>
                    </td>

                    <td class="ctd"> {{ ($i+1) }} من {{ number_format($all_qty)  }} </td>
                    <td class="ctd"> 
                        <input type="text" class="form-control item-qty" name="{{$i}}" id="store_vou_qnt_i_{{$i}}" value="{{ (number_format(1) )}}" onchange="calculateItem(this)" readonly>
                        <input type="hidden" class="form-control" name="{{$i}}" id="store_vou_qnt_p_{{$i}}" value="{{ (number_format(0) )}}" readonly>
                        <input type="hidden" class="form-control" name="{{$d->uuid}}" id="uuid_{{$i}}" value="{{$d->uuid}}" readonly>
                    </td>
                    <td class="ctd"> 
                        <input type="text" class="form-control" name="store_vou_qnt_t_i_r" id="store_vou_qnt_t_i_r_{{$i}}" value="{{ (number_format(0) )}}" readonly>
                    </td>
                    <td class="ctd"> 
                        <input type="text" class="form-control" name="{{$i}}" id="store_vou_item_price_unit_{{$i}}" value="{{ $d->store_vou_item_price_unit }}"  onchange="calculateTotal(this)" readonly>
                    </td>
                    <td class="ctd">
                        <input type="text" class="form-control" name="{{$i}}" id="sales_cars_add_amount_{{$i}}" value="{{ $car_data['sales_cars_add_amount'] }}"  onchange="calculateTotal(this)" readonly>
                    </td>
                    <td class="ctd"> 
                        <input type="text" class="form-control total_sum" name="{{$i}}" id="store_vou_item_total_price_{{$i}}" value="{{ number_format($store_vou_item_total_price/$all_qty +   $car_data['sales_cars_add_amount']) }}"  readonly>  
                    </td>
                   
                    <td class="ctd">
                        <select class="form-select form-control" name="{{$i}}" id="store_vou_disc_type_{{$i}}" data-live-search="true" onchange="calculateTotal(this)" >
                            @foreach($item_disc_type as $dt)
                            <option value="{{ $dt->system_code_id }}" data-mntnsdisctype="{{ $dt->getSysCodeName() }}"> {{ $dt->getSysCodeName() }}</option> 
                            @endforeach
                        </select>
                    </td>
                    <td class="ctd">
                        <input type="text" class="form-control" name="{{$i}}" id="store_voue_disc_value_{{$i}}" value="0" onchange="calculateTotal(this)"  readonly>
                    </td>
                    <td class="ctd">
                        <input type="text" class="form-control total_disc" name="{{$i}}" id="store_vou_disc_amount_{{$i}}" value="0" readonly>
                    </td>
                    <td class="ctd">
                        <input type="text" class="form-control total_sum_vat" name="{{$i}}" id="store_vou_vat_amount_{{$i}}" value="0" readonly>  
                    </td>
                    <td class="ctd">
                        <input type="text" class="form-control total_sum_net" name="{{$i}}" id="store_vou_price_net_{{$i}}" value="0" readonly>
                    </td>
                   
                </tr>
                @endfor
            @endif
            @endforeach
            <input type="hidden" class="form-control" name="item_row_count" id="item_row_count" value="{{ $item_row_count }}" >
            <input type="hidden" class="form-control" name="store_vou_ref_before" id="store_vou_ref_before" value="{{ $d->sales->uuid }}" >
        </tbody>
        <tfoot>
            <tr>
                <td colspan="1" class="ctd table-active"> @lang('sales_car.total_amount') </td>
                <td colspan="4" class="ctd table-active"> <div id="total_sum_div"> 0</div> </td>

                <td colspan="1" class="ctd table-active"> @lang('sales_car.total_disc_amount') </td>
                <td colspan="4" class="ctd table-active"> <div id="total_disc_div"> 0 </div> </td>

               <td colspan="1" class="ctd table-active"> @lang('sales_car.total_vat_amount') </td>
                <td colspan="4" class="ctd table-active"> <div id="total_sum_vat_div"> 0 </div> </td>

                <td colspan="1" class="ctd table-active" > @lang('sales_car.total_net_amount') </td>
                <td colspan="4" class="ctd table-active"> <div id="total_sum_net_div"> 0 </div> </td>
            </tr>
        </tfoot>
    </table>
</div>










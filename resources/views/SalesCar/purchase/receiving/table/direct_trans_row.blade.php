
@foreach($sales_request->details as $key => $d)
    <?php 
        $total_sum = $total_sum + floatval($d->store_vou_item_total_price);
        $total_disc = $total_disc + floatval($d->store_vou_disc_amount);
        $total_sum_vat = $total_sum_vat + $d->store_vou_vat_amount;
        $total_sum_net = $total_sum_net + floatval($d->store_vou_price_net);
        $item_row_count =  floatval($item_row_count) + 1;
        $all_qty = $d->store_vou_qnt_t_o - $d->store_vou_qnt_t_i_r;
    ?>
    <input type="hidden" class="form-control" name="store_vou_ref_before" id="store_vou_ref_before" value="{{ $d->sales->uuid }}" >
    @if($d->store_vou_qnt_t_o - $d->store_vou_qnt_t_i_r > 0)
        <?php  $i = $key ?>
        <tr id="{{ $i }}">
            <td class="ctd">
                @if($d->store_vou_qnt_t_o - $d->store_vou_qnt_t_i_r > 0)
                <div class="form-group">
                    <label class="custom-control custom-checkbox custom-control-inline">
                        <input type="checkbox" class="custom-control-input" id="selected_item_{{$i}}" name="{{$i}}" value="{{$i}}" onchange="selectItem(this,'{{$page}}')">
                        <span class="custom-control-label"></span>
                    </label> 
                </div>
                @endif
            </td>
            
            <!-- <td class="ctd"> {{ $key + 1 }} </td> -->
            <td class="ctd"> 
                <input type="text" class="form-control" name="{{$i}}" id="sales_cars_chasie_no_{{$i}}" value="{{ optional($d->car)->sales_cars_chasie_no  }}" readonly>          
            </td>
            <td class="ctd"> 
            <input type="text" class="form-control" name="{{$i}}" id="sales_cars_plate_no_{{$i}}" value="{{ optional($d->car)->sales_cars_plate_no  }}" readonly>
            </td>
            <td class="ctd"> {{ $d->brand->getName()}}  </td>
            <td class="ctd"> {{ $d->brandDT->getBrandName()}}  </td>
            <td class="ctd">
                <input type="text" class="form-control" name="{{$i}}" id="sales_cars_color_{{$i}}" value="{{ optional($d->car)->sales_cars_color  }}" readonly>
            </td>
            <td class="ctd">
                <input type="text" class="form-control" name="{{$i}}" id="sales_cars_model_{{$i}}" value="{{ optional($d->car)->sales_cars_model }}" readonly>
            </td>
            <td class="ctd">
                <input type="text" class="form-control" name="{{$i}}" id="sales_cars_sales_amount_{{$i}}" value="{{ optional($d->car)->sales_cars_sales_amount }}" readonly>
            </td>
            
            <td class="ctd">
                <input type="text" class="form-control" name="{{$i}}" id="sales_cars_desc_{{$i}}" value="{{ optional($d->car)->sales_cars_desc }}" readonly>
            </td>

            <td class="ctd"> {{ ($i+1) }} من {{ number_format($all_qty)  }} </td>
            <td class="ctd"> 
                <input type="text" class="form-control item-qty" name="{{$i}}" id="store_vou_qnt_t_i_{{$i}}" value="{{ (number_format(1) )}}" onchange="calculateTransItem(this)" readonly>
                <input type="hidden" class="form-control" name="{{$i}}" id="store_vou_qnt_t_o_{{$i}}" value="{{ (number_format(1) )}}" readonly>
                <input type="hidden" class="form-control" name="{{$d->uuid}}" id="uuid_{{$i}}" value="{{$d->uuid}}" readonly>
                <input type="hidden" class="form-control" name="{{$i}}" id="car_uuid_{{$i}}" value="{{ optional($d->car)->uuid }}" >
                <input type="hidden" class="form-control" name="{{$i}}" id="store_brand_id_{{$i}}" value="{{ $d->store_brand_id }}" readonly>
                <input type="hidden" class="form-control" name="{{$i}}" id="store_brand_dt_id_{{$i}}" value="{{ $d->store_brand_dt_id }}" readonly>
            </td>
            <td class="ctd"> 
                <input type="text" class="form-control" name="store_vou_qnt_t_i_r" id="store_vou_qnt_t_i_r_{{$i}}" value="0" readonly>
            </td>
            <td class="ctd"> 
            <input type="text" class="form-control" name="{{$i}}" id="store_vou_item_price_unit_{{$i}}" value="{{ $d->store_vou_item_price_unit }}"  onchange="calculateTransTotal(this)" readonly>
            </td>
            <td class="ctd">
                <input type="text" class="form-control" name="{{$i}}" id="sales_cars_add_amount_{{$i}}" value="{{ optional($d->car)->sales_cars_add_amount }}"  onchange="calculateTransTotal(this)" readonly>
            </td>
            <td class="ctd"> 
                <input type="text" class="form-control total_sum" name="{{$i}}" id="store_vou_item_total_price_{{$i}}" value="{{ number_format($d->store_vou_item_total_price/$all_qty +   optional($d->car)->sales_cars_add_amount) }}"  readonly>  
            </td>
            
            <td class="ctd">
                <select class="form-select form-control" name="{{$i}}" id="store_vou_disc_type_{{$i}}" data-live-search="true" onchange="calculateTransTotal(this)" >
                    @foreach($item_disc_type as $dt)
                    <option value="{{ $dt->system_code_id }}" data-mntnsdisctype="{{ $dt->getSysCodeName() }}"> {{ $dt->getSysCodeName() }}</option> 
                    @endforeach
                </select>
            </td>
            <td class="ctd">
                <input type="text" class="form-control" name="{{$i}}" id="store_voue_disc_value_{{$i}}" value="0" onchange="calculateTransTotal(this)"  readonly>
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
       
    @endif
@endforeach
<input type="hidden" class="form-control" name="trans_req_type" id="trans_req_type" value="direct_trans" >
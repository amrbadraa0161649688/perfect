<table class="table table-bordered card_table" id="item_table">
    <tbody >
        <tr >
            <th colspan="10" style="text-align: center;background-color: #113f50;color: white;">
                @lang('sales_car.items') 
            </th>
        </tr>
        <tr>
            <th class="ctd table-active">No</th>
            <th class="ctd table-active" style="width:15%"> @lang('sales_car.brand') </th>
            <th class="ctd table-active" style="width:15%"> @lang('sales_car.brand_dt') </th>
            <th class="ctd table-active">  @lang('sales_car.qty') </th>
            <th class="ctd table-active"> @lang('sales_car.unit_price') </th>
            <th class="ctd table-active">@lang('sales_car.total') </th>

            <!-- <th class="ctd table-active" style="width:10%"> @lang('sales_car.disc_type') </th>
            <th class="ctd table-active" style="width:10%"> @lang('sales_car.disc') </th>
            <th class="ctd table-active" style="width:10%"> @lang('sales_car.total_disc') </th> -->

            <th class="ctd table-active"> @lang('sales_car.vat') </th>
            <th class="ctd table-active"> @lang('sales_car.net_amount') </th>
            <!-- <th class="ctd table-active"> @lang('sales_car.action') </th> -->
        </tr>
        <?php 
            $total_sum = 0;
            $total_disc_sum = 0;
            $total_sum_vat = 0;
            $total_sum_net = 0;
            $item_row_count = 0;
        ?>
        
        @foreach($sales->details as $key => $d)
            <?php 
                $total_sum = $total_sum + floatval($d->store_vou_item_total_price);
                $total_disc_sum = $total_disc_sum + floatval($d->store_vou_disc_amount);
                $total_sum_vat = $total_sum_vat + $d->store_vou_vat_amount;
                $total_sum_net = $total_sum_net + floatval($d->store_vou_price_net);
                $item_row_count =  floatval($item_row_count) + 1;
            ?>
            <tr id="{{ $d->uuid }}">
                <td class="ctd"> {{ $key + 1 }} </td>
                <td class="ctd"> {{$d->brand->getName()}}  </td>
                <td class="ctd">  {{$d->brandDT->getBrandName()}}  </td>
                <td class="ctd"> {{ $d->store_vou_qnt_p }} </td>
                <td class="ctd"> {{ $d->store_vou_item_price_unit }} </td>
                <td class="ctd"> {{ $d->store_vou_item_total_price}}  </td>

                <!-- <td class="ctd"> {{ optional($d->discType)->getSysCodeName()  }}  </td>
                <td class="ctd"> {{ $d->store_voue_disc_value}}  </td>
                <td class="ctd"> {{ $d->store_vou_disc_amount}}  </td> -->

                <td class="ctd"> {{ $d->store_vou_vat_amount }} </td>
                <td class="ctd"> {{ $d->store_vou_price_net }} </td>
            </tr>
        @endforeach
        <input type="hidden" class="form-control" name="item_row_count" id="item_row_count" value="{{ $item_row_count }}" >
    </tbody>
    <tfoot>
        <tr>
            <td colspan="1" class="ctd table-active"> @lang('sales_car.total_amount') </td>
            <td colspan="2" class="ctd table-active"> <div id="total_sum_div"> {{$total_sum}} </div> </td>
            <!-- <td colspan="1" class="ctd table-active"> @lang('sales_car.total_disc_amount') </td>
            <td colspan="2" class="ctd table-active"> <div id="total_sum_vat_div"> {{ $total_disc_sum }} </div> </td> -->
            <td colspan="1" class="ctd table-active"> @lang('sales_car.total_vat_amount') </td>
            <td colspan="2" class="ctd table-active"> <div id="total_sum_vat_div"> {{$total_sum_vat}} </div> </td>
            <td colspan="1" class="ctd table-active" > @lang('sales_car.total_net_amount') </td>
            <td colspan="2" class="ctd table-active"> <div id="total_sum_net_div"> {{ $total_sum_net  }} </div> </td>
        </tr>
    </tfoot>
</table>










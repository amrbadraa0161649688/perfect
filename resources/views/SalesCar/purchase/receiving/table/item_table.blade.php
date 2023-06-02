<table class="table table-bordered card_table" id="item_table">
    <tbody >
        <tr >
            <th colspan="13" style="text-align: center;background-color: #113f50;color: white;">
                 @lang('sales_car.items') 
            </th>
        </tr>
        <tr>
            <th class="ctd table-active">No</th>
            <th class="ctd table-active" style="width:10%">  رقم الشاسي </th>
            <th class="ctd table-active fitwidth"> @lang('sales_car.brand') </th>
            <th class="ctd table-active fitwidth"> @lang('sales_car.brand_dt') </th>
            <th class="ctd table-active" style="width:10%"> الوصف </th>
            <th class="ctd table-active">الكمية </th>
            <th class="ctd table-active">سعر الوحدة</th>
            <th class="ctd table-active" style="width:10%">  رسوم اضافية </th>
            <th class="ctd table-active">الاجمالي</th>

           
           
            

            <th class="ctd table-active">الضريبة</th>
            <th class="ctd table-active"> الاجمالي الصافي</th>
            <!-- <th class="ctd table-active">Action</th> -->
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
                <td class="ctd"> {{ optional($d->car)->sales_cars_chasie_no  }}  </td>
                <td class="ctd"> {{$d->brand->getName()}}  </td>
                <td class="ctd">  {{$d->brandDT->getBrandName()}}  </td>
                <td class="ctd"> {{ optional($d->car)->sales_cars_desc }}  </td>
                @if($d->storeVouType->system_code =='104003')
                    <td class="ctd"> {{ $d->store_vou_qnt_i }} </td>
                @else
                    <td class="ctd"> {{ $d->store_vou_qnt_t_i }} </td>
                @endif
                <td class="ctd"> {{ $d->store_vou_item_price_unit }} </td>
                <td class="ctd"> {{ optional($d->car)->sales_cars_add_amount}}  </td>
                <td class="ctd"> {{ $d->store_vou_item_total_price}}  </td>
                
                <td class="ctd"> {{ $d->store_vou_vat_amount }} </td>
                <td class="ctd"> {{ $d->store_vou_price_net }} </td>
                <!-- <td class="ctd"> {{ $d->mntns_cards_vat_amount }} </td> -->
                <!-- <td class="ctd"> {{ $d->mntns_cards_amount }} </td> -->
                <!-- <td class="ctd"> {{ $d->mntns_cards_amount }} </td> -->
                <!-- <td class="ctd"> 
                    <button type="button"  class="btn btn-danger m-btn m-btn--icon m-btn--icon-only" onclick="deleteItem('{{ $d->uuid }}')"><i class="fa fa-trash"></i></button>
                </td> -->
            </tr>
        @endforeach
        <input type="hidden" class="form-control" name="item_row_count" id="item_row_count" value="{{ $item_row_count }}" >
    </tbody>
    <tfoot>
        <tr>
            <td colspan="1" class="ctd table-active">  @lang('sales_car.total_amount') </td>
            <td colspan="1" class="ctd table-active"> <div id="total_sum_div"> {{$total_sum}} </div> </td>
            <td colspan="1" class="ctd table-active">  @lang('sales_car.total_disc_amount') </td>
            <td colspan="2" class="ctd table-active"> <div id="total_sum_vat_div"> {{ $total_disc_sum }} </div> </td>
           <td colspan="1" class="ctd table-active">  @lang('sales_car.total_vat_amount') </td>
            <td colspan="2" class="ctd table-active"> <div id="total_sum_vat_div"> {{$total_sum_vat}} </div> </td>
            <td colspan="1" class="ctd table-active" >  @lang('sales_car.total_net_amount') </td>
            <td colspan="2" class="ctd table-active"> <div id="total_sum_net_div"> {{ $total_sum_net  }} </div> </td>
        </tr>
    </tfoot>
</table>










<style type="text/css">
    .ctd{
        text-align: center;
    }
</style>
<div class="table-responsive" style="height:300px;">
    
    <input type="hidden" class="form-control" name="source_branch" id="source_branch" value="{{ $sales_request->branch_id }}" readonly>
    <table class="table table-bordered card_table" id="item_table" style="width:220%">
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
                <th class="ctd table-active" width="7%"> @lang('sales_car.car_chasie_no') </th>
                <th class="ctd table-active" width="5%"> @lang('sales_car.car_plate_no') </th>
                <th class="ctd table-active" width="5%"> @lang('sales_car.brand') </th>
                <th class="ctd table-active" width="5%"> @lang('sales_car.brand_dt') </th>
                <th class="ctd table-active" width="5%"> اللون </th>
                <th class="ctd table-active" width="5%">  الموديل </th>
                <th class="ctd table-active" width="5%"> سعر البيع </th>
                <th class="ctd table-active" width="5%"> الوصف </th>
                <th class="ctd table-active" width="5%"> @lang('sales_car.qty') </th>
                <th class="ctd table-active" width="5%">الكمية المحولة</th>
                <th class="ctd table-active" width="5%"> المتبقي  </th>
                <th class="ctd table-active" width="5%"> @lang('sales_car.unit_price') </th>
                <th class="ctd table-active" width="5%"> رسوم اضافية</th>
                <th class="ctd table-active" width="5%"> @lang('sales_car.total') </th>
                <th class="ctd table-active" width="4%"> @lang('sales_car.disc_type') </th>
                <th class="ctd table-active" width="5%"> @lang('sales_car.disc') </th>
                <th class="ctd table-active" width="5%"> @lang('sales_car.total_disc') </th>
                <th class="ctd table-active" width="5%"> @lang('sales_car.vat') </th>
                <th class="ctd table-active" width="5%">  @lang('sales_car.net_amount') </th>
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
            
            @if($sales_request->store_vou_ref_before == null)
                @include('salesCar.purchase.receiving.table.direct_trans_row')
            @else
                @include('salesCar.purchase.receiving.table.request_row')
            @endif
            
                    
                
            <input type="hidden" class="form-control" name="item_row_count" id="item_row_count" value="{{ $item_row_count }}" >
           
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="ctd table-active"> @lang('sales_car.total_amount') </td>
                <td colspan="3" class="ctd table-active"> <div id="total_sum_div"> 0</div> </td>

                <td colspan="2" class="ctd table-active"> @lang('sales_car.total_disc_amount') </td>
                <td colspan="3" class="ctd table-active"> <div id="total_disc_div"> 0 </div> </td>

               <td colspan="2" class="ctd table-active"> @lang('sales_car.total_vat_amount') </td>
                <td colspan="3" class="ctd table-active"> <div id="total_sum_vat_div"> 0 </div> </td>

                <td colspan="2" class="ctd table-active" > @lang('sales_car.total_net_amount') </td>
                <td colspan="3" class="ctd table-active"> <div id="total_sum_net_div"> 0 </div> </td>
            </tr>
        </tfoot>
    </table>
</div>










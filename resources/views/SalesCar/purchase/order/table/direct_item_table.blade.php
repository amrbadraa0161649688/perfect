<table class="table table-bordered card_table" id="item_table">
    <tbody >
        <tr >
            <th colspan="13" style="text-align: center;background-color: #113f50;color: white;">
                @lang('sales_car.items') 
            </th>
        </tr>
        <tr>
            <th colspan="9">
                <div class="col-md-12">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label"> @lang('sales_car.brand') </label>
                                <select class="selectpicker show-tick form-control"  data-live-search="true" name="brand_id" id="brand_id"  >
                                    <option value="" selected> choose</option> 
                                    @foreach($car_brand as $brand)    
                                    <option value="{{$brand->brand_id}}"  data-brand="{{$brand}}"  data-brandname="{{$brand->getName()}}" > {{$brand->getName()}}  </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="recipient-name" class="col-form-label">  @lang('sales_car.brand_dt')  </label>
                                <select class="form-select form-control car" name="brand_dt" id="brand_dt" data-live-search="true" required>
                                    <option value="" selected> choose</option>  
                                </select>
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
        <tr >
            <th class="ctd table-active">No</th>
            <th class="ctd table-active" style="width:15%"> @lang('sales_car.brand') </th>
            <th class="ctd table-active" style="width:15%"> @lang('sales_car.brand_dt') </th>
            <th class="ctd table-active"> @lang('sales_car.qty') </th>
            <th class="ctd table-active"> @lang('sales_car.unit_price')</th>
            <th class="ctd table-active"> @lang('sales_car.total') </th>

            <!-- <th class="ctd table-active" style="width:10%"> @lang('sales_car.disc_type') </th>
            <th class="ctd table-active" style="width:10%"> @lang('sales_car.disc') </th>
            <th class="ctd table-active" style="width:10%"> @lang('sales_car.total_disc') </th> -->

            <th class="ctd table-active"> @lang('sales_car.vat') </th>
            <th class="ctd table-active"> @lang('sales_car.net_amount') </th>
            <th class="ctd table-active"> @lang('sales_car.action') </th>
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
                <td class="ctd"> 
                    <button type="button" id="btnSave[]" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only" onclick="deleteItem('{{$d->uuid}}')"><i class="fa fa-trash"></i>
                </button> </td>
                
            </tr>
        @endforeach
        <input type="hidden" class="form-control" name="item_row_count" id="item_row_count" value="{{ $item_row_count }}" >
    </tbody>
    <tfoot>
        <tr>
            <td colspan="1" class="ctd table-active"> @lang('sales_car.total_amount') </td>
            <td colspan="1" class="ctd table-active"> <div id="total_sum_div"> {{$total_sum}} </div> </td>
            <td colspan="1" class="ctd table-active"> @lang('sales_car.total_disc_amount') </td>
            <td colspan="1" class="ctd table-active"> <div id="total_sum_vat_div"> {{ $total_disc_sum }} </div> </td>
           <td colspan="1" class="ctd table-active"> @lang('sales_car.total_vat_amount') </td>
            <td colspan="1" class="ctd table-active"> <div id="total_sum_vat_div"> {{$total_sum_vat}} </div> </td>
            <td colspan="1" class="ctd table-active" > @lang('sales_car.total_net_amount') </td>
            <td colspan="2" class="ctd table-active"> <div id="total_sum_net_div"> {{ $total_sum_net  }} </div> </td>
        </tr>
    </tfoot>
</table>










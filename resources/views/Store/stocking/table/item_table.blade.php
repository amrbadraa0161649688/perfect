<table class="table table-bordered card_table" id="item_table">
    <tbody >
        <tr>
            <th colspan="10">
                <div class="col-md-12">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label">  الكمية </label>
                                <input type="number" class="form-control" name="store_vou_qnt" id="store_vou_qnt" value="1">
                            </div>
                            <div class="col-md-4">
                                <label for="recipient-name" class="col-form-label">  كود الصنف </label>
                                <!-- <select class="selectpicker show-tick form-control"  data-live-search="true" name="item_id" id="item_id"  >
                                    <option value="" selected> choose</option> 
                                    @foreach($itemes as $item)    
                                    <option value="{{$item->item_id}}"  data-item="{{$item}}"  data-itemname="{{$item->item_name_e}}" data-balance="{{$item->item_balance}}"> {{$item->item_code}}  </option>
                                    @endforeach
                                </select> -->
                                <input type="text" class="form-control" name="item_id" id="item_id" value="">
                            </div>
                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label"> لااسم عربي  </label>
                                <input type="text" class="form-control" name="item_name_a" id="item_name_a" value="" readonly>
                            </div>

                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label"> لااسم عربي  </label>
                                <input type="text" class="form-control" name="item_name_e" id="item_name_e" value="" readonly>
                            </div>

                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label"> الموقع  </label>
                                <input type="text" class="form-control" name="item_location" id="item_location" value="" readonly>
                            </div>
                            
                            

                            <!-- <div class="col-md-1">
                                <label for="recipient-name" class="col-form-label"> العمليات  </label>
                                <br>
                                <button onclick="saveItemRow()" class="btn btn-primary">
                                    <i class="fe fe-plus mr-2"></i> 
                                </button>
                            </div> -->
                            
                        </div>
                    </div>
                </div>
            </th>
        </tr>
        <tr>
            <th class="ctd table-active">No</th>
            <th class="ctd table-active" style="width:25%"> @lang('stocking.item_code')</th>
            <th class="ctd table-active" style="width:25%"> @lang('stocking.item')</th>
            <th class="ctd table-active"> @lang('stocking.location') </th>
            <th class="ctd table-active" style="width:15%"> @lang('stocking.stocking_qty') </th>
            <th class="ctd table-active"> @lang('stocking.action') </th>
        </tr>
        <?php 
            $item_row_count = 0;
        ?>
        <div id="showResult">
            @foreach($stocking->details()->orderBy('updated_date')->get() as $key => $d)
                <?php 
                    $item_row_count =  floatval($item_row_count) + 1;
                ?>
                <tr id="showResult">
                    <td class="ctd"> {{ $key + 1 }} </td>
                    <td class="ctd"> {{$d->item->item_code}}  </td>
                    <td class="ctd"> {{$d->item->item_name_e}} <br> {{$d->item->item_name_a}}  </td>
                    <td class="ctd"> 
                        <input type="text" class="form-control" name="stocking_item_location_{{ $d->uuid }}" id="stocking_item_location_{{ $d->uuid }}" value="{{$d->store_vou_loc}}">     
                    </td>
                    <td class="ctd"> 
                        <input type="text" class="form-control" name="stocking_qty_{{ $d->uuid }}" id="stocking_qty_{{ $d->uuid }}" value="{{ $d->store_vou_qnt }}">
                    </td>
                    <td class="ctd"> 
                        <button type="button"  class="btn  m-btn m-btn--icon m-btn--icon-only" onclick="updateQty('{{ $d->uuid }}')"><i class="fa fa-save"></i></button>
                        <button type="button"  class="btn  m-btn m-btn--icon m-btn--icon-only" onclick="deleteItem('{{ $d->uuid }}')"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            @endforeach
        </div>
        <input type="hidden" class="form-control" name="item_row_count" id="item_row_count" value="{{ $item_row_count }}" >
    </tbody>
</table>










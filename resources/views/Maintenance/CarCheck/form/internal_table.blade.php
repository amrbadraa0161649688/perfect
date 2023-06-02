<table class="table table-bordered card_table" id="internal_maintenance_table">
    <tbody >
        <tr >
            <th colspan="10" style="text-align: center;background-color: #113f50;color: white;">
            @lang('maintenanceType.mntns_internal')
            </th>
        </tr>
        @if(in_array($card->mntns_cards_status,$can_edit)  || $card->mntns_cards_status == $can_close)
        <tr>
            <th colspan="10">
                <div class="col-md-12">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label">   @lang('maintenanceType.mntns_type')  </label>
                                <select class="selectpicker show-tick form-control"  data-live-search="true" name="mntns_cards_item_id" id="mntns_cards_item_id"  >
                                    <option value="" selected> choose</option> 
                                    @foreach(App\Models\MaintenanceType::where('mntns_card_type','=',$card->mntns_cards_type)->get() as $mt)    
                                    <option value="{{$mt->mntns_type_id}}" data-mntnstypehours="{{$mt->mntns_type_hours}}" data-mntnstypeempno="{{$mt->mntns_type_emp_no}}" data-mntnstypevalue="{{$mt->mntns_type_value}}" data-mntnscardsitem ="{{$mt->typeCat->getSysCodeName()}} - {{$mt->getMaintenanceTypeName()}}" > {{$mt->typeCat->getSysCodeName()}} - {{$mt->getMaintenanceTypeName()}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label">  @lang('maintenanceType.value')  </label>
                                <input type="text" class="form-control" name="mntns_type_value" id="mntns_type_value" value="" readonly>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label"> @lang('maintenanceType.disc_type')  </label>
                                <select class="form-select form-control" name="mntns_cards_item_disc_type" id="mntns_cards_item_disc_type" data-live-search="true">
                                    <!-- <option value="" selected> choose</option>  -->
                                    @foreach($mntns_cards_item_disc_type as $mctdt)
                                    <option value="{{ $mctdt->system_code_id }}" data-mntnsdisctype="{{ $mctdt->getSysCodeName() }}"> {{ $mctdt->getSysCodeName() }}</option> 
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="recipient-name" class="col-form-label"> @lang('maintenanceType.disc')   </label>
                                <input type="number" class="form-control" name="mntns_cards_item_disc_amount" id="mntns_cards_item_disc_amount" value="0" >
                                <input type="hidden" class="form-control" name="mntns_cards_item_disc_value" id="mntns_cards_item_disc_value" value="0" >
                            </div>
                            <div class="col-md-4 row">
                                <div class="col-md-5">
                                    <label for="recipient-name" class="col-form-label"> @lang('maintenanceType.vat')  </label>
                                    <input type="number" class="form-control" name="vat_value" id="vat_value" value="" readonly>
                                </div>
                                <div class="col-md-5">
                                    <label for="recipient-name" class="col-form-label"> @lang('maintenanceType.total')  </label>
                                    <input type="number" class="form-control" name="total_after_vat" id="total_after_vat" value="" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label for="recipient-name" class="col-form-label">  @lang('maintenanceType.mntns_b_add')  </label>
                                    @if($card->mntns_cards_status == $can_inv)
                                    @else   
                                    <button onclick="saveInternalRow()" class="btn btn-primary">
                                        <i class="fe fe-plus mr-2"></i> 
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </th>
        </tr>
        @endif
        <tr>
            <th class="ctd table-active">No</th>
            <th class="ctd table-active" style="width:25%"> @lang('maintenanceType.mntns_type') </th>
            <th class="ctd table-active"> @lang('maintenanceType.mntns_hours')</th>
            <th class="ctd table-active"> @lang('maintenanceType.value') </th>
            <th class="ctd table-active"> @lang('maintenanceType.disc_type') </th>
            <th class="ctd table-active"> @lang('maintenanceType.disc')  </th>
            <th class="ctd table-active">@lang('maintenanceType.vat')</th>
            <th class="ctd table-active">@lang('maintenanceType.total') </th>
            <th class="ctd table-active">Action</th>
           
        </tr>
        <?php 
            $internal_discount_total = 0;
            $internal_vat_total = 0;
            $internal_total = 0;
            $internal_row_count = 0;
        ?>
        @foreach($card->internalDetails as $key => $internal)
            <?php 
                $internal_discount_total = $internal_discount_total+ $internal->mntns_cards_disc_amount;
                $internal_vat_total = $internal_vat_total + $internal->mntns_cards_vat_amount;
                $internal_total = $internal_total + floatval($internal->mntns_cards_amount);
                $internal_row_count =  floatval($internal_row_count) + 1;
            ?>
            <tr id="{{ $internal->uuid }}">
                <td class="ctd"> {{ $key + 1 }} </td>
                <td class="ctd">  {{optional($internal->maintenanceType->typeCat)->getSysCodeName()}} - {{ optional($internal->maintenanceType)->getMaintenanceTypeName() }}  </td>
                <td class="ctd"> {{ $internal->mntns_cards_item_hours }} </td>
                <td class="ctd"> {{ $internal->mntns_cards_item_price }} </td>
                <td class="ctd"> {{ optional($internal->discType)->getSysCodeName()}}  </td>
                <td class="ctd"> {{ $internal->mntns_cards_disc_amount }} </td>
                <td class="ctd"> {{ $internal->mntns_cards_vat_amount }} </td>
                <td class="ctd"> {{ $internal->mntns_cards_amount }} </td>
                <td class="ctd"> 
                @if(in_array($card->mntns_cards_status,$can_edit))
                    <button type="button"  class="btn btn-primary m-btn m-btn--icon m-btn--icon-only" onclick="addTech('{{ $internal->uuid }}')"><i class="fa fa-user-plus"></i></button>
                    <button type="button"  class="btn btn-danger m-btn m-btn--icon m-btn--icon-only" onclick="deleteItem('{{ $internal->uuid }}')"><i class="fa fa-trash"></i></button>
                @endif
                </td>
            </tr>
        @endforeach
        <input type="hidden" class="form-control" name="internal_row_count" id="internal_row_count" value="{{ $internal_row_count }}" >
        <input type="hidden" class="form-control" name="internal_total_disc_amount" id="internal_total_disc_amount" value="{{ $internal_discount_total }}" >
        <input type="hidden" class="form-control" name="internal_total_vat_amount" id="internal_total_vat_amount" value="{{$internal_vat_total}} " >
        <input type="hidden" class="form-control" name="internal_total_amount" id="internal_total_amount" value="{{ $internal_total }}" >
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" class="ctd table-active"> @lang('maintenanceType.disc')</td>
            <td colspan="1" class="ctd table-active"> <div id="internal_total_disc_amount_div"> {{ $internal_discount_total }} </div></td>
            <td colspan="2" class="ctd table-active">@lang('maintenanceType.vat')</td>
            <td colspan="1" class="ctd table-active"> <div id="internal_total_vat_amount_div"> {{$internal_vat_total}} </div> </td>
            <td colspan="2" class="ctd table-active">@lang('maintenanceType.total')</td>
            <td colspan="1" class="ctd table-active"> <div id="internal_total_amount_div"> {{ $internal_total }} </div> </td>
        </tr>
        <tr>
            
        </tr>
        <tr>
            
        </tr>
    </tfoot>
</table>







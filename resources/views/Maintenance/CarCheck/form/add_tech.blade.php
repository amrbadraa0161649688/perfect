<div class="modal-body">
    <form id="tech_data_form"  enctype="multipart/form-data">
        @csrf  
        <table class="table table-bordered card_table" id="tech_maintenance_table">
            <tbody >
                <tr >
                    <th colspan="10" style="text-align: center;background-color: #113f50;color: white;">
                    @lang('maintenanceType.mntns_tech')
                    </th>
                </tr>
                <tr>
                    <th colspan="10">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="recipient-name" class="col-form-label">  @lang('maintenanceType.mntns_type')  </label>
                                        <input type="text" class="form-control" name="mntns_cards_dt" id="mntns_cards_dt" value="{{ $maintenance_card_details->maintenanceType->getMaintenanceTypeName() }}" readonly>
                                        <input type="hidden" class="form-control" name="mntns_cards_dt_id" id="mntns_cards_dt_id" value="{{ $maintenance_card_details->mntns_cards_dt_id }}" readonly>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="recipient-name" class="col-form-label">  @lang('maintenanceType.mntns_tech')  </label>
                                        <select class="form-select form-control" name="mntns_tech_emp_id" id="mntns_tech_emp_id" data-live-search="true">
                                            <option value="" selected> choose</option>
                                            @foreach($tech_list as $tech)
                                                <option value="{{$tech->emp_id}}" data-name="{{ $tech->emp_name_full_ar }}"> {{ $tech->emp_name_full_ar }} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="recipient-name" class="col-form-label">  @lang('maintenanceType.mntns_hours') </label>
                                        <input type="text" class="form-control" name="mntns_tech_hours" id="mntns_tech_hours" value="" >
                                    </div>
                                    <div class="col-md-2">
                                        <div class="col-md-2">
                                            <label for="recipient-name" class="col-form-label"> @lang('maintenanceType.mntns_b_add')  </label>
                                            <button type="button" onclick="saveTechRow()" class="btn btn-primary">
                                                <i class="fe fe-plus mr-2"></i> 
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th class="ctd table-active">No</th>
                    <th class="ctd table-active" style="width:50%"> @lang('maintenanceType.mntns_tech')  </th>
                    <th class="ctd table-active"> @lang('maintenanceType.mntns_hours')  </th>
                    
                </tr>
                <?php 
                    
                    $tech_row_count = 0;
                ?>
                @foreach($maintenance_card_details->tech as $key => $tech)   
                <?php 
                   
                    $tech_row_count =  floatval($tech_row_count) + 1;
                ?>
                <tr id="">
                    <td class="ctd"> {{$key +1}}  </td>
                    <td class="ctd"> {{ $tech->emp->emp_name_full_ar}}   </td>
                    <td class="ctd"> {{ $tech->mntns_tech_hours }}</td>
                </tr>
                @endforeach
                <input type="hidden" class="form-control" name="tech_row_count" id="tech_row_count" value="{{ $tech_row_count }}" >
            </tbody>
        </table>
        
    </form>
</div>
   
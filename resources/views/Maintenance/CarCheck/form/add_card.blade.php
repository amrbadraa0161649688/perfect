<div id="add_card_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width:130%">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" onclick="closeItemModal()">&times;</button>
                <h4 class="modal-title" style="text-align:left">@lang('maintenanceType.m_new_card')</h4>
            </div>

            <div class="modal-body">
                <form id="card_data_form"  enctype="multipart/form-data">
                    @csrf  
                    <div class="col-md-12">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label ">  @lang('maintenanceType.mntns_card_types') </label>
                                    <select class="form-select form-control is-invalid" name="mntns_cards_type" id="mntns_cards_type" required>
                                        <option value="" selected> choose</option>  
                                        @foreach($mntns_cards_type as $mct)
                                        <option value="{{$mct->system_code_id}}" > {{ $mct->getSysCodeName() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label"> @lang('maintenanceType.mntns_type') </label>
                                    <select class="form-select form-control is-invalid" name="mntns_cards_category" id="mntns_cards_category" required>
                                        <option value="" selected> choose</option>  
                                        @foreach($mntns_cards_category as $mcc)
                                        <option value="{{$mcc->system_code_id}}" > {{ $mcc->getSysCodeName() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label"> @lang('maintenanceType.mntns_card_cus_type') </label>
                                    <select class="form-select form-control is-invalid" name="mntns_cards_customer_type" id="mntns_cards_customer_type" required>
                                        <option value="" selected> choose</option>    
                                        @foreach($cards_customer_type as $customer_type)
                                        <option value="{{$customer_type->system_code_id}}"> {{ $customer_type->getSysCodeName() }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('maintenanceType.mntns_card_customer') </label>
                                    <select class="selectpicker show-tick form-control customer is-invalid"  data-live-search="true" name="customer_id" id="customer_id" required>
                                        <option value="" selected> choose</option>  
                                    </select>
                                </div>
                               
                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label">  @lang('maintenanceType.mntns_card_plate') </label>
                                    <select class="selectpicker show-tick form-control car is-invalid" name="mntns_cars_id" id="mntns_cars_id" data-live-search="true" required>
                                        <option value="" selected> choose</option>  
                                    </select>

                                        
                                </div>
                                <div class="col-md-4">
                                    <br>
                                    <br>
                                    @if(auth()->user()->user_type_id != 1)
                                    @foreach(session('job')->permissions as $job_permission)
                                        @if($job_permission->app_menu_id == 59 && $job_permission->permission_add)
                                            <a href="{{route('maintenance-car.create')}}"  target="_blank" class="btn btn-primary">
                                                <i class="fe fe-plus mr-2"></i> @lang('maintenanceType.mntns_new_car')
                                            </a>
                                        @endif
                                    @endforeach

                                    @else
                                    <a href="{{route('maintenance-car.create')}}"  target="_blank" class="btn btn-primary">
                                                <i class="fe fe-plus mr-2"></i> @lang('maintenanceType.mntns_new_car')
                                            </a>
                                    @endif

                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('maintenanceType.mntns_counter_car') </label>
                                    <input type="number" class="form-control is-invalid" name="mntns_cars_meter" id="mntns_cars_meter" value="">
                                </div>
                                <div class="col-md-8">
                                    <label for="recipient-name" class="col-form-label"> @lang('home.notes') </label>
                                    <textarea rows="2" class="form-control" name="mntns_cards_notes" id="mntns_cards_notes" placeholder="Here can be your note" value=""></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                    <button type="button" id="btnCancel" class="btn btn-secondary btn-block" onclick="closeItemModal()"> @lang('maintenanceType.mntns_b_cance')</button>
                </div>
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">	
                    <button type="button" id="btnSave" class="btn btn-success forward btn-block" onclick="saveCard()"> @lang('maintenanceType.mntns_b_add')</button>
                </div>		
            </div>
        </div>
    </div>
</div>
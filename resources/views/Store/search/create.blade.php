<div id="add_item_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width:130%">
            <div class="modal-header">
                <h4 class="modal-title" style="text-align:right">
                    @lang('storeItem.add_new_item')
                </h4>
                <div style="text-align:left">
                    <button type="button" class="close" data-dismiss="modal" onclick="closeItemModal()" >&times;</button>
                </div>
            </div>

            <div class="modal-body">
                <form id="add_item_data_form"  enctype="multipart/form-data">
                    @csrf  
                    <input type="hidden" class="form-control" name="company_id_m" id="company_id_m" value="{{ auth()->user()->company->company_id }}"> 
                    <div class="col-md-12">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-4"> 
                                    <label for="recipient-name" class="col-form-label "> @lang('storeItem.company')   </label>
                                    <select class="form-select form-control" name="company_id_m" id="company_id_m" disabled >
                                        <option value="auth()->user()->company->company_id" selected> {{ auth()->user()->company->getCompanyName()}}</option>    
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('storeItem.item_category') </label>
                                    <select class="form-select form-control is-invalid" name="item_category_m" id="item_category_m" required>
                                        <option value="" selected> choose</option>    
                                        @foreach($warehouses_type_lits as $warehouses_type)
                                            <option value="{{$warehouses_type->system_code}}"  >
                                                    {{ $warehouses_type->getSysCodeName() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('storeItem.branch')   </label>
                                    <select class="form-select form-control is-invalid" name="branch_id_m" id="branch_id_m" required>
                                        <option value="" selected> choose</option>    
                                        @foreach($branch_list as $branch)
                                            <option value="{{$branch->branch_id}}">
                                                {{ $branch->getBranchName() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('storeItem.item_code') </label>
                                    <input type="text" class="form-control is-invalid" name="item_code_m" id="item_code_m" value="" oninput="this.value=this.value.replace(/[^A-Za-z-0-9\s]/g,' ');" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('storeItem.item_name_a')  </label>
                                    <input type="text" class="form-control is-invalid" name="item_name_a_m" id="item_name_a_m"  oninput="this.value=this.value.replace(/[^ء-ي\s]/g,' ');"
                                                    required>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label ">  @lang('storeItem.item_name_e')   </label>
                                    <input type="text" class="form-control is-invalid" name="item_name_e_m" id="item_name_e_m" value="" oninput="this.value=this.value.replace(/[^A-Za-z-0-9\s]/g,' ');" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label ">  @lang('storeItem.item_vendor_code')   </label>
                                    <input type="text" class="form-control is-invalid" name="item_vendor_code_m" id="item_vendor_code_m" value="" oninput="this.value=this.value.replace(/[^A-Za-z-0-9\s]/g,' ');" required>
                                </div>

                                

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label ">  @lang('storeItem.item_location')   </label>
                                    <input type="text" class="form-control is-invalid" name="item_location_m" id="item_location_m" value="">
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label ">  @lang('storeItem.item_unit')   </label>
                                    <select class="form-select form-control is-invalid" name="item_unit_m" id="item_unit_m" required>
                                        <option value="" selected> choose</option>    
                                        @foreach($unit_lits as $unit)
                                        <option value="{{$unit->system_code}}"> {{ $unit->getSysCodeName() }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label">  @lang('storeItem.item_code_1')  </label>
                                    <input type="text" class="form-control is-invalid" name="item_code_1_m" id="item_code_1_m" oninput="this.value=this.value.replace(/[^A-Za-z-0-9\s]/g,' ');" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label"> @lang('storeItem.item_code_2') </label>
                                    <input type="text" class="form-control is-invalid" name="item_code_2_m" id="item_code_2_m" oninput="this.value=this.value.replace(/[^A-Za-z-0-9\s]/g,' ');" required>
                                </div>

                                <!-- <div class="col-md-4">
                                    
                                    <label for="recipient-name" class="col-form-label">  @lang('storeItem.item_price_sales')  </label>
                                    <input type="number" class="form-control is-invalid" name="item_price_sales" id="item_price_sales"  oninput="this.value=this.value.replace( /[^0.01-9.01]/g,' ');" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label">  @lang('storeItem.item_price_cost')  </label>
                                    <input type="number" class="form-control is-invalid" name="item_price_cost" id="item_price_cost"  oninput="this.value=this.value.replace( /[^0.01-9.01]/g,' ');" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label">  @lang('storeItem.item_balance')  </label>
                                    <input type="number" class="form-control is-invalid" name="item_balance" id="item_balance"  oninput="this.value=this.value.replace( /[^0.01-9.01]/g,' ');" required>
                                </div> -->

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label">   @lang('storeItem.item_desc')  </label>
                                    <textarea rows="2" class="form-control" name="item_desc_m" id="item_desc_m" placeholder="Here can be your note" value=""></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                    <button type="button" id="btnCancel" class="btn btn-secondary btn-block" onclick="closeAddItemModal()">  @lang('storeItem.cancel_button') </button>
                </div>
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">	
                    <button type="button" id="btnSave" class="btn btn-success forward btn-block" onclick="saveItem()">  @lang('storeItem.add_button')  </button>
                </div>		
            </div>
        </div>
    </div>
</div>


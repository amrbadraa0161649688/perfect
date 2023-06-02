<div id="add_item_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="text-align:right">
                    @lang('storeItem.add_new_item')
                </h4>
                <div style="text-align:left">
                    <button type="button" class="close" data-dismiss="modal" onclick="closeItemModal()" >&times;</button>
                </div>
            </div>

            <div class="modal-body">
                <form id="item_data_form"  enctype="multipart/form-data">
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
                                    <label class="col-form-label"> @lang('storeItem.branch')  </label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="branch_id_m[]" id="branch_id_m" data-actions-box="true">
                                        @foreach($branch_list as $branch)
                                            <option value="{{$branch->branch_id}}">
                                            {{ $branch->getBranchName() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                              <!--  <div class="col-md-4">
                                    <label> @lang('storeItem.branch')  </label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="branch_id_m[]" id="branch_id_m" data-actions-box="true">
                                        @foreach($branch_list as $branch)
                                            <option value="{{$branch->branch_id}}">
                                            {{ $branch->getBranchName() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>-->

                                <!-- <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('storeItem.branch')   </label>
                                    <select class="form-select form-control is-invalid" name="branch_id_m" id="branch_id_m" required>
                                        <option value="" selected> choose</option>    
                                        @foreach($branch_list as $branch)
                                            <option value="{{$branch->branch_id}}">
                                                {{ $branch->getBranchName() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> -->

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('storeItem.item_code') </label>
                                    <input type="text" class="form-control is-invalid" name="item_code_m" id="item_code_m" value="" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('storeItem.item_name_a')  </label>
                                    <input type="text" class="form-control is-invalid" name="item_name_a_m" id="item_name_a_m" 
                                                    required>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label ">  @lang('storeItem.item_name_e')   </label>
                                    <input type="text" class="form-control is-invalid" name="item_name_e_m" id="item_name_e_m" value=""  required>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label ">  @lang('storeItem.item_vendor_code')   </label>
                                    <input type="text" class="form-control" name="item_vendor_code_m" id="item_vendor_code_m" value=""  required>
                                </div>

                                

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label ">  @lang('storeItem.item_location')   </label>
                                    <input type="text" class="form-control" name="item_location_m" id="item_location_m" value="">
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label ">  @lang('storeItem.item_unit')   </label>
                                    <select class="form-select form-control" name="item_unit_m" id="item_unit_m" required>
                                        <option value="" selected> choose</option>    
                                        @foreach($unit_lits as $unit)
                                        <option value="{{$unit->system_code}}" {{($unit->system_code == 93 ? 'selected' : '')}}> {{ $unit->getSysCodeName() }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label">  @lang('storeItem.item_code_1')  </label>
                                    <input type="text" class="form-control" name="item_code_1_m" id="item_code_1_m"  required>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label"> @lang('storeItem.item_code_2') </label>
                                    <input type="text" class="form-control" name="item_code_2_m" id="item_code_2_m"  required>
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
                    <button type="button" id="btnCancel" class="btn btn-secondary btn-block" onclick="closeItemModal()">  @lang('storeItem.cancel_button') </button>
                </div>
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">	
                    <button type="button" id="btnSave" class="btn btn-primary forward btn-block" onclick="saveItem()">  @lang('storeItem.add_button')  </button>
                </div>		
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $(document).ready(function () {
        $('#branch_id_m').selectpicker();
        $('#company_id_m').change(function () {
            if (!$('#company_id_m').val()) {
                $('#company_id_m').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#company_id_m').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });

        $('#item_category_m').change(function () {
            if (!$('#item_category_m').val()) {
                $('#item_category_m').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#item_category_m').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });

        $('#branch_id_m').change(function () {
            if (!$('#branch_id_m').val()) {
                $('#branch_id_m').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#branch_id_m').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });


        $('#item_code_m').keyup(function () {
            console.log('111');
            if ($('#item_code_m').val().length < 3) {
                $('#item_code_m').addClass('is-invalid')
            } else {
                $('#item_code_m').removeClass('is-invalid');
            }
        });

        $('#item_name_a_m').keyup(function () {
            if ($('#item_name_a_m').val().length < 3) {
                $('#item_name_a_m').addClass('is-invalid')
            } else {
                $('#item_name_a_m').removeClass('is-invalid');
            }
        });

        $('#item_name_e_m').keyup(function () {
            if ($('#item_name_e_m').val().length < 3) {
                $('#item_name_e_m').addClass('is-invalid')
            } else {
                $('#item_name_e_m').removeClass('is-invalid');
            }
        });

        // $('#item_vendor_code_m').keyup(function () {
        //     if ($('#item_vendor_code_m').val().length < 3) {
        //         $('#item_vendor_code_m').addClass('is-invalid')
        //     } else {
        //         $('#item_vendor_code_m').removeClass('is-invalid');
        //     }
        // });

        // $('#item_location_m').keyup(function () {
        //     if ($('#item_location_m').val().length < 3) {
        //         $('#item_location_m').addClass('is-invalid')
        //     } else {
        //         $('#item_location_m').removeClass('is-invalid');
        //     }
        // });

        // $('#item_code_1_m').keyup(function () {
        //     if ($('#item_code_1_m').val().length < 3) {
        //         $('#item_code_1_m').addClass('is-invalid')
        //     } else {
        //         $('#item_code_1_m').removeClass('is-invalid');
        //     }
        // });

        // $('#item_code_2_m').keyup(function () {
        //     if ($('#item_code_2_m').val().length < 3) {
        //         $('#item_code_2_m').addClass('is-invalid')
        //     } else {
        //         $('#item_code_2_m').removeClass('is-invalid');
        //     }
        // });

        $('#item_price_sales_m').keyup(function () {
            if ($('#item_price_sales_m').val().length < 1) {
                $('#item_price_sales_m').addClass('is-invalid')
            } else {
                $('#item_price_sales_m').removeClass('is-invalid');
            }
        });

        $('#item_price_cost_m').keyup(function () {
            if ($('#item_price_cost_m').val().length < 1) {
                $('#item_price_cost_m').addClass('is-invalid')
            } else {
                $('#item_price_cost_m').removeClass('is-invalid');
            }
        });

        $('#item_balance_m').keyup(function () {
            if ($('#item_balance_m').val().length < 1) {
                $('#item_balance_m').addClass('is-invalid')
            } else {
                $('#item_balance_m').removeClass('is-invalid');
            }
        });

        $('#item_unit_m').change(function () {
            if (!$('#item_unit_m').val()) {
                $('#item_unit_m').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#item_unit_m').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });

        

        
    });
</script>
<div id="create_request_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width:130%">
            <div class="modal-header">
               
               
                <h4 class="modal-title" style="text-align:right">
                @lang('sales_car.add_request_button')  
                </h4>
                <div style="text-align:left">
                <button type="button" class="close" data-dismiss="modal" onclick="closeItemModal()">&times;</button>
                </div>
            </div>

            <div class="modal-body">
                <form id="request_data_form"  enctype="multipart/form-data">
                    @csrf  
                    <div class="col-md-12">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('sales_car.item_category') </label>
                                    <select class="form-select form-control is-invalid" name="store_category_type" id="store_category_type" required>
                                        <option value="" selected> choose</option>  
                                        @foreach($warehouses_type_list as $w_t)
                                        <option value="{{$w_t->system_code}}" > {{ $w_t->getSysCodeName() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label"> @lang('sales_car.vendor') </label>
                                    <select class="form-select form-control is-invalid" name="store_acc_no" id="store_acc_no" required>
                                        <option value="" selected> choose</option>  
                                        @foreach($vendor_list as $vendor)
                                        <option value="{{$vendor->customer_id}}" data-vendorname="{{ $vendor->getCustomerName() }}" data-vendorvat="{{ $vendor->customer_vat_no }}" > {{ $vendor->getCustomerName() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                               
                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('sales_car.vendor_name') </label>
                                    <input type="text" class="form-control is-invalid" name="store_acc_name" id="store_acc_name" value="">
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('sales_car.vat_no') </label>
                                    <input type="number" class="form-control is-invalid" name="store_acc_tax_no" id="store_acc_tax_no" value="">
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label"> @lang('sales_car.payment_method')  </label>
                                    <select class="form-select form-control is-invalid" name="store_vou_pay_type" id="store_vou_pay_type" required>
                                        <option value="" selected> choose</option>    
                                        @foreach($payemnt_method_list as $p_method)
                                        <option value="{{$p_method->system_code}}"> {{ $p_method->getSysCodeName() }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                
                                <div class="col-md-8">
                                    <label for="recipient-name" class="col-form-label">@lang('sales_car.note') </label>
                                    <textarea rows="2" class="form-control" name="store_vou_notes" id="store_vou_notes" placeholder="Here can be your note" value=""></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                    <button type="button" id="btnCancel" class="btn btn-secondary btn-block" onclick="closeItemModal()">الغاء</button>
                </div>
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">	
                    <button type="button" id="btnSave" class="btn btn-success forward btn-block" onclick="saveRequest()">اضافة </button>
                </div>		
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $(document).ready(function () {
        $('#store_acc_no').on('change',function(){
            $('#store_acc_name').val($('#store_acc_no :selected').data('vendorname'));
            $('#store_acc_name').removeClass('is-invalid');
            $('#store_acc_tax_no').val($('#store_acc_no :selected').data('vendorvat'));
            $('#store_acc_tax_no').removeClass('is-invalid');
        });

        $('#store_category_type').change(function () {
            if (!$('#store_category_type').val()) {
                $('#store_category_type').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#store_category_type').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });

        $('#store_acc_no').change(function () {
            if (!$('#store_acc_no').val()) {
                $('#store_acc_no').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#store_acc_no').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });

        $('#store_vou_pay_type').change(function () {
            if (!$('#store_vou_pay_type').val()) {
                $('#store_vou_pay_type').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#store_vou_pay_type').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });

        $('#store_acc_name').keyup(function () {
            if ($('#store_acc_name').val().length < 3) {
                $('#store_acc_name').addClass('is-invalid')
            } else {
                $('#store_acc_name').removeClass('is-invalid');
            }
        });

        $('#store_acc_tax_no').keyup(function () {
            if ($('#store_acc_tax_no').val().length < 3) {
                $('#store_acc_tax_no').addClass('is-invalid')
            } else {
                $('#store_acc_tax_no').removeClass('is-invalid');
            }
        });

    });

    function saveRequest()
        {
            if($('.is-invalid').length > 0){
                return toastr.warning('تاكد من ادخال كافة الحقول');
            }
            url = '{{ route('sales-car-request.store') }}'
            var form = new FormData($('#request_data_form')[0]);
			form.append('company_id', $('#company_id').val());
            form.append('warehouses_type', $('#warehouses_type').val());
            form.append('branch_id', $('#branch_id').val());

			var data = form  ; 
			$.ajax({
				type: 'POST',
				url : url,
				data: data,
				cache: false,
				contentType: false,
				processData: false,
				
			}).done(function(data){
				if(data.success)
				{
                    toastr.success(data.msg);
                    url =  '{{ route("sales-car-request.edit", ":id") }}';
                    url = url.replace(':id',data.uuid);
                    window.location.href = url;
                    
				}
				else
				{
					toastr.warning(data.msg);
				}
			});
        }
</script>
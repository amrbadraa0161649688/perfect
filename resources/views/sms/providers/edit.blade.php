
        <div class="modal-content" style="width:180%">
            <div class="modal-header">
                <h4 class="modal-title" style="text-align:right">
                    @lang('sms.add_new_provider')
                </h4>
                <div style="text-align:left">
                    <button type="button" class="close" data-dismiss="modal" >&times;</button>
                </div>
            </div>
            
            <div class="modal-body">
                <form id="providers_data_update_form"  enctype="multipart/form-data">
                    @csrf  
                    <input type="hidden" class="form-control " name="uuid" id="uuid" value="{{$provider->uuid}}">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-4"> 
                                    <label for="recipient-name" class="col-form-label "> @lang('sms.company')   </label>
                                    <select class="form-select form-control " name="company_id_m_e" id="company_id_m_e">
                                        @foreach($companies as $company)
                                            <option value="{{$company->company_id}}" @if( $provider->company_id == $company->company_id) selected @endif>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$company->company_name_ar}}
                                                @else
                                                    {{$company->company_name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('sms.provider_name') </label>
                                    <input type="text" class="form-control " name="sms_provider_name_e" id="sms_provider_name_e" value="{{$provider->sms_provider_name}}"  oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'');" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('sms.provider_phone')  </label>
                                    <input type="number" class="form-control " name="sms_provider_phone_e" id="sms_provider_phone_e" value="{{$provider->sms_provider_phone}}"
                                    oninput="this.value=this.value.replace( /[^0-9]/g,'');" required>
                                </div>

                               
                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label ">  @lang('sms.account_sid')   </label>
                                    <input type="text" class="form-control " name="account_sid_e" id="account_sid_e" value="{{$provider->account_sid}}" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'');" required>
                                </div>

                               
                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label">  @lang('sms.account_user_name')  </label>
                                    <input type="text" class="form-control " name="account_user_name_e" id="account_user_name_e" value="{{$provider->account_user_name}}" oninput="this.value=this.value.replace(/[^A-Za-z-0-9\s]/g,'');" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label"> @lang('sms.account_password') </label>
                                    <input type="text" class="form-control " name="account_password_e" id="account_password_e" value="{{$provider->account_password}}" required>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                    <button type="button" id="btnCancel" class="btn btn-secondary btn-block" data-dismiss="modal">  @lang('sms.cancel_button') </button>
                </div>
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">	
                    <button type="button" id="btnSave" class="btn btn-success forward btn-block" onclick="updateProvider()">  @lang('sms.add_button')  </button>
                </div>		
            </div>
        </div>
    

<script type="text/javascript">
    
    $(document).ready(function () {
        
        $('#company_id_m').change(function () {
            if ($('#company_id_m').val() == '') {
                $('#company_id_m').addClass('is-invalid');
            } else {
                $('#company_id_m').removeClass('is-invalid');
            }
        });

        $('#sms_provider_name_e').keyup(function () {
            if ($('#sms_provider_name_e').val().length < 3) {
                $('#sms_provider_name_e').addClass('is-invalid')
            } else {
                $('#sms_provider_name_e').removeClass('is-invalid');
            }
        });

        $('#sms_provider_phone_e').keyup(function () {
            if ($('#sms_provider_phone_e').val().length < 12) {
                $('#sms_provider_phone_e').addClass('is-invalid')
            } else {
                $('#sms_provider_phone_e').removeClass('is-invalid');
            }
        });

        $('#account_sid_e').keyup(function () {
            
            if ($('#account_sid_e').val().length < 3) {
                $('#account_sid_e').addClass('is-invalid');
            } else {
                $('#account_sid_e').removeClass('is-invalid');
            }
        });

        $('#account_user_name_e').keyup(function () {
            if ($('#account_user_name_e').val().length < 3) {
                $('#account_user_name_e').addClass('is-invalid')
            } else {
                $('#account_user_name_e').removeClass('is-invalid');
            }
        });

        $('#account_password_e').keyup(function () {
            if ($('#account_password_e').val().length < 3) {
                $('#account_password_e').addClass('is-invalid')
            } else {
                $('#account_password_e').removeClass('is-invalid');
            }
        });

    });

    function updateProvider()
        {
            if($('#edit_item_modal .is-invalid').length > 0){
                return toastr.warning('تاكد من ادخال كافة الحقول');
            }
            url = '{{ route('sms-providers.update') }}'
            var form = new FormData($('#providers_data_update_form')[0]);
			form.append('company_id', $('#company_id').val());

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
                    getData();
                    $('#edit_item_modal').on('hidden.bs.modal', function () {
                        //$('#edit_item_modal .modal-body').html('');
                    });
                    $('#edit_item_modal').modal('hide');
				}
				else
				{
					toastr.warning(data.msg);
				}
			});
        }

</script>
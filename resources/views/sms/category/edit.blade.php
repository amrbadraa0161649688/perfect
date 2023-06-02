
        <div class="modal-content" style="width:180%">
            <div class="modal-header">
                <h4 class="modal-title" style="text-align:right">
                    @lang('sms.add_new_sms')
                </h4>
                <div style="text-align:left">
                    <button type="button" class="close" data-dismiss="modal" >&times;</button>
                </div>
            </div>
             
            <div class="modal-body">
                <form id="category_data_update_form"  enctype="multipart/form-data">
                    @csrf  
                    <input type="hidden" class="form-control " name="uuid" id="uuid" value="{{$category->uuid}}">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-4"> 
                                    <label for="recipient-name" class="col-form-label "> @lang('sms.company')   </label>
                                    <select class="form-select form-control " name="company_id_m_e" id="company_id_m_e">
                                        @foreach($companies as $company)
                                            <option value="{{$company->company_id}}" @if( $category->company_id == $company->company_id) selected @endif>
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
                                    <select class="form-control" id="sms_provider_id_m" name="sms_provider_id_m">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($providers as $providers)
                                            <option value="{{$providers->sms_provider_id}}" @if( $category->sms_provider_id == $providers->sms_provider_id) selected @endif>
                                                {{$providers->sms_provider_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('sms.sms_category_type') </label>
                                    <select class="form-control" id="sms_category_type_m" name="sms_category_type_m">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($sms_category_type_lits as $sms_category_type)
                                            <option value="{{$sms_category_type->system_code}}" @if( $category->sms_category_type == $sms_category_type->system_code_id) selected @endif >
                                                {{$sms_category_type->getSysCodeName()}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="recipient-name" class="col-form-label "> @lang('sms.sms_name_ar')  </label>
                                    <input type="text" class="form-control " name="sms_name_ar" id="sms_name_ar"  value="{{$category->sms_name_ar}}"
                                    oninput="this.value=this.value.replace( /[^A-Za-z\s]/g,'');" required>
                                </div>


                                <div class="col-md-6">
                                    <label for="recipient-name" class="col-form-label "> @lang('sms.sms_name_en')  </label>
                                    <input type="text" class="form-control " name="sms_name_en" id="sms_name_en"  value="{{$category->sms_name_en}}"
                                    oninput="this.value=this.value.replace( /[^A-Za-z\s]/g,'');" required>
                                </div>

                               
                                <!-- <div class="col-md-6">
                                    <label for="recipient-name" class="col-form-label ">  @lang('sms.sms_body_ar')   </label>
                                    <textarea rows="2" class="form-control " name="sms_body_ar" id="sms_body_ar" placeholder="@lang('sms.sms_body_ar')" >{{$category->sms_body_ar}}</textarea>
                                </div>

                               
                                <div class="col-md-6">
                                    <label for="recipient-name" class="col-form-label">  @lang('sms.sms_body_en')  </label>
                                    <textarea rows="2" class="form-control " name="sms_body_en" id="sms_body_en" placeholder="@lang('sms.sms_body_en')" > {{$category->sms_body_en}}</textarea>
                                </div> -->


                                <div class="col-md-3">
                                    <label for="recipient-name" class="col-form-label ">  @lang('sms.sms_var_1')   </label>
                                    <textarea rows="2" class="form-control" name="sms_var_1" id="sms_var_1" placeholder="@lang('sms.sms_var_1')" value="">{{$category->sms_var_1}}</textarea>
                                </div>

                               
                                <div class="col-md-3">
                                    <label for="recipient-name" class="col-form-label">  @lang('sms.sms_var_2')  </label>
                                    <textarea rows="2" class="form-control" name="sms_var_2" id="sms_var_2" placeholder="@lang('sms.sms_var_2')" value="">{{$category->sms_var_2}}</textarea>
                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name" class="col-form-label ">  @lang('sms.sms_var_3')   </label>
                                    <textarea rows="2" class="form-control" name="sms_var_3" id="sms_var_3" placeholder="@lang('sms.sms_var_3')" value="">{{$category->sms_var_3}}</textarea>
                                </div>

                               
                                <div class="col-md-3">
                                    <label for="recipient-name" class="col-form-label">  @lang('sms.sms_var_4')  </label>
                                    <textarea rows="2" class="form-control" name="sms_var_4" id="sms_var_4" placeholder="@lang('sms.sms_var_4')" value="">{{$category->sms_var_4}}</textarea>
                                </div>

                                


                                <div class="col-md-3">
                                    <label for="recipient-name" class="col-form-label ">  @lang('sms.sms_var_1_en')   </label>
                                    <textarea rows="2" class="form-control" name="sms_var_1_en" id="sms_var_1_en" placeholder="@lang('sms.sms_var_1_en')" value=""> {{$category->sms_var_1_en}} </textarea>
                                </div>

                               
                                <div class="col-md-3">
                                    <label for="recipient-name" class="col-form-label">  @lang('sms.sms_var_2_en')  </label>
                                    <textarea rows="2" class="form-control" name="sms_var_2_en" id="sms_var_2_en" placeholder="@lang('sms.sms_var_2_en')" value=""> {{$category->sms_var_2_en}} </textarea>
                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name" class="col-form-label ">  @lang('sms.sms_var_3_en')   </label>
                                    <textarea rows="2" class="form-control" name="sms_var_3_en" id="sms_var_3_en" placeholder="@lang('sms.sms_var_3_en')" value=""> {{$category->sms_var_3_en}} </textarea>
                                </div>

                               
                                <div class="col-md-3">
                                    <label for="recipient-name" class="col-form-label">  @lang('sms.sms_var_4_en')  </label>
                                    <textarea rows="2" class="form-control" name="sms_var_4_en" id="sms_var_4_en" placeholder="@lang('sms.sms_var_4_en')" value=""> {{$category->sms_var_4_en}} </textarea>
                                </div>
                                

                                <div class="col-md-12">
                                    <div class="form-label"> @lang('sms.sms_type')</div>
                                    <div>
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="sms" name="sms" value="1"  @if( $category->sms_is_sms == 1) checked="" @endif >
                                            <span class="custom-control-label">@lang('sms.sms_is_sms')</span>
                                        </label>
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="whatsaap" name="whatsaap" value="1"  @if( $category->sms_is_whatsapp  == 1) checked="" @endif>
                                            <span class="custom-control-label">@lang('sms.sms_is_whatsapp')</span>
                                        </label>
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="email" name="email" value="1"  @if( $category->sms_is_email == 1) checked="" @endif>
                                            <span class="custom-control-label">@lang('sms.sms_is_email')</span>
                                        </label>
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="notification" name="notification" value="1"  @if( $category->sms_is_notification == 1) checked="" @endif>
                                            <span class="custom-control-label">@lang('sms.sms_is_notification')</span>
                                        </label>
                                    </div>
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

        $('#sms_provider_id_m').change(function () {
            if ($('#sms_provider_id_m').val() == '') {
                $('#sms_provider_id_m').addClass('is-invalid')
            } else {
                $('#sms_provider_id_m').removeClass('is-invalid');
            }
        });

        $('#sms_name_ar_m').keyup(function () {
            
            if ($('#sms_name_ar_m').val().length < 3) {
                $('#sms_name_ar_m').addClass('is-invalid');
            } else {
                $('#sms_name_ar_m').removeClass('is-invalid');
            }
        });

        $('#sms_name_en_m').keyup(function () {
            
            if ($('#sms_name_en_m').val().length < 3) {
                $('#sms_name_en_m').addClass('is-invalid');
            } else {
                $('#sms_name_en_m').removeClass('is-invalid');
            }
        });

        $('#sms_body_ar_m').keyup(function () {
            
            if ($('#sms_body_ar_m').val().length < 3) {
                $('#sms_body_ar_m').addClass('is-invalid');
            } else {
                $('#sms_body_ar_m').removeClass('is-invalid');
            }
        });


        $('#sms_body_en_m').keyup(function () {
            
            if ($('#sms_body_en_m').val().length < 3) {
                $('#sms_body_en_m').addClass('is-invalid');
            } else {
                $('#sms_body_en_m').removeClass('is-invalid');
            }
        });

    });

    function updateProvider()
        {
            if($('#edit_item_modal .is-invalid').length > 0){
                return toastr.warning('تاكد من ادخال كافة الحقول');
            }
            url = '{{ route('sms-category.update') }}'
            var form = new FormData($('#category_data_update_form')[0]);
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